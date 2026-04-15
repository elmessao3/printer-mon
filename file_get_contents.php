<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrinterController extends Controller
{

    public function fetchPrinterStatus($ip)
    {
        // Use HTTPS directly (HP printers redirect HTTP → HTTPS)
        $url = "https://$ip/sws/app/information/suppliesStatus.json";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {

            $error = curl_error($ch);
            curl_close($ch);

            return response()->json([
                'status' => 'offline',
                'toner' => null,
                'drum' => null,
                'error' => $error
            ]);
        }

        curl_close($ch);

        // Log printer response for debugging
        Log::info("Printer JSON ($ip): " . $response);

        $data = json_decode($response, true);

        if (!$data) {

            Log::error("JSON decode failed: $ip");

            return response()->json([
                'status' => 'error',
                'toner' => null,
                'drum' => null
            ]);
        }

        $toner = null;
        $drum = null;

        // First possible HP JSON structure
        if (isset($data['supplies'])) {

            foreach ($data['supplies'] as $supply) {

                $name = strtolower($supply['name'] ?? '');

                if (str_contains($name, 'toner')) {
                    $toner = $supply['remaining'] ?? null;
                }

                if (str_contains($name, 'drum') || str_contains($name, 'imaging')) {
                    $drum = $supply['remaining'] ?? null;
                }
            }
        }

        // Second HP JSON structure
        if ($toner === null && isset($data['toner_black']['remaining'])) {
            $toner = $data['toner_black']['remaining'];
        }

        if ($drum === null && isset($data['drum_black']['remaining'])) {
            $drum = $data['drum_black']['remaining'];
        }

        return response()->json([
            'status' => 'online',
            'toner' => $toner,
            'drum' => $drum
        ]);
    }
}