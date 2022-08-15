<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Api\Core;

class BETinymceController extends Controller
{
    public function upload(Request $request)
    {
        $apiCore = new Core();
        reset($_FILES);
        $temp = current($_FILES);

        if (is_uploaded_file($temp['tmp_name'])) {
            $ext = explode(".", $temp['name']);
            $filename = time() . "." . $ext[count($ext)-1];

            $filetowrite = public_path() . "/uploaded/tinymce/" . $filename;

            $filetowrite = $apiCore->platformSlashes($filetowrite);

            move_uploaded_file($temp['tmp_name'], $filetowrite);

            $fileURL = url('public/uploaded/tinymce/') . '/' . $filename;

            $path = public_path('uploaded/tinymce/' . $filename);

            $image = \Image::make($path);
            // perform orientation using intervention
            $image->orientate();
            // save image
            $image->save();

            // Respond to the successful upload with JSON.
            echo json_encode(array('location' => $fileURL));
        } else {
            echo json_encode(array('location' => ''));
        }

    }
}
