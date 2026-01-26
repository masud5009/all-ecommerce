<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageStore;
use App\Http\Requests\Admin\LanguageUpdate;
use App\Models\Admin\Language;
use App\Models\User\UserLanguage;
use Illuminate\Support\Facades\File;
use App\Services\LanguageService;
use App\Services\TranslateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{

    public $languageService;
    function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }


    public function index()
    {
        $data = Language::all();
        return view('admin.language.index', compact('data'));
    }

    public function store(LanguageStore $request)
    {
        $this->languageService->storeAdminLanguage($request->all());
        Session::flash('success', __('Added successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(LanguageUpdate $request)
    {
        $this->languageService->updateAdminLanguage($request->all());

        Session::flash('success', __('Updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }


    /**
     * frontend make default
     */
    public function makeDefault($id)
    {
        $prevDefLang = Language::query()->where('is_default', '=', 1);
        $prevDefLang->update(['is_default' => 0]);

        $language = Language::findOrFail($id);

        $language->update(['is_default' => 1]);
        return back()->with('success', $language->name . ' ' . 'is set as website default language.');
    }

    /**
     * dashboard make default
     */
    public function dashboardDefault($id)
    {
        $prevDefLang = Language::query()->where('dashboard_default', '=', 1);

        $prevDefLang->update(['dashboard_default' => 0]);

        $language = Language::findOrFail($id);
        $language->dashboard_default = 1;
        $language->save();

        return back()->with('success', $language->name . ' ' . 'is set as dashboard default language.');
    }

    /**
     * add frontend keyword
     */
    public function addKeyword(Request $request)
    {
        $rules = ['keyword' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        $key = $request->input('keyword');
        $value = $request->input('value', $key); // if value not sent, use key as value

        $success = $this->languageService->storeKeywords([
            'keys' => [$key => $value]
        ], 'admin_frontend');

        if (!$success) {
            return response()->json(['error' => 'Failed to add keyword'], 500);
        }

        Session::flash('success', __('Added successfully'));
        return response()->json(['status' => 'success'], 200);
    }
    /**
     * add admin keywords
     */
    public function addAdminKeyword(Request $request)
    {
        $rules = ['keyword' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 422);
        }

        $key = $request->input('keyword');
        $value = $request->input('value', $key);

        $success = $this->languageService->storeKeywords([
            'keys' => [$key => $value]
        ], 'admin_dashboard');

        if (!$success) {
            return response()->json(['error' => 'Failed to add keyword'], 500);
        }

        Session::flash('success', __('Added successfully'));
        return response()->json(['status' => 'success'], 200);
    }



    /**
     * display edit admin frontend keyword page
     */
    public function editKeyword($id)
    {
        $data['language'] = Language::findOrFail($id);
        $json = file_get_contents(resource_path('lang/') . $data['language']->code . '.json');
        $data['keywords'] = json_decode($json, true);
        $data['userType'] =  'admin_frontend';

        return view('admin.language.edit_keyword', $data);
    }
    /**
     * display edit admin dashboard keyword page
     */
    public function AdminEditKeyword($id)
    {
        $data['language'] = Language::findOrFail($id);
        $json = file_get_contents(resource_path('lang/') . 'admin_' . $data['language']->code . '.json');
        $data['keywords'] = json_decode($json, true);
        $data['userType'] =  'admin_dashboard';

        return view('admin.language.edit_keyword', $data);
    }

    /**
     * update keyword [admin dashboard, user dashboard, user frontend, admin frontend]
     */
    public function updateKeyword(Request $request, $id)
    {
        $content = json_encode($request->keys);
        if ($content === 'null') {
            return back()->with('alert', __('At least one keyword is required'));
        }

        $this->languageService->updateKeywords($request->all(), $id);
        return back()->with('success', __('Updated Successfully'));
    }

    /**
     * delete language
     */
    public function delete(Request $request)
    {
        $language = Language::query()->find($request->lang_id);

        if ($language->is_default == 1) {
            return redirect()->back()->with('warning', 'Default language cannot be delete!');
        }

        @unlink(resource_path('lang/') . $language->code . '.json');
        @unlink(resource_path('lang/admin_') . $language->code . '.json');
        @unlink(resource_path('lang/user_') . $language->code . '.json');

        File::deleteDirectory(resource_path('lang/') . 'admin_' . $language->code);
        File::deleteDirectory(resource_path('lang/') . 'user_' . $language->code);

        //deleting user_language wich added by admin
        UserLanguage::where('created_by', 'admin')->where('code', $language->code)->delete();

        $language->delete();

        Session::flash('success', $language->name . ' language deleted successfully!');

        return redirect()->back();
    }

    /**
     * auto translate language keywords
     */
    public function translateText(Request $request)
    {
        $translateService = new TranslateService();
        return $translateService->translateText($request->all());
    }
}
