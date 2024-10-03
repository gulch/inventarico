<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

use function config;
use function date;
use function file_exists;
use function mb_strlen;
use function mb_strtolower;
use function mb_substr;
use function mkdir;
use function pathinfo;
use function public_path;
use function redirect;
use function rename;
use function trans;
use function unlink;
use function url;
use function view;

final class PhotosController extends Controller
{
    private string $path_prefix;

    function __construct(Request $request)
    {
        $this->path_prefix = date('/Y/m/');

        parent::__construct($request);
    }

    public function index(): View
    {
        $data = [
            'photos' => Photo::ofCurrentUser()->latest()->paginate(24),
        ];

        return view('photos.index', $data);
    }

    public function edit(int $id): View
    {
        $photo = Photo::findOrFail($id);

        $this->ownerAccess($photo);

        $data = [
            'photo' => $photo,
        ];

        return view('photos.edit', $data);
    }

    public function update(int $id): RedirectResponse
    {
        $photo = Photo::findOrFail($id);

        $this->ownerAccess($photo);

        $photo->update($this->request->all());

        return redirect()->intended('/photos');
    }

    public function destroy(int $id): JsonResponse
    {
        $photo = Photo::find($id);

        $this->ownerAccess($photo);

        if (null === $photo) {
            return $this->jsonResponse(['message' => trans('app.item_not_found')]);
        }

        if ($photo->things->count()) {
            return $this->jsonResponse([
                'message' => trans('app.photo_is_use_in_some_thing'),
            ]);
        }

        if ($photo->operations->count()) {
            return $this->jsonResponse([
                'message' => trans('app.photo_is_use_in_some_thing'),
            ]);
        }

        @unlink(public_path() . config('inco.thumb_image_upload_path') . $photo->path);
        @unlink(public_path() . config('inco.photo_image_upload_path') . $photo->path);

        /* Save original file but rename with prefix "REMOVED_" */
        $original_filepath = public_path() . config('inco.original_image_upload_path');
        @rename($original_filepath . $photo->path, $original_filepath . 'REMOVED_' . $photo->path);

        /* Delete from DB */
        if (! $photo->delete()) {
            return $this->jsonResponse([
                'message' => trans('app.can_not_delete_image'),
            ]);
        }

        return $this->jsonResponse(['success' => 'ok']);
    }

    public function upload(): JsonResponse
    {
        return $this->jsonResponse($this->doUploadImage());
    }

    public function uploadAndCreate(): JsonResponse
    {
        $result = $this->doUploadImage();

        if (isset($result['path'])) {
            $photo = new Photo();
            $photo->setUserId();
            $photo->path = $result['path'];
            $photo->save();

            if (isset($photo->id)) {
                $result['id'] = $photo->id;
            }
        }

        return $this->jsonResponse($result);
    }

    public function getAllImagesList(): JsonResponse
    {
        return $this->jsonResponse([
            'success' => 1,
            'content' => view(
                'partials.images-list',
                [
                    'photos' => Photo::ofCurrentUser()->latest()->get()
                ]
            )->render(),
        ]);
    }

    private function getFilePath(string $path): string
    {
        $file_path = public_path() . $path . $this->path_prefix;

        if (! file_exists($file_path)) {
            mkdir($file_path, 750, true);
        }

        return $file_path;
    }

    private function getFileLink(string $path, string $filename): string
    {
        return url($path . $this->path_prefix . $filename);
    }

    /**
     * @return array<string, mixed|string>
     */
    private function doUploadImage(): array
    {
        $result_array = [];

        if ($this->request->get('key')) {
            $result_array['key'] = $this->request->get('key');
        }

        if ($this->request->hasFile('image') || $this->request->hasFile('file')) {

            /**
             * @var \Illuminate\Http\UploadedFile $image
             */
            $image = $this->request->hasFile('image') ? $this->request->file('image') : $this->request->file('file');

            if ($image->isValid()) {
                $filename = $this->getUniqueFilename($image->getClientOriginalName());

                $filepath_original = $this->getFilePath(config('inco.original_image_upload_path'));

                $image->move($filepath_original, $filename);

                if ($setup = $this->request->get('setup')) {
                    switch ($setup) {
                        case 'photo':
                            $this->createPhotoImage($filepath_original, $filename);

                            $this->createThumbImage($filepath_original, $filename);

                            $result_array['filelink'] = $this->getFileLink(config('inco.thumb_image_upload_path'), $filename);

                            break;

                        case 'editor':
                            $this->createEditorImage($filepath_original, $filename);

                            $result_array['filekey'] = [
                                'url' => $this->getFileLink(config('inco.editor_image_upload_path'), $filename),
                            ];

                            break;
                    }
                }

                $result_array['success'] = 'OK';
                $result_array['path'] = $this->path_prefix . $filename;
                $result_array['type'] = $setup;
            } else {
                $result_array['message'] = trans('app.incorrect_image_format');
            }
        } else {
            $result_array['message'] = trans('app.image_not_found');
        }

        return $result_array;
    }

    /*
     * General Image
     */
    private function createPhotoImage(string $filepath_original, string $filename): void
    {
        ImageService::manipulate(
            $filepath_original . $filename,
            $this->getFilePath(config('inco.photo_image_upload_path')) . $filename,
        );
    }

    /*
     * Thumb Image
     */
    private function createThumbImage(string $filepath_original, string $filename): void
    {
        ImageService::manipulate(
            $filepath_original . $filename,
            $this->getFilePath(config('inco.thumb_image_upload_path')) . $filename,
            [
                'width' => 175,
                'height' => 130,
                'crop' => true,
            ],
        );
    }

    /*
     * Editor Image
     */
    private function createEditorImage(string $filepath_original, string $filename): void
    {
        ImageService::manipulate(
            $filepath_original . $filename,
            $this->getFilePath(config('inco.editor_image_upload_path')) . $filename,
            [
                'width' => 960,
                'height' => 720,
            ],
        );
    }

    private function getUniqueFilename(string $filename): string
    {
        $ext = pathinfo($filename, \PATHINFO_EXTENSION);
        $name = pathinfo($filename, \PATHINFO_FILENAME);

        if (mb_strlen($name) > 100) {
            $name = mb_substr($name, 0, 100);
        }

        return mb_strtolower(Str::slug($name) . '-' . uniqid() . '.' . $ext);
    }
}
