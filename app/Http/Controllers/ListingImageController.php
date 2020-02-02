<?php

namespace App\Http\Controllers;

use App\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ListingImageController extends Controller {

    public function index() {
        return view('image');
    }

    public function save(Request $request) {
        request()->validate([

            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($image = $request->file('image')) {
            foreach ($image as $files) {
                $destinationPath = 'public/image/'; // upload path
                $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
                $insert[]['image'] = "$profileImage";
            }
        }

        $check = Image::insert($insert);

        return;
    }

    public function remove(Request $request, ListingImage $image) {
        $user = Auth::user();

        if ($image->listing->user->id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $image->delete();

        return View::make('', ['status' => 'success']);
    }

    public function removeBulk(Request $request) {
        $user = Auth::user();

        $images = $request->input('images');

        $listingImage = new ListingImage();
        $errors = [];

        foreach ($images as $image) {

            $i = $listingImage->find($image);

            if ($i->listing->user->id !== $user->id) {
                $errors[] = $i;
            } else {
                $i->delete();
            }

        }

        return response()->json([
            'status' => $errors ? false : true,
            'message' => 'Images removed',
            'errors' => $errors
        ]);
    }
}
