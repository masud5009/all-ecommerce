<?php

namespace App\Services;

use App\Models\Admin\Language;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\User\UserLanguage;
use App\Services\Traits\LanguageKeywordTrait;

class LanguageService
{
    use LanguageKeywordTrait;

    /**
     * store language with validation files for [admin dashboard, user dashboard , admin frontend]
     */
    public function storeAdminLanguage($request)
    {
        try {
            DB::beginTransaction();
            $code = $request['code'];
            // retrieve all default language json files
            $data = file_get_contents(resource_path('lang/') . 'default.json');
            $admin_data = file_get_contents(resource_path('lang/') . 'admin_default.json');
            $user_data = file_get_contents(resource_path('lang/') . 'user_default.json');

            // create new language json files
            $json_file = trim($code) . '.json';
            $admin_json_file = 'admin_' . trim($code) . '.json';
            $user_json_file = 'user_' . trim($code) . '.json';

            // retrieve all default language json file path
            $path = resource_path('lang/') . $json_file;
            $admin_path = resource_path('lang/') . $admin_json_file;
            $user_path = resource_path('lang/') . $user_json_file;

            //put all default langauge json file content into new langauge
            File::put($path, $data);
            File::put($admin_path, $admin_data);
            File::put($user_path, $user_data);


            //create a new langauge
            $in['name'] = $request['name'];
            $in['code'] = $request['code'];
            $in['direction'] = $request['direction'];
            if (Language::where('is_default', 1)->count() > 0) {
                $in['is_default'] = 0;
            } else {
                $in['is_default'] = 1;
            }
            $lang = Language::create($in);


            // define the path for the language folder
            $langFolderPath = resource_path('lang/' . $lang->code);
            $adminDestinationFolder = resource_path('lang/' . 'admin_' . $lang->code);
            $userDestinationFolder = resource_path('lang/' . 'user_' . $lang->code);
            $this->copyFolder($langFolderPath, $adminDestinationFolder);
            $this->copyFolder($langFolderPath, $userDestinationFolder);

            if (!file_exists($langFolderPath)) {
                mkdir($langFolderPath, 0755, true);
            }

            // define the source path for the existing language files
            $sourcePath = resource_path('lang/admin_' . $lang->code);
            // Check if the source directory exists
            if (is_dir($sourcePath)) {
                $files = scandir($sourcePath);
                foreach ($files as $file) {
                    // Skip the current and parent directory indicators
                    if ($file !== '.' && $file !== '..') {
                        // Copy each file to the new language folder
                        copy($sourcePath . '/' . $file, $langFolderPath . '/' . $file);
                    }
                }
            }

            // Load validation attributes
            $validationFilePath = resource_path('lang/admin_' . $lang->code . '/validation.php');
            $userValidationFilePath = resource_path('lang/user_' . $lang->code . '/validation.php');

            //update existing keywords for validation attributes
            $newKeys = config('dashboard-validation');
            $this->updateValidationAttribute($newKeys, $admin_data, $validationFilePath);
            $this->updateValidationAttribute($newKeys, $user_data, $userValidationFilePath);

            DB::commit();
            return $lang;
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up created files on failure
            $this->cleanupLanguageFiles($code);

            throw $e;
        }
    }

