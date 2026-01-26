<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    public function index()
    {
        $data = Setting::select('pusher_app_id', 'pusher_app_key', 'pusher_app_secret', 'pusher_app_cluster', 'pusher_status')->first();
        return view('admin.settings.plugin', compact('data'));
    }

    /**
     * pusher_update
     */
    public function pusher_update(Request $request)
    {
        $request->validate([
            'pusher_app_id' => 'required',
            'pusher_app_key' => 'required',
            'pusher_app_secret' => 'required',
            'pusher_app_cluster' => 'required',
            'pusher_status' => 'required'
        ]);

        Setting::updateOrInsert(
            ['uniqid' => 1234],
            [
                'pusher_app_id' => $request->pusher_app_id,
                'pusher_app_key' => $request->pusher_app_key,
                'pusher_app_secret' => $request->pusher_app_secret,
                'pusher_app_cluster' => $request->pusher_app_cluster,
                'pusher_status' => $request->pusher_status
            ]
        );

        session()->flash('success',__('Pusher update successfully'));
        return back();
    }
}
