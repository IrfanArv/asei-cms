<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthWPController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class SocialResponses extends Controller
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

    public function index()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/social-responses?lang=id&_embed&per_page=100&page=1');


        if ($response->ok()) {
            $socialData = $response->json();
            $listData = [];

            foreach ($socialData as $pages) {
                $page = [
                    'id' => isset($pages['id']) ? $pages['id'] : null,
                    'date_gmt' => $pages['date_gmt'],
                    'modified_gmt' => $pages['modified_gmt'],
                    'slug' => $pages['slug'],
                    'link' => $pages['link'],
                    'status' => $pages['status'],
                    'title' => isset($pages['title']['rendered']) ? $pages['title']['rendered'] : null,
                    'images' => isset($pages['better_featured_image']) ? $pages['better_featured_image']['source_url'] : null,
                    'lang' => $pages['lang'],
                    'english' => isset($pages['translations']['en']) ? $pages['translations']['en'] : null,
                ];

                $listData[] = $page;
            }
            // return response()->json(['data' => $listData]);
            usort($listData, function ($a, $b) {
                return strcmp($a['title'], $b['title']);
            });
            return view('cms.socials.index', compact('listData'));
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
        }
    }
}
