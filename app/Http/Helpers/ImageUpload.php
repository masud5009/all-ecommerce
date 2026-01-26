<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Validator;

class ImageUpload
{
    public static function store($directory, $file)
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $extension;
        @mkdir($directory, 0775, true);
        $file->move($directory, $fileName);

        return $fileName;
    }

    public static function update($directory, $newFile, $oldFile)
    {
        if (!$newFile || !$newFile->isValid()) {
            return $oldFile; // Return old file if new file is invalid
        }

        // Delete old file if exists
        if ($oldFile && file_exists($directory . $oldFile)) {
            @unlink($directory . $oldFile);
        }

        $extension = $newFile->getClientOriginalExtension();
        $fileName = uniqid() . '.' . $extension;
        @mkdir($directory, 0775, true);
        $newFile->move($directory, $fileName);
        return $fileName;
    }

    public static function sliderStore($img, $files, $directory)
    {
        if (!$img || !$img->isValid()) {
            return response()->json(['error' => 'Invalid image file']);
        }

        $allowedExts = array('jpg', 'png', 'jpeg', 'svg', 'webp');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    $ext = $img->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                },
            ]
        ];

        $validator = Validator::make($files, $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $filename = uniqid() . '.png';
        @mkdir($directory, 0775, true);
        $img->move($directory, $filename);

        return $filename;
    }
}
