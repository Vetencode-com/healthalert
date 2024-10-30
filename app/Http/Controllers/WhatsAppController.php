<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function index()
    {
        $url = 'https://api.blitzmes.com/api/connection';
        $body = [
            'api_key' => config('blitzmes.api_key'),
        ];
        $response = Http::post($url, $body);

        $device_status = 'STOPPED';
        if ($response->successful()) {
            $resp = $response->json();
            $device_status = $resp['isConnect'] ? 'CONNECTED' : 'STOPPED';
        }

        $data['device_status'] = $device_status;
        return view('whatsapp', $data);
    }
}
