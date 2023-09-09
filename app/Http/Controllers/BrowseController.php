<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Posts;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    //

    public function browseImage() {
        $data['photos'] = Image::get();
        return view('browse', $data);
    }

    public function browseBacaJuga() {
        $data['posts'] = Posts::get();
        return view('browse_baca_juga', $data);
    }
}
