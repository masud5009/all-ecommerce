<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $slider = \App\Models\HomeSlider::first();
        return view('admin.home.slider', compact('slider'));
    }
}
