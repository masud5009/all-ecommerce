<?php

namespace App\Http\Controllers\Admin;

use App\Models\HomeSlider;
use Illuminate\Http\Request;
use App\Http\Helpers\ImageUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    private string $imageDirectory = 'assets/img/home_slider/';

    public function index(Request $request)
    {
        $sliders = HomeSlider::orderBy('serial_number', 'ASC')->get();
        return view('admin.home.slider', compact('sliders'));
    }

    public function store(Request $request)
    {
        $tableCheck = $this->ensureSliderTableExists();
        if ($tableCheck) {
            return $tableCheck;
        }

        $validator = Validator::make($request->all(), $this->sliderRules());

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $slider = new HomeSlider();
        $uploadResult = $this->saveSlider($slider, $request);

        if ($uploadResult !== true) {
            return $uploadResult;
        }

        session()->flash('success', __('Slider created successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $tableCheck = $this->ensureSliderTableExists();
        if ($tableCheck) {
            return $tableCheck;
        }

        $rules = array_merge($this->sliderRules(true), [
            'id' => 'required|exists:home_sliders,id',
        ]);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $slider = HomeSlider::findOrFail((int) $request->id);
        $uploadResult = $this->saveSlider($slider, $request, true);

        if ($uploadResult !== true) {
            return $uploadResult;
        }

        session()->flash('success', __('Slider updated successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        if ($this->ensureSliderTableExists()) {
            return redirect()->back()->with('error', __('The home slider table is not available yet.'));
        }

        $request->validate([
            'slider_id' => 'required|exists:home_sliders,id',
        ]);

        $slider = HomeSlider::findOrFail((int) $request->slider_id);
        $this->deleteSliderImage($slider->image);
        $slider->delete();

        return redirect()->back()->with('success', __('Slider deleted successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $tableCheck = $this->ensureSliderTableExists();
        if ($tableCheck) {
            return $tableCheck;
        }

        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:home_sliders,id',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $sliders = HomeSlider::whereIn('id', $request->ids)->get();

        foreach ($sliders as $slider) {
            $this->deleteSliderImage($slider->image);
            $slider->delete();
        }

        session()->flash('success', __('Sliders deleted successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    public function changeStatus(Request $request)
    {
        if ($this->ensureSliderTableExists()) {
            return 'error';
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:home_sliders,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return 'error';
        }

        HomeSlider::where('id', (int) $request->id)->update([
            'status' => (int) $request->status,
        ]);

        return 'success';
    }

    private function sliderRules(bool $isUpdate = false): array
    {
        $imageRule = $isUpdate
            ? 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048'
            : 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048';

        $rules = [
            'image' => $imageRule,
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text_1' => 'required|string|max:255',
            'button_url_1' => 'required|string|max:255',
            'button_text_2' => 'required|string|max:255',
            'button_url_2' => 'required|string|max:255',
            'image_left_badge_title' => 'nullable|string|max:255',
            'image_left_badge_sub_title' => 'nullable|string|max:255',
            'image_right_badge_title' => 'nullable|string|max:255',
            'image_right_badge_sub_title' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'serial_number' => 'required|integer|min:0',
        ];

        if (Schema::hasColumn('home_sliders', 'language_id')) {
            $rules['language_id'] = $isUpdate
                ? 'nullable|exists:languages,id'
                : 'required|exists:languages,id';
        }

        return $rules;
    }

    private function saveSlider(HomeSlider $slider, Request $request, bool $isUpdate = false)
    {
        if (Schema::hasColumn('home_sliders', 'language_id') && $request->filled('language_id')) {
            $slider->language_id = (int) $request->language_id;
        }

        if ($request->hasFile('image')) {
            $directory = public_path($this->imageDirectory);
            $imageName = $isUpdate
                ? ImageUpload::update($directory, $request->file('image'), $slider->image)
                : ImageUpload::store($directory, $request->file('image'));

            if (!$imageName) {
                return Response::json([
                    'errors' => [
                        'image' => [__('Image upload failed. Please try again.')],
                    ],
                ], 422);
            }

            $slider->image = $imageName;
        }

        $slider->title = trim((string) $request->title);
        $slider->sub_title = trim((string) $request->sub_title);
        $slider->description = trim((string) $request->description);
        $slider->button_text_1 = trim((string) $request->button_text_1);
        $slider->button_url_1 = trim((string) $request->button_url_1);
        $slider->button_text_2 = trim((string) $request->button_text_2);
        $slider->button_url_2 = trim((string) $request->button_url_2);
        $slider->image_left_badge_title = $request->filled('image_left_badge_title')
            ? trim((string) $request->image_left_badge_title)
            : null;
        $slider->image_left_badge_sub_title = $request->filled('image_left_badge_sub_title')
            ? trim((string) $request->image_left_badge_sub_title)
            : null;
        $slider->image_right_badge_title = $request->filled('image_right_badge_title')
            ? trim((string) $request->image_right_badge_title)
            : null;
        $slider->image_right_badge_sub_title = $request->filled('image_right_badge_sub_title')
            ? trim((string) $request->image_right_badge_sub_title)
            : null;
        $slider->status = (int) $request->status;
        $slider->serial_number = (int) $request->serial_number;
        $slider->save();

        return true;
    }

    private function deleteSliderImage(?string $image): void
    {
        if (!empty($image)) {
            @unlink(public_path($this->imageDirectory) . $image);
        }
    }

    private function ensureSliderTableExists()
    {
        if (!Schema::hasTable('home_sliders')) {
            return Response::json([
                'errors' => [
                    'slider' => [__('The home slider table is not available yet. Run the migration first.')],
                ],
            ], 422);
        }

        return null;
    }
}
