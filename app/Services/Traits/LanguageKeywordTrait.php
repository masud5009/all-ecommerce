<?php

namespace App\Services\Traits;

trait LanguageKeywordTrait
{
    private function updateDefaultFilesWithNewKeys($userType, $newKeys)
    {
        $file = $this->getDefaultFilePath($userType);
        if (!$file || !file_exists($file)) return;

        $content = json_decode(file_get_contents($file), true) ?? [];
        $content = $this->mergeNewKeys($content, $newKeys);
        file_put_contents($file, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function addNewKeysToLanguage($userType, $language, $newKeys)
    {
        $jsonFile = $this->getJsonFilePath($userType, $language->code);
        $validationFile = $this->getValidationFilePath($userType, $language->code);

        // Update JSON file
        if (file_exists($jsonFile)) {
            $content = json_decode(file_get_contents($jsonFile), true) ?? [];
            $content = $this->mergeNewKeys($content, $newKeys);
            file_put_contents($jsonFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // Update validation.php only for dashboard
        if (in_array($userType, ['admin_dashboard', 'user_dashboard']) && file_exists($validationFile)) {
            $configKeys = config('dashboard-validation');
            $this->updateValidationAttribute($configKeys, json_encode($newKeys), $validationFile);
        }

        // For user_frontend: update DB
        if ($userType === 'user_frontend') {
            $existing = json_decode($language->customer_keywords ?? '{}', true);
            $merged = array_merge($existing, $newKeys);
            $language->customer_keywords = json_encode($merged);
            $language->save();
        }
    }

    private function getDefaultFilePath($userType)
    {
        return match ($userType) {
            'admin_dashboard' => resource_path('lang/admin_default.json'),
            'user_dashboard'  => resource_path('lang/user_default.json'),
            'admin_frontend',
            'user_frontend'   => resource_path('lang/default.json'),
            default           => null,
        };
    }

    private function getJsonFilePath($userType, $code)
    {
        return match ($userType) {
            'admin_dashboard' => resource_path("lang/admin_{$code}.json"),
            'user_dashboard'  => resource_path("lang/user_{$code}.json"),
            'admin_frontend',
            'user_frontend'   => resource_path("lang/{$code}.json"),
            default           => null,
        };
    }

    private function getValidationFilePath($userType, $code)
    {
        return match ($userType) {
            'admin_dashboard' => resource_path("lang/admin_{$code}/validation.php"),
            'user_dashboard'  => resource_path("lang/user_{$code}/validation.php"),
            'admin_frontend',
            'user_frontend'   => resource_path("lang/{$code}/validation.php"),
            default           => null,
        };
    }

    private function mergeNewKeys($content, $newKeys)
    {
        foreach ($newKeys as $key => $value) {
            if (!array_key_exists($key, $content)) {
                $content[$key] = $value;
            }
        }
        return $content;
    }

    public function updateValidationAttribute($newKeys, $content, $validationFilePath)
    {
        if (!file_exists($validationFilePath)) return;

        $validation = include $validationFilePath;
        if (!isset($validation['attributes']) || !is_array($validation['attributes'])) {
            $validation['attributes'] = [];
        }

        // Add missing config keys
        foreach ($newKeys as $key => $value) {
            if (!array_key_exists($key, $validation['attributes'])) {
                $validation['attributes'][$key] = $value;
            }
        }

        // Update with actual translation values
        $decoded = json_decode($content, true) ?? [];
        foreach ($decoded as $key => $value) {
            if (array_key_exists($key, $validation['attributes'])) {
                $validation['attributes'][$key] = $value;
            }
        }

        $export = "<?php\n\nreturn " . var_export($validation, true) . ";\n";
        file_put_contents($validationFilePath, $export);
    }
}
