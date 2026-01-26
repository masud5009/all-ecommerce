<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TranslateService
{
    private $apiLimits = [
        'google' => [
            'monthly_chars' => 500000,
            'name' => 'Google Cloud Translate'
        ],
        'mymemory' => [
            'daily_requests' => 1000,
            'daily_chars' => 10000,
            'name' => 'MyMemory Translate'
        ],
        'libre' => [
            'daily_requests' => 100,
            'name' => 'LibreTranslate'
        ]
    ];

    public function translateText($request)
    {
        $text   = $request['q'];
        $target = $request['target'];
        $source = $request['source'] ?? 'auto';

        if (empty($text) || !is_string($text)) {
            return response()->json(['translated' => $text]);
        }

        $textLength = strlen($text);
        $this->updateApiCounter('total', $textLength);

        // 1. Google Cloud Translation API (Priority 1)
        $googleKey = env('GOOGLE_TRANSLATE_API_KEY');
        if ($googleKey) {
            $response = Http::timeout(10)->post("https://translation.googleapis.com/language/translate/v2?key={$googleKey}", [
                'q'      => $text,
                'target' => $target,
                'source' => $source === 'auto' ? '' : $source,
                'format' => 'text'
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $translated = $result['data']['translations'][0]['translatedText'] ?? null;

                if ($translated) {
                    $this->updateApiCounter('google_success', $textLength);
                    return response()->json([
                        'translated' => $translated,
                        'api_used' => 'google'
                    ]);
                }
            }
        }

        // 2. MyMemory Translation API (Priority 2)
        $langPair = $source === 'auto' ? 'en|' . $target : $source . '|' . $target;
        $response = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
            'q'        => $text,
            'langpair' => $langPair,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $translated = $data['responseData']['translatedText'] ?? $text;

            if (is_string($translated) && $data['responseStatus'] == 200) {
                // Check for error messages in response
                if (!str_contains(strtolower($translated), 'my memory') &&
                    !str_contains(strtolower($translated), 'no query') &&
                    !str_contains(strtolower($translated), 'no translation found') &&
                    $translated !== $text) {

                    $this->updateApiCounter('mymemory_success', $textLength);
                    return response()->json([
                        'translated' => $translated,
                        'api_used' => 'mymemory'
                    ]);
                }
            }
        }

        // 3. LibreTranslate (Priority 3)
        try {
            $response = Http::timeout(15)->post('https://libretranslate.de/translate', [
                'q'      => $text,
                'source' => $source === 'auto' ? 'auto' : $source,
                'target' => $target,
                'format' => 'text',
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['translatedText']) && $result['translatedText'] !== $text) {
                    $this->updateApiCounter('libre_success', $textLength);
                    return response()->json([
                        'translated' => $result['translatedText'],
                        'api_used' => 'libre'
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silent fail - server might be down
        }

        // All APIs failed
        $this->updateApiCounter('failed', $textLength);

        // Return original text with upgrade suggestion
        if (!$googleKey) {
            return response()->json([
                'translated' => $text,
                'upgrade'    => true,
                'message'    => 'For unlimited high-quality translations, please add your Google Cloud API Key in Settings.'
            ]);
        }

        return response()->json(['translated' => $text]);
    }

    private function updateApiCounter($type, $chars = 0)
    {
        $statsFile = storage_path('app/translation_stats.json');

        // Default starting stats - fresh installation এর জন্য
        $defaultStats = [
            'total_requests' => 0,
            'total_chars' => 0,
            'google_requests' => 0,
            'mymemory_requests' => 0,
            'libre_requests' => 0,
            'failed_requests' => 0,
            'installation_date' => date('Y-m-d H:i:s'),
            'last_updated' => date('Y-m-d H:i:s')
        ];

        // Load existing stats or use default
        if (file_exists($statsFile)) {
            $stats = json_decode(file_get_contents($statsFile), true);
            if (!isset($stats['installation_date'])) {
                $stats['installation_date'] = date('Y-m-d H:i:s');
            }
        } else {
            $stats = $defaultStats;
        }

        // Update counters
        switch($type) {
            case 'total':
                $stats['total_requests']++;
                $stats['total_chars'] += $chars;
                break;
            case 'google_success':
                $stats['google_requests']++;
                break;
            case 'mymemory_success':
                $stats['mymemory_requests']++;
                break;
            case 'libre_success':
                $stats['libre_requests']++;
                break;
            case 'failed':
                $stats['failed_requests']++;
                break;
        }

        $stats['last_updated'] = date('Y-m-d H:i:s');

        // Save to file
        file_put_contents($statsFile, json_encode($stats, JSON_PRETTY_PRINT));
    }

    public function getApiStatistics()
    {
        $statsFile = storage_path('app/translation_stats.json');

        $defaultStats = [
            'total_requests' => 0,
            'total_chars' => 0,
            'google_requests' => 0,
            'mymemory_requests' => 0,
            'libre_requests' => 0,
            'failed_requests' => 0,
            'installation_date' => date('Y-m-d H:i:s'),
            'last_updated' => date('Y-m-d H:i:s')
        ];

        if (file_exists($statsFile)) {
            $stats = json_decode(file_get_contents($statsFile), true);
            // Ensure all required fields exist
            $stats = array_merge($defaultStats, $stats);
        } else {
            $stats = $defaultStats;
            // Create the file with default stats
            file_put_contents($statsFile, json_encode($stats, JSON_PRETTY_PRINT));
        }

        // Add calculated fields
        $stats['success_rate'] = $stats['total_requests'] > 0 ?
            round((($stats['total_requests'] - $stats['failed_requests']) / $stats['total_requests']) * 100) : 100;

        $stats['api_limits'] = $this->apiLimits;

        return $stats;
    }

    /**
     * Reset statistics (for admin)
     */
    public function resetStatistics()
    {
        $statsFile = storage_path('app/translation_stats.json');
        $defaultStats = [
            'total_requests' => 0,
            'total_chars' => 0,
            'google_requests' => 0,
            'mymemory_requests' => 0,
            'libre_requests' => 0,
            'failed_requests' => 0,
            'installation_date' => date('Y-m-d H:i:s'),
            'last_updated' => date('Y-m-d H:i:s'),
            'reset_date' => date('Y-m-d H:i:s')
        ];

        file_put_contents($statsFile, json_encode($defaultStats, JSON_PRETTY_PRINT));
        return $defaultStats;
    }
}
