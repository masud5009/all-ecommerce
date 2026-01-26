<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PageHeading;
use Illuminate\Http\Request;

class PageHeadingController extends Controller
{
    public function index()
    {
        $language = $this->getLanguage();
        $data = PageHeading::query()->where('language_id', $language->id)->first();
        return view('admin.settings.page-heading', compact('data'));
    }
}
