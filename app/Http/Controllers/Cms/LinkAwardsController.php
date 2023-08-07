<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RequestApiController;
use App\Http\Controllers\Api\AuthWPController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;


class LinkAwardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getAuthToken()
    {
        $token = Session::get('jwt_token');

        if ($token === null) {
            $authController = new AuthWPController();
            $authController->authenticate();
            $token = Session::get('jwt_token');
        }
        return $token;
    }

    private function sendAuthenticatedRequest($method, $url, $data = [])
    {
        $token = $this->getAuthToken();

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->{$method}($url, $data);
    }


    private function uploadFeaturedMedia($postId, $file, $post_type)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();

        $uploadResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post($wpApiUrl . '/wp/v2/media');

        if ($uploadResponse->successful()) {
            $mediaId = $uploadResponse->json()['id'];
            $thumbnailResponse = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postId, [
                'featured_media' => $mediaId,
            ]);

            return ['success' => $thumbnailResponse->successful()];
        }

        return ['success' => false];
    }

    public function getPiagam()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->timeout(10)->get($wpApiUrl . '/wp/v2/link-awards?category-link-awards=239&per_page=100&page=1');


            if ($response->ok()) {
                $piagams = $response->json();
                $dataPiagam = [];

                foreach ($piagams as $piagam) {
                    $page = [
                        'id' => isset($piagam['id']) ? $piagam['id'] : null,
                        'image_url' => isset($piagam['better_featured_image']) ? $piagam['better_featured_image']['source_url'] : null,
                    ];

                    $dataPiagam[] = $page;
                }
                // return response()->json(['data' => $dataPiagam]);
                return view('cms.awards.piagam', compact('dataPiagam'));
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        }
    }

    public function getAwards()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->timeout(10)->get($wpApiUrl . '/wp/v2/link-awards?category-link-awards=238&per_page=100&page=1');


            if ($response->ok()) {
                $awards = $response->json();
                $dataAward = [];

                foreach ($awards as $item) {
                    $data = [
                        'id' => isset($item['id']) ? $item['id'] : null,
                        'title' => $item['title']['rendered'],
                        'rewards' => $item['acf']['rewards'],
                        'modified_gmt' => $item['modified_gmt'],
                    ];

                    $dataAward[] = $data;
                }
                // return response()->json(['data' => $awards]);
                return view('cms.awards.award', compact('dataAward'));
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        }
    }

    public function getLink()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->timeout(10)->get($wpApiUrl . '/wp/v2/link-awards?category-link-awards=237&per_page=100&page=1');


            if ($response->ok()) {
                $links = $response->json();
                $dataLink = [];

                foreach ($links as $item) {
                    $data = [
                        'id' => isset($item['id']) ? $item['id'] : null,
                        'link' => $item['acf']['link'],
                        'image_url' => isset($item['better_featured_image']) ? $item['better_featured_image']['source_url'] : null,
                        'modified_gmt' => $item['modified_gmt'],
                    ];

                    $dataLink[] = $data;
                }
                // return response()->json(['data' => $dataLink]);
                return view('cms.awards.link', compact('dataLink'));
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        }
    }
}
