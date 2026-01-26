<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Models\Admin\Language;
use App\Models\Setting;
use App\Rules\ImageExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * gerneral settings page show
     */
    public function generalSetting()
    {
        $data = DB::table('settings')->first();
        return view('admin.settings.general_setting', compact('data'));
    }


    /**
     * general settings update
     */
    public function updateGeneralSetting(Request $request)
    {
        $data = DB::table('settings')->first();
        $insertData = $request->except(['_token', 'favicon', 'website_logo']);
        $rules = [
            'website_title' => 'required',
            'currency_symbol' => 'required',
            'currency_symbol_position' => 'required',
            'currency_text' => 'required',
            'currency_text_position' => 'required',
            'currency_rate' => 'required|numeric|min:0',
        ];

        if (!$request->filled('favicon') && is_null($data->favicon)) {
            $rules['favicon'] = 'required|mimes:jpg,jpeg,png,svg,gif';
        }
        if (!$request->filled('website_logo') && is_null($data->website_logo)) {
            $rules['website_logo'] = 'required|mimes:jpg,jpeg,png,svg,gif';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        if ($request->hasFile('favicon')) {
            if (!empty($data->favicon)) {
                $insertData['favicon'] = ImageUpload::update(public_path('assets/front/img/'), $request->file('favicon'), $data->favicon);
            } else {
                $insertData['favicon'] = ImageUpload::store(public_path('assets/front/img/'), $request->file('favicon'));
            }
        }

        if ($request->hasFile('website_logo')) {
            if (!empty($data->website_logo)) {
                $insertData['website_logo'] = ImageUpload::update(public_path('assets/front/img/'), $request->file('website_logo'), $data->website_logo);
            } else {
                $insertData['website_logo'] = ImageUpload::store(public_path('assets/front/img/'), $request->file('website_logo'));
            }
        }


        //update or insert data to basic settings table
        DB::table('settings')->updateOrInsert(
            ['uniqid' => 1234],
            $insertData
        );
        session()->flash('success', 'General settings update successfull!');
        return response()->json(['status' => 'success']);
    }

    /**
     * minor update
     */
    public function minorUpdateGeneralSetting(Request $request)
    {
        //theme update
        if ($request->filled('theme')) {
            $theme = $request->theme;
            if ($theme == 'light') {
                session()->put('themeColor', 'light');
            } else {
                session()->put('themeColor', 'dark');
            }
        }

        //default language update
        $prevDefLang = Language::query()->where('dashboard_default', '=', 1);

        $prevDefLang->update(['dashboard_default' => 0]);

        $language = Language::findOrFail($request->language_id);
        $language->dashboard_default = 1;
        $language->save();


        $insertData = $request->except(['_token', 'theme', 'language_id']);
        $rules = [
            'language_id' => 'required|exists:languages,id',
            'currency_symbol' => 'required',
            'currency_text' => 'required',
            'currency_rate' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //update or insert data to basic settings table
        DB::table('settings')->updateOrInsert(
            ['uniqid' => 1234],
            $insertData
        );
                session()->flash('success', __('Updated Successfully'));
        return response()->json(['status' => 'success']);
    }

    public function seoInfo()
    {
        return view('admin.settings.seo');
    }

    /**
     * view maintenance mode page
     */
    public function maintenance()
    {
        $data = Setting::select('maintenance_status', 'maintenance_message', 'maintenance_image', 'bypass_token')
            ->first();
        return view('admin.settings.maintenance', compact('data'));
    }
    /**
     * update maintenance mode
     */
    public function maintenanceUpdate(Request $request)
    {
        $rules = [
            'maintenance_status' => 'required',
            'maintenance_message' => 'required'
        ];
        $messages = [];
        $setting = Setting::select('maintenance_image')->first();
        //check if image null or filled
        if (!$request->filled('maintenance_image') && is_null($setting->maintenance_image)) {
            $rules['maintenance_image'] = 'required';

            $messages['maintenance_image.required'] = 'The maintenance image field is required.';
        }
        //check if status is active or not
        if ($request->maintenance_status == 1) {
            $rules['bypass_token'] = 'required';
        }
        //now validation with image extension
        if ($request->hasFile('maintenance_image')) {
            $rules['maintenance_image'] = new ImageExtension();
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('maintenance_image')) {
            if (!empty($setting->maintenance_image)) {
                $image = ImageUpload::update(public_path('assets/img/'), $request->file('maintenance_image'), $setting->maintenance_image);
            } else {
                $image = ImageUpload::store(public_path('assets/img/'), $request->file('maintenance_image'));
            }
        }

        DB::table('settings')->updateOrInsert(
            ['uniqid' => 1234],
            [
                'maintenance_image' => $request->hasFile('maintenance_image') ? $image : $setting->maintenance_image,
                'maintenance_status' => $request->maintenance_status,
                'maintenance_message' => Purifier::clean($request->maintenance_message),
                'bypass_token' => $request->bypass_token
            ]
        );

        $down = "down";
        if ($request->filled('bypass_token')) {
            $down .= " --secret=" . $request->bypass_token;
        }

        if ($request->maintenance_status == 1) {
            Artisan::call('up');
            Artisan::call($down);
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
        } else {
            Artisan::call('up');
        }

        Session::flash('success', 'Maintenance Info updated successfully!');
        return response()->json(['status' => 'success'], 200);
    }
}
