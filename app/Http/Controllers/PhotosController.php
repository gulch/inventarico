<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as InterventionImage;
use App\Models\Photo;

class PhotosController extends Controller
{
    public function index()
    {
        $data = [
            'photos' => Photo::ofCurrentUser()->latest()->paginate(24)
        ];

        return view('photos.index', $data);
    }

    public function edit($id)
    {
        $photo = Photo::findOrFail($id);
        $this->ownerAccess($photo);

        $data = [
            'photo' => $photo
        ];

        return view('photos.edit', $data);
    }

    public function update($id)
    {
        $photo = Photo::findOrFail($id);
        $this->ownerAccess($photo);
        $photo->update($this->request->all());

        return redirect()->intended('/photos');
    }

    public function destroy($id)
    {
        $photo = Photo::find($id);
        $this->ownerAccess($photo);
        if (is_null($photo)) {
            return $this->jsonResponse(['message' => trans('app.item_not_found')]);
        } else {
            if (sizeof($photo->items)) {
                return $this->jsonResponse([
                    'message' => trans('app.photo_is_use_in_some_thing')
                ]);
            }

            if (sizeof($photo->operations)) {
                return $this->jsonResponse([
                    'message' => trans('app.photo_is_use_in_some_thing')
                ]);
            }

            self::removeImageFile($photo->path);

            /* Save original file but rename with prefix "REMOVED_" */
            $original_filepath = public_path() . config('app.original_image_upload_path');
            @rename($original_filepath . $photo->path, $original_filepath . 'REMOVED_' . $photo->path);

            /* Delete from DB */
            if (!$photo->delete()) {
                return $this->jsonResponse([
                    'message' => trans('app.can_not_delete_image')
                ]);
            }
        }

        return $this->jsonResponse(['success' => 'ok']);
    }

    public function upload()
    {
        return json_encode($this->doUploadImage());
    }

    public function uploadAndCreate()
    {
        $result = $this->doUploadImage();
        if (isset($result['path'])) {
            $photo = new Photo;
            $photo->setUserId();
            $photo->path = $result['path'];
            $photo->save();

            if (isset($photo->id)) {
                $result['id'] = $photo->id;
            }
        }

        return $this->jsonResponse($result);
    }

    public function getAllImagesList()
    {
        $photos = Photo::ofCurrentUser()->latest()->get();
        $result = [
            'success' => 1,
            'content' => view('partials.images-list', compact('photos'))->render()
        ];

        return $this->jsonResponse($result);
    }

    private function doUploadImage()
    {
        $result_array = [];
        if ($this->request->get('key')) {
            $result_array['key'] = $this->request->get('key');
        }
        if ($this->request->hasFile('image') || $this->request->hasFile('file')) {
            $image = $this->request->hasFile('image') ? $this->request->file('image') : $this->request->file('file');

            if (is_array($image) && count($image) > 0) {
                $image = $image[0];
            }

            if ($image->isValid()) {
                $filename = $this->addUniqueID($image->getClientOriginalName());

                $filepath_original = self::getFilePath(config('app.original_image_upload_path'));
                if ($image->move($filepath_original, $filename)) {
                    if ($setup = $this->request->get('setup')) {
                        switch ($setup) {
                            case 'photo':
                                $this->createPhotoImage($filepath_original, $filename);
                                $this->createThumbImage($filepath_original, $filename);
                                $result_array['filelink'] = self::getFileLink(config('app.thumb_image_upload_path'), $filename);
                                break;

                            case 'editor':
                                $this->createEditorImage($filepath_original, $filename);

                                $result_array['filekey'] = [
                                    'url' => self::getFileLink(config('app.editor_image_upload_path'), $filename)
                                ];

                                //$result_array['link'] = self::getFileLink(config('app.editor_image_upload_path'), $filename);
                                break;
                        }
                    }

                    $result_array['success'] = 'OK';
                    $result_array['path'] = date('/Y/m/') . $filename;
                    $result_array['type'] = $setup;
                } else {
                    $result_array['message'] = trans('app.can_not_move_image_to_folder_message');
                }
            } else {
                $result_array['message'] = trans('app.incorrect_image_format');
            }
        } else {
            $result_array['message'] =trans('app.image_not_found');
        }

        return $result_array;
    }

    private function manipulateImagebyInvervention(
        $filepath_original,
        $filepath_new,
        $filename,
        $width = null,
        $height = null,
        $crop = false,
        $quality = null
    ) {
        $ii = InterventionImage::getManager();
        $ii->configure(['driver' => 'imagick']);
        $img = $ii->make($filepath_original . $filename);
        if ($img) {
            if ($width && $height) {
                if (($img->height() / $img->width()) < ($height / $width)) {
                    $img->resize(null, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        /*$constraint->upsize();*/
                    });
                } else {
                    $img->resize($width, null, function ($constraint) {
                        $constraint->aspectRatio();
                        /*$constraint->upsize();*/
                    });
                }
                if ($crop) {
                    $img->crop($width, $height);
                }
            } else {
                if ($width || $height) {
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
            }

            /* Optimizations */
            $img->getCore()->stripImage();
            $img->sharpen(8);
            //$img->getCore()->setImageProperty('jpeg:sampling-factor', '4:4:1');

            $img->save($filepath_new . $filename, $quality);
        }
    }

    /*
     * Post Image
     */
    private function createPhotoImage($filepath_original, $filename)
    {
        $filepath = self::getFilePath(config('app.photo_image_upload_path'));
        $this->manipulateImagebyInvervention($filepath_original, $filepath, $filename);
    }

    /*
     * Thumb Image
     */
    private function createThumbImage($filepath_original, $filename)
    {
        $filepath_small = self::getFilePath(config('app.thumb_image_upload_path'));
        $this->manipulateImagebyInvervention($filepath_original, $filepath_small, $filename, 175, 130, true);
    }

    /*
     * Editor Image
     */
    private function createEditorImage($filepath_original, $filename)
    {
        $filepath = self::getFilePath(config('app.editor_image_upload_path'));
        $this->manipulateImagebyInvervention($filepath_original, $filepath, $filename, 1080, null, false);
    }

    private function addUniqueID($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        if (strlen($name) > 100) {
            $name = substr($name, 0, 100);
        }

        return strtolower(Str::slug($name) . '-' . uniqid() . '.' . $ext);
    }

    public static function getFilePath($path, $prefix = null)
    {
        if (!$prefix) {
            $prefix = date('/Y/m/');
        }
        $file_path = public_path() . $path . $prefix;

        if (!file_exists($file_path)) {
            mkdir($file_path, 750, true);
        }

        return $file_path;
    }

    public static function getFileLink($path, $filename)
    {
        return url('/') . $path . date('/Y/m/') . $filename;
    }

    public static function removeImageFile($path)
    {
        @unlink(public_path() . config('app.thumb_image_upload_path') . $path);
        @unlink(public_path() . config('app.photo_image_upload_path') . $path);
    }
}
