<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\MenuBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class MenuBuilderController extends Controller
{
    public function index(Request $request)
    {
        $language = $this->getLangUsingCode($request->language);
        $menus = MenuBuilder::where('language_id', $language->id)->first();
        $menus = $menus ? json_decode($menus->menu, true) : [];

        return view('admin.menu-builder.index', compact('menus', 'language'));
    }

    public function store(Request $request, $language_id)
    {
        $request->validate([
            'menu' => 'required|array',
        ]);

        MenuBuilder::updateOrCreate(
            ['language_id' => $language_id],
            ['menu' => json_encode($request->menu)]
        );

        Session::flash('success', __("Menu saved successfully"));
        return response(['status' => 'success'], 200);
    }
}
