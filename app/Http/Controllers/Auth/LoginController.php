<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
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

    protected function redirectTo()
    {
        return '/dashboard';
    }
}
