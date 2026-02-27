<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PluginController extends Controller
{
    public function index()
    {
        $data = Setting::select(
            'pusher_app_id',
            'pusher_app_key',
            'pusher_app_secret',
            'pusher_app_cluster',
            'pusher_status',
            'stedfast_api_key',
            'stedfast_secret_key',
            'stedfast_status',
            'gemini_status',
            'gemini_api_key',
            'gemini_image_model',
            'gemini_text_model'
        )->first();
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

        session()->flash('success', __('Pusher update successfully'));
        return back()->with('active_plugin', 'pusher');
    }

    /**
     * stedfast_update
     */
    public function stedfast_update(Request $request)
    {
        $request->validate([
            'stedfast_api_key' => 'required',
            'stedfast_secret_key' => 'required',
            'stedfast_status' => 'required'
        ]);

        Setting::updateOrInsert(
            ['uniqid' => 1234],
            [
                'stedfast_api_key' => $request->stedfast_api_key,
                'stedfast_secret_key' => $request->stedfast_secret_key,
                'stedfast_status' => $request->stedfast_status
            ]
        );

        session()->flash('success', __('Stedfast update successfully'));
        return back()->with('active_plugin', 'stedfast');
    }


    /**
     * stedfast_update
     */
    public function gemini_update(Request $request)
    {
        $request->validate([
            'gemini_api_key' => 'required',
        ]);

        Setting::updateOrInsert(
            ['uniqid' => 1234],
            [
                'gemini_status' => $request->gemini_status,
                'gemini_api_key' => $request->gemini_api_key,
                'gemini_image_model' => $request->gemini_image_model,
                'gemini_text_model' => $request->gemini_text_model
            ]
        );

        session()->flash('success', __('Gemini update successfully'));
        return back()->with('active_plugin', 'gemini');
    }
}
