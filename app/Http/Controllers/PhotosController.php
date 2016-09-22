<?php

namespace App\Http\Controllers;
use App\Models\Photo;

class PhotosController extends Controller
{
    public function index()
    {
        $data = [
            'photos' => Photo::ofCurrentUser()->paginate(24)
        ];

        return view('photos.list', $data);
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

            self::removeImageFile($photo->path);

            /* Save original file but rename with prefix "REMOVED_" */
            $original_filepath = public_path() . config('app.original_image_upload_path');
            @rename($original_filepath . $image->path, $original_filepath . 'REMOVED_' . $image->path);

            /* Delete from DB */
            if (!$photo->delete()) {
                return $this->jsonResponse(['message' => trans('app.can_not_delete_image')]);
            }
        }

        return json_encode(['success' => 'ok']);
    }

    public function upload()
    {
        return json_encode($this->doUploadImage());
    }

    public function uploadAndCreate()
    {
        $result = $this->doUploadImage();
        if (isset($result['path'])) {
            $photo = Photo::create([
                'path' => $result['path']
            ]);

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
}
