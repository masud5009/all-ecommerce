<?php

namespace App\Http\Controllers\Admin;

use App\Models\HomeSlider;
use Illuminate\Http\Request;
use App\Http\Helpers\ImageUpload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    private string $imageDirectory = 'assets/img/home_slider/';
    private string $backgroundImageDirectory = 'assets/img/home_slider/background/';

    /**
     * slider page load
     */
    public function index(Request $request)
    {
        $sliders = HomeSlider::latest()->get();
        return view('admin.home.slider', compact('sliders'));
    }

    /**
     * store slider data
     */
    public function store(Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'background_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,avif|max:4096',
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        if ($request->hasFile('image')) {
            $directory = public_path($this->imageDirectory);
            $imageName = ImageUpload::store($directory, $request->file('image'));
        }

        $backgroundImageName = null;
        if ($request->hasFile('background_image')) {
            $directory = public_path($this->backgroundImageDirectory);
            $backgroundImageName = ImageUpload::store($directory, $request->file('background_image'));
        }

        $slider = new HomeSlider();
        $slider->language_id = $request->language_id;
        $slider->image = $imageName;
        $slider->background_image = $backgroundImageName;
        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->description = $request->description;
        $slider->button_text_1 = $request->button_text_1;
        $slider->button_url_1 = $request->button_url_1;
        $slider->button_text_2 = $request->button_text_2;
        $slider->button_url_2 = $request->button_url_2;
        $slider->image_left_badge_title = $request->image_left_badge_title;
        $slider->image_left_badge_sub_title = $request->image_left_badge_sub_title;
        $slider->image_right_badge_title = $request->image_right_badge_title;
        $slider->image_right_badge_sub_title = $request->image_right_badge_sub_title;
        $slider->status = $request->status;
        $slider->serial_number = $request->serial_number;
        $slider->save();

        session()->flash('success', __('Slider created successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * update slider data
     */
    public function update(Request $request)
    {
        $rules = [
            'image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'background_image' => 'nullable|mimes:jpg,jpeg,png,webp,svg,avif|max:4096',
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        $slider = HomeSlider::findOrFail($request->id);

        $imageName = $slider->image;
        if ($request->hasFile('image')) {
            $directory = public_path($this->imageDirectory);
            $imageName = ImageUpload::update($directory, $request->file('image'), $slider->image);
        }

        $backgroundImageName = $slider->background_image;
        if ($request->hasFile('background_image')) {
            $directory = public_path($this->backgroundImageDirectory);
            $backgroundImageName = ImageUpload::update(
                $directory,
                $request->file('background_image'),
                $slider->background_image
            );
        }

        $slider->language_id = $slider->language_id;
        $slider->image = $imageName;
        $slider->background_image = $backgroundImageName;
        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->description = $request->description;
        $slider->button_text_1 = $request->button_text_1;
        $slider->button_url_1 = $request->button_url_1;
        $slider->button_text_2 = $request->button_text_2;
        $slider->button_url_2 = $request->button_url_2;
        $slider->image_left_badge_title = $request->image_left_badge_title;
        $slider->image_left_badge_sub_title = $request->image_left_badge_sub_title;
        $slider->image_right_badge_title = $request->image_right_badge_title;
        $slider->image_right_badge_sub_title = $request->image_right_badge_sub_title;
        $slider->status = $request->status;
        $slider->serial_number = $request->serial_number;
        $slider->save();

        session()->flash('success', __('Slider updated successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * delete slider data
     */
    public function delete(Request $request)
    {
        $slider = HomeSlider::findOrFail($request->slider_id);
        $this->deleteImage($this->imageDirectory, $slider->image);
        $this->deleteImage($this->backgroundImageDirectory, $slider->background_image);
        $slider->delete();

        return redirect()->back()->with('success', __('Slider deleted successfully'));
    }

    /**
     * bulk delete sliders
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $slider = HomeSlider::findOrFail($id);
            $this->deleteImage($this->imageDirectory, $slider->image);
            $this->deleteImage($this->backgroundImageDirectory, $slider->background_image);
            $slider->delete();
        }
        session()->flash('success', __('Sliders deleted successfully'));

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * change slider status
     */
    public function changeStatus(Request $request)
    {
        HomeSlider::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }

    private function deleteImage(string $directory, ?string $fileName): void
    {
        if (!empty($fileName)) {
            @unlink(public_path($directory) . $fileName);
        }
    }
}
