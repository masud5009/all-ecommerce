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
            'gemini_text_model',
            'facebook_pixel_status',
            'facebook_pixel_id',
            'google_recaptcha_status',
            'google_recaptcha_site_key',
            'google_recaptcha_secret_key',
            'google_analytics_status',
            'google_analytics_measurement_id'
        )->first() ?? new Setting();

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

    /**
     * facebook_pixel_update
     */
    public function facebook_pixel_update(Request $request)
    {
        $request->validate([
            'facebook_pixel_status' => 'required|in:0,1',
            'facebook_pixel_id' => 'nullable|required_if:facebook_pixel_status,1|string|max:255',
        ]);

        Setting::updateOrInsert(
            ['uniqid' => 1234],
            [
                'facebook_pixel_status' => $request->facebook_pixel_status,
                'facebook_pixel_id' => $request->facebook_pixel_id,
            ]
        );

        session()->flash('success', __('Facebook Pixel update successfully'));
        return back()->with('active_plugin', 'facebook_pixel');
    }

    /**
     * google_recaptcha_update
     */
    public function google_recaptcha_update(Request $request)
    {
        $request->validate([
            'google_recaptcha_status' => 'required|in:0,1',
            'google_recaptcha_site_key' => 'nullable|required_if:google_recaptcha_status,1|string|max:255',
            'google_recaptcha_secret_key' => 'nullable|required_if:google_recaptcha_status,1|string|max:255',
        ]);

        Setting::updateOrInsert(
            ['uniqid' => 1234],
            [
                'google_recaptcha_status' => $request->google_recaptcha_status,
                'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
                'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key,
            ]
        );

        session()->flash('success', __('Google Recaptcha update successfully'));
        return back()->with('active_plugin', 'google_recaptcha');
    }

    /**
     * google_analytics_update
     */
    public function google_analytics_update(Request $request)
    {
        $request->validate([
            'google_analytics_status' => 'required|in:0,1',
            'google_analytics_measurement_id' => 'nullable|required_if:google_analytics_status,1|string|max:255',
        ]);

        Setting::updateOrInsert(
            ['uniqid' => 1234],
            [
                'google_analytics_status' => $request->google_analytics_status,
                'google_analytics_measurement_id' => $request->google_analytics_measurement_id,
            ]
        );

        session()->flash('success', __('Google Analytics update successfully'));
        return back()->with('active_plugin', 'google_analytics');
    }
}
