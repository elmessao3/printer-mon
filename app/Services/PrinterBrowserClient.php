<?php

namespace App\Services;

class PrinterBrowserClient
{
    public function get($ip)
    {
        $url = "https://$ip/sws/app/information/home/home.json";

        $response = $this->request($url, $ip);

        return $this->parse($response);
    }

    private function request($url, $ip)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,

            CURLOPT_TIMEOUT => 10,

            CURLOPT_COOKIEJAR => storage_path("printer_cookie.txt"),
            CURLOPT_COOKIEFILE => storage_path("printer_cookie.txt"),

            CURLOPT_HTTPHEADER => [
                "User-Agent: Mozilla/5.0",
                "Accept: application/json, text/plain, */*",
                "Referer: https://$ip/sws/index.html"
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function parse($response)
    {
        if (!$response || str_contains($response, "<html")) {
            return [
                'status' => 'offline',
                'toner' => null,
                'drum' => null,
            ];
        }

        $fixed = $this->fixHpJson($response);

        $data = json_decode($fixed, true);

        if (!$data) {
            return [
                'status' => 'error',
                'toner' => null,
                'drum' => null,
            ];
        }

        return [
            'status' => 'online',
            'toner' => $data['toner_black']['remaining'] ?? null,
            'drum'  => $data['drum_black']['remaining'] ?? null,
        ];
    }

    private function fixHpJson($text)
    {
        // remove control chars
        $text = str_replace(["\r", "\n", "\t"], "", $text);

        // fix keys (HP bug)
        $text = preg_replace('/([{,])\s*([a-zA-Z0-9_]+)\s*:/', '$1"$2":', $text);

        // fix trailing commas (IMPORTANT)
        $text = preg_replace('/,\s*}/', '}', $text);
        $text = preg_replace('/,\s*]/', ']', $text);

        return $text;
    }
}