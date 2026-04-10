<?php

namespace App\Services\Plugins;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class GoogleRecaptchaService
{
    private ?Setting $settings = null;

    public function isEnabled(): bool
    {
        $settings = $this->getSettings();

        return (int) ($settings?->google_recaptcha_status ?? 0) === 1
            && !empty($settings?->google_recaptcha_site_key)
            && !empty($settings?->google_recaptcha_secret_key);
    }

    public function siteKey(): ?string
    {
        return $this->getSettings()?->google_recaptcha_site_key;
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (!$this->isEnabled()) {
            return true;
        }

        if (blank($token)) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $this->getSettings()?->google_recaptcha_secret_key,
                    'response' => $token,
                    'remoteip' => $ip,
                ]);

            if (!$response->ok()) {
                return false;
            }

            return (bool) $response->json('success', false);
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function getSettings(): ?Setting
    {
        if ($this->settings instanceof Setting) {
            return $this->settings;
        }

        return $this->settings = Setting::select(
            'google_recaptcha_status',
            'google_recaptcha_site_key',
            'google_recaptcha_secret_key'
        )->first();
    }
}
