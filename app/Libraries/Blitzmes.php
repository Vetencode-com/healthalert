<?php

namespace App\Libraries;

class Blitzmes
{
    private static $api_key;
    public static $baseUrl;
    public static $isProduction = false;
    public static $prefixFormat = '62'; // Default value

    public static function init()
    {
        self::$api_key = config('blitzmes.api_key');
        self::$isProduction = config('blitzmes.is_production');
        self::$prefixFormat = config('blitzmes.prefix_format');
        self::$baseUrl = 'https://api.blitzmes.com/api/';
        date_default_timezone_set(config('blitzmes.timezone'));
    }

    public static function sendMessage($destination, string $message, ?array $replacer = null)
    {
        return self::performCurlRequest('send-message', [
            'receiver' => self::formatNumber($destination),
            'data' => [
                'message' => is_null($replacer) ? $message : self::formatMessage($message, $replacer),
            ],
        ]);
    }

    public static function sendMedia($destination, $caption, $mediaUrl, ?array $replacer = null)
    {
        return self::performCurlRequest('send-media', [
            'receiver' => self::formatNumber($destination),
            'data' => [
                'url' => !self::$isProduction ? 'https://vetencode.com/assets/landing-page/images/vetend/logo3.png' : $mediaUrl,
                'media_type' => self::defineMediaType($mediaUrl),
                'caption' => is_null($replacer) ? $caption : self::formatMessage($caption, $replacer),
            ],
        ]);
    }

    private static function performCurlRequest($endpoint, $body)
    {
        $url = self::$baseUrl . $endpoint;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(['api_key' => self::$api_key] + $body),
            CURLOPT_HTTPHEADER => [
                "Accept: /",
                "Content-Type: application/json",
            ],
            CURLOPT_SSL_VERIFYPEER => self::$isProduction,
        ]);

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return (object) [
                'status' => false,
                'message' => 'CURL Error',
                'http_status' => 400,
                'executed_at' => date('Y-m-d H:i:s'),
                'error' => $err,
                'formatted_message' => (isset($body['data']['message'])
                    ? $body['data']['message']
                    : $body['data']['caption']),
            ];
        } else {
            $resp = json_decode($response);
            return (object) [
                'status' => $resp->status,
                'http_status' => $resp->status ? $http_status : 1005,
                'message' => $resp->message,
                'executed_at' => date('Y-m-d H:i:s'),
                'error' => $resp,
                'formatted_message' => (isset($body['data']['message'])
                    ? $body['data']['message']
                    : $body['data']['caption']),
            ];
        }
    }

    public static function formatNumber($phoneNumber, $prefix = '62')
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (strpos($phoneNumber, $prefix) === 0) {
            return $phoneNumber;
        }

        if (strpos($phoneNumber, '0') === 0) {
            return $prefix . substr($phoneNumber, 1);
        }
        return $prefix . $phoneNumber;
    }

    public static function greetingTime(string $time): string
    {
        $hour = (int) date('H', strtotime($time));

        if ($hour >= 5 && $hour < 11) {
            return "pagi";
        } elseif ($hour >= 11 && $hour < 15) {
            return "siang";
        } elseif ($hour >= 15 && $hour < 19) {
            return "sore";
        } else {
            return "malam";
        }
    }

    public static function formatMessage(string $message, array $replacer): string
    {
        $keys = array_keys($replacer);
        $result = $message;
        $timePlaceHolderFound = false;
        foreach ($keys as $key) {
            if ($key == '{time}') {
                $timePlaceHolderFound = true;
            }
            $result = str_replace($key, $replacer[$key], $result);
        }

        // Jika tidak ditemukan {time} replacer, maka gunakan yang default
        if (!$timePlaceHolderFound) {
            $time = self::greetingTime(date('H:i'));
            $result = str_replace('{time}', $time, $result);
        }
        return $result;
    }

    public static function defineMediaType(string $mediaUrl): string
    {
        $extension = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'webp':
                return 'image';
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'mkv':
            case 'flv':
            case 'wmv':
            case 'webm':
                return 'video';
            case 'mp3':
            case 'wav':
            case 'ogg':
            case 'flac':
            case 'aac':
                return 'audio';
            default:
                return 'file';
        }
    }

    public static function generateOtp($digits = 5)
    {
        $otp = '';
        for ($i = 0; $i < $digits; $i++) {
            $otp .= mt_rand(0, 9);
        }
        return $otp;
    }
}
