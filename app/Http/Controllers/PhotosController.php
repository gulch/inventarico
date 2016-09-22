<?php

namespace App\Http\Controllers;
use App\Models\Photo;

class PhotosController extends Controller
{
    public function index()
    {
        $data = [
            'photos' => Photo::paginate(24)
        ];

        return view('photos.list', $data);
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function upload()
    {
        //
    }
}
