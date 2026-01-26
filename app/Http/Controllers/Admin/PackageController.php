<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\PackageRequest;
use App\Http\Requests\Package\PackageUpdateRequest;
use App\Models\Admin\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function index()
    {
        $all_packages = Package::orderBy('id', 'desc')->get();
        return view('admin.package.index', compact('all_packages'));
    }


    public function store(PackageRequest $request)
    {
        Package::storeFromRequest($request);
        session()->flash('success', __('Created successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id, Request $request)
    {
        $data['package'] = Package::findOrFail($id);
        return view('admin.package.edit', $data);
    }


    public function update(PackageUpdateRequest $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->updateFromRequest($request);

        session()->flash('success', __('Updated successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $package = Package::findOrFail($request->package_id);
        $package->delete();
        session()->flash('success', 'Package deleted successfully!');
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $package = Package::query()->findOrFail($id);
            $package->delete();
        }
        session()->flash('success', 'Packages deleted successful!');
        return response()->json(['status' => 'success'], 200);
    }

    public function setting()
    {
        return view('admin.package.setting');
    }


    public function settingUpdate(Request $request)
    {
        $rules = ['package_expire_day' => 'required|numeric|max:30|min:1'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        DB::table('settings')->updateOrInsert(
            ['uniqid' => 1234],
            [
                'package_expire_day' => $request->package_expire_day,
            ]
        );

        return redirect()->back()->with('success', __('Settings update successfully'));
    }

    public function changeStatus(Request $request)
    {
        Package::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
