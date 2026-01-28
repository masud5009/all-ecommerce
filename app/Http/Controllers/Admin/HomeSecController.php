<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSectionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeSecController extends Controller
{
    public function index(Request $request)
    {
        $data = HomeSectionSetting::pluck('value', 'key')->toArray();
        return view('admin.home.imagetext', compact('data'));
    }

    public function update(Request $request)
    {
        $sections = config('website');

        DB::transaction(function () use ($request, $sections) {
            foreach ($sections as $sectionName => $fields) {
                foreach ($fields as $key => $meta) {

                    $type = $meta['type'] ?? 'text';

                    // FILE: upload only if file provided
                    if ($type === 'file') {
                        if ($request->hasFile($key)) {
                            $path = $request->file($key)->store('home', 'public');
                            $this->upsertSetting($sectionName, $key, $path, $type);
                        }
                        continue;
                    }

                    // TEXT / URL / TEXTAREA
                    if ($request->has($key)) {
                        $value = $request->input($key);
                        $this->upsertSetting($sectionName, $key, $value, $type);
                    }
                }
            }
        });

        return back()->with('success', 'Home section updated successfully.');
    }

    private function upsertSetting(string $section, string $key, $value, ?string $type = null): void
    {
        HomeSectionSetting::updateOrCreate(
            ['key' => $key],
            [
                'section' => $section,
                'value'   => is_null($value) ? null : (string) $value,
                'type'    => $type,
            ]
        );
    }
}