    /**
     * update language with validation files for [admin dashboard, user dashboard , admin frontend]
     */
    public function updateAdminLanguage($request)
    {
        try {
            DB::beginTransaction();
            $language = Language::findOrFail($request['id']);
            //delete old file
            @unlink(resource_path('lang/') . $language->code . '.json');
            @unlink(resource_path('lang/') . 'admin_' . $language->code . '.json');
            @unlink(resource_path('lang/') . 'user_' . $language->code . '.json');
            File::deleteDirectory(resource_path('lang/') . 'admin_' . $language->code);
            File::deleteDirectory(resource_path('lang/') . 'user_' . $language->code);

            //add new file name for admin front
            $data = file_get_contents(resource_path('lang/') . 'default.json');
            $json_file = trim(strtolower($request['code'])) . '.json';
            $path = resource_path('lang/') . $json_file;

            //add new file name for admin dashboard
            $adminData = file_get_contents(resource_path('lang/') . 'admin_default.json');
            $admin_json = trim(strtolower('admin_' . $request['code'])) . '.json';
            $adminPath = resource_path('lang/') . $admin_json;

            //add new file name for admin dashboard
            $userData = file_get_contents(resource_path('lang/') . 'user_default.json');
            $user_json = trim(strtolower('user_' . $request['code'])) . '.json';
            $userPath = resource_path('lang/') . $user_json;

            File::put($path, $data);
            File::put($adminPath, $adminData);
            File::put($userPath, $userData);

            $language->name = $request['name'];
            $language->code = $request['code'];
            $language->direction = $request['direction'];
            $language->save();


            // define the path for the language folder
            $langFolderPath = resource_path('lang/' . $language->code);
            $adminDestinationFolder = resource_path('lang/' . 'admin_' . $language->code);
            $userDestinationFolder = resource_path('lang/' . 'user_' . $language->code);
            $this->copyFolder($langFolderPath, $adminDestinationFolder);
            $this->copyFolder($langFolderPath, $userDestinationFolder);

            if (!file_exists($langFolderPath)) {
                mkdir($langFolderPath, 0755, true);
            }
            // define the source path for the existing language files
            $sourcePath = resource_path('lang/admin_' . $language->code);
            // Check if the source directory exists
            if (is_dir($sourcePath)) {
                $files = scandir($sourcePath);
                foreach ($files as $file) {
                    // Skip the current and parent directory indicators
                    if ($file !== '.' && $file !== '..') {
                        // Copy each file to the new language folder
                        copy($sourcePath . '/' . $file, $langFolderPath . '/' . $file);
                    }
                }
            }
            // Load validation attributes
            $validationFilePath = resource_path('lang/admin_' . $language->code . '/validation.php');
            $userValidationFilePath = resource_path('lang/user_' . $language->code . '/validation.php');

            //update existing keywords for validation attributes
            $newKeys = config('dashboard-validation');
            $this->updateValidationAttribute($newKeys, $adminData, $validationFilePath);
            $this->updateValidationAttribute($newKeys, $userData, $userValidationFilePath);
            DB::commit();
            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * update keywords with validation attributes for [admin dashboard, user dashboard, admin frontend]
     */
    public function updateKeywords($request, $language_id)
    {
        $language = Language::findOrFail($language_id);
        $newkeywordsArr = $request['keys'];
        $content = json_encode($request['keys']);


        if ($request['userType'] == 'admin_dashboard') {
            //=== language messages
            $existingkeywordsArr = [];
            $fileLocated = resource_path('lang/') . 'admin_' . $language->code . '.json';
            if (file_exists($fileLocated)) {
                $existingkeywordsArr = json_decode(file_get_contents($fileLocated), true) ?? [];
            }

            $requestKeywordsArr = array_merge($existingkeywordsArr, $newkeywordsArr);
            file_put_contents(resource_path('lang/') . 'admin_' . $language->code . '.json', json_encode($requestKeywordsArr));

            //=====validation messages
            $validationData = include resource_path('lang/admin_' . $language->code . '/validation.php');
            $validationAttributes = $validationData['attributes'];
            $validationFilePath = resource_path('lang/admin_' . $language->code . '/validation.php');

            if (is_array($validationAttributes)) {
                foreach (config('dashboard-validation') as $key => $value) {
                    if (!array_key_exists($key, $validationAttributes)) {
                        $validationAttributes[$key] = $value;
                    }
                }
            }

            foreach ($requestKeywordsArr as $key => $value) {
                if (array_key_exists($key, $validationAttributes)) {
                    $validationAttributes[$key] = $value;
                }
            }

            $validationData['attributes'] = $validationAttributes;
            $validationContent = "<?php\n\nreturn " . var_export($validationData, true) . ";\n";

            file_put_contents($validationFilePath, $validationContent);
        } elseif ($request['userType'] == 'user_dashboard') {
            // Load validation attributes
            $validationFilePath = resource_path('lang/user_' . $language->code . '/validation.php');

            //update existing attributes
            $newKeys = config('dashboard-validation');
            $this->updateValidationAttribute($newKeys, $content, $validationFilePath);
            file_put_contents(resource_path('lang/') . 'user_' . $language->code . '.json', $content);
        } else {
            // Load validation attributes
            $validationFilePath = resource_path('lang/' . $lang->code . '/validation.php');

            //update existing attributes
            $newKeys = config('front-validation');
            $this->updateValidationAttribute($newKeys, $content, $validationFilePath);
            file_put_contents(resource_path('lang/') . $lang->code . '.json', $content);
        }
    }

    /**
     * store keywords for [admin dashboard, user dashboard, admin frontend, user frontend]
     */
    public function storeKeywords($request, $userType)
    {
        $newKeys = $request['keys'] ?? [];
        if (!is_array($newKeys) || empty($newKeys)) {
            return false;
        }

        // update default files
        $this->updateDefaultFilesWithNewKeys($userType, $newKeys);

        // update all languages
        $languages = Language::all();
        foreach ($languages as $language) {
            $this->addNewKeysToLanguage($userType, $language, $newKeys);
        }

        return true;
    }


    /**
     * Copy a folder and its contents to a new location.
     */
    public function copyFolder($sourcePath, $destinationPath)
    {
        if (!File::exists($sourcePath)) {
            return false;
        }

        if (File::exists($destinationPath)) {
            File::deleteDirectory($destinationPath);
        }
        return File::copyDirectory($sourcePath, $destinationPath);
    }

    /**
     * Update validation attributes in the language wise validation.php file
     */
    public function updateValidationAttribute($newKeys, $content, $validationFilePath)
    {
        try {
            // Load the existing validation array
            $validation = include($validationFilePath);

            // Ensure 'attributes' key exists
            if (!isset($validation['attributes']) || !is_array($validation['attributes'])) {
                $validation['attributes'] = [];
            }
        } catch (\Exception $e) {
            session()->flash('warning', __('Please provide a valid language code!'));
            return;
        }

        //update existing keys
        foreach ($newKeys as $key => $value) {
            if (!array_key_exists($key, $validation['attributes'])) {
                $validation['attributes'][$key] = $value;
            }
        }
        // update values which matching keys with new values
        $decodedContent = json_decode($content, true);

        if (is_array($decodedContent)) {
            foreach ($decodedContent as $key => $value) {
                if (array_key_exists($key, $validation['attributes'])) {
                    $validation['attributes'][$key] = $value;
                }
            }
        }

        //save the changes in validation attributes array
        $validationContent = "<?php\n\nreturn " . var_export($validation, true) . ";\n";
        file_put_contents($validationFilePath, $validationContent);
    }

    /**
     * Clean up language files in case of transaction failure
     */
    private function cleanupLanguageFiles($code)
    {
        $filesToDelete = [
            resource_path('lang/' . trim($code) . '.json'),
            resource_path('lang/admin_' . trim($code) . '.json'),
            resource_path('lang/user_' . trim($code) . '.json'),
        ];

        $foldersToDelete = [
            resource_path('lang/' . trim($code)),
            resource_path('lang/admin_' . trim($code)),
            resource_path('lang/user_' . trim($code)),
        ];

        foreach ($filesToDelete as $file) {
            if (File::exists($file)) {
                File::delete($file);
            }
        }

        foreach ($foldersToDelete as $folder) {
            if (File::exists($folder)) {
                File::deleteDirectory($folder);
            }
        }
    }
}
