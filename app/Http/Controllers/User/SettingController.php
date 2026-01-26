<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        return view('user.settings.index');
    }

    /**
     * minor update
     */
    public function minorUpdateGeneralSetting(Request $request)
    {
        $user  = Auth::guard('web')->user();
        //theme update
        if ($request->filled('theme')) {
            $theme = $request->theme;
            if ($theme == 'light') {
                session()->put($user->username . '_themeColor', 'light');
            } else {
                session()->put($user->username . '_themeColor', 'dark');
            }
        }

        //default language update
        $prevDefLang = UserLanguage::query()->where('dashboard_default', '=', 1);

        $prevDefLang->update(['dashboard_default' => 0]);

        $language = UserLanguage::findOrFail($request->language_id);
        $language->dashboard_default = 1;
        $language->save();


        $insertData = $request->except(['_token', 'theme', 'language_id']);
        $rules = [
            'language_id' => 'required|exists:user_languages,id',
            'currency_symbol' => 'required',
            'currency_text' => 'required',
            'currency_rate' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        //update or insert data to basic settings table
        DB::table('user_settings')->updateOrInsert(
            ['user_id' => $user->id],
            $insertData
        );
        session()->flash('success', __('Updated Successfully'));
        return response()->json(['status' => 'success']);
    }
}
