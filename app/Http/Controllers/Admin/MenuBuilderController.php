<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\MenuBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuBuilderController extends Controller
{
    public function index()
    {
        $menus = MenuBuilder::first();
        $menus = $menus ? json_decode($menus->menu, true) : [];

        return view('admin.menu-builder.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu' => 'required|array',
        ]);

        $menu = MenuBuilder::first();

        if ($menu) {
            $menu->language_id  =  app('defaultLang')->id;
            $menu->menu = json_encode($request->menu);
            $menu->save();
        } else {
            MenuBuilder::create([
                'menu' => json_encode($request->menu),
                'language_id'  =>  app('defaultLang')->id
            ]);
        }

        Session::flash('success', __("Menu saved successfully"));
        return response(['status' => 'success'], 200);
    }
}
