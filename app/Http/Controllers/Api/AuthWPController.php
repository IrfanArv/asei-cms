<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AuthWPController extends Controller
{
    public function authenticate()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $response = Http::post($wpApiUrl . '/api/v1/token', [
            'username' => env('WORDPRESS_USER_NAME'),
            'password' => env('WORDPRESS_PASSWORD'),
        ]);

        if ($response->ok()) {
            $token = $response->json()['jwt_token'];
            session(['jwt_token' => $token]);
        } else {
            $errorMessage = 'Autentikasi ke server gagal';
            return response()->json(['error' => $errorMessage], 401);
        }
    }
}
