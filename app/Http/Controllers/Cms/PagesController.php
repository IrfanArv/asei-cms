<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Api\RequestApiController;
use App\Http\Controllers\Api\AuthWPController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class PagesController extends Controller
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

    public function update($id)
    {
        $token = $this->getAuthToken();
        $wpApiUrl = env('WORDPRESS_API_URL');

        $title = request()->input('title');
        $content = request()->input('content');

        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $uploadResult = $this->uploadFeaturedMedia($id, $file, 'pages');

            if (!$uploadResult['success']) {
                return redirect()->back()->with('error', 'Failed to upload featured image');
            }
        }

        $data = [
            'title' => $title,
            'content' => $content,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put($wpApiUrl . '/wp/v2/pages/' . $id, $data);

        if ($response->ok()) {
            return redirect()->back()->with('success', 'Meta data page updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update page data');
        }
    }


    public function index()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();
        // *PAGES
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/pages?lang=id&_embed&per_page=100&page=1');


        if ($response->ok()) {
            $pageWP = $response->json();
            $dataPages = [];

            foreach ($pageWP as $pages) {
                $page = [
                    'id' => isset($pages['id']) ? $pages['id'] : null,
                    'date_gmt' => $pages['date_gmt'],
                    'modified_gmt' => $pages['modified_gmt'],
                    'slug' => $pages['slug'],
                    'link' => $pages['link'],
                    'title' => isset($pages['title']['rendered']) ? $pages['title']['rendered'] : null,
                    'images' => isset($pages['better_featured_image']) ? $pages['better_featured_image']['source_url'] : null,
                    'lang' => $pages['lang'],
                    'english' => isset($pages['translations']['en']) ? $pages['translations']['en'] : null,
                ];

                $dataPages[] = $page;
            }
            // return response()->json(['data' => $dataPages]);
            usort($dataPages, function ($a, $b) {
                return strcmp($a['title'], $b['title']);
            });
            return view('cms.pages.index', compact('dataPages'));
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
        }
    }



    public function getPagesById($id)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();

        try {
            // Fetch page data
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                ->timeout(10)
                ->get($wpApiUrl . '/wp/v2/pages/' . $id);

            // Process page data
            if ($response->ok()) {
                $pagesData = $response->json();


                $sectionOneId = null;
                $sectionFourTitleId = null;
                $langData = 'id';
                if ($id === '109') {
                    $sectionOneId = 457;
                    $langData = 'id';
                    $sectionFourTitleId = 461;
                } elseif ($id === '112') {
                    $sectionOneId = 459;
                    $langData = 'en';
                    $sectionFourTitleId = 462;
                } elseif ($id === 109) {
                    $sectionOneId = 459;
                    $langData = 'id';
                    $sectionFourTitleId = 461;
                } elseif ($id === 112) {
                    $sectionOneId = 459;
                    $langData = 'en';
                    $sectionFourTitleId = 462;
                }

                // Fetch sliders data
                $homeSliders = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->timeout(10)
                    ->get($wpApiUrl . '/wp/v2/home-sliders?lang=' . $langData . '&_embed');
                $sliderData = $homeSliders->json();

                $sectionOne = null;
                $sectionFourData = null;
                $sectionFourTitle = null;
                if ($sectionOneId !== null) {
                    $sectionOneUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionOneId;
                    $responseSectionOne = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionOneUrl);

                    if ($responseSectionOne->ok()) {
                        $sectionOne = $responseSectionOne->json();
                    }

                    // * Section 4
                    $sectionFourUrl = $wpApiUrl . '/wp/v2/page-content?lang=' . $langData . '&group-pages=191';
                    $responseSectionFour = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionFourUrl);


                    if ($responseSectionFour->ok()) {
                        $sectionFourData = $responseSectionFour->json();
                    }
                    // return response()->json(['data' => $sectionFourData]);

                    // * Section 4 Right
                    $sectionFourTitlesUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFourTitleId;
                    $responseSectionFourTitle = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionFourTitlesUrl);

                    if ($responseSectionFourTitle->ok()) {
                        $sectionFourTitle = $responseSectionFourTitle->json();
                    }
                    // return response()->json(['data' => $sectionFourTitle]);
                }

                $imageUrl = isset($pagesData['better_featured_image']['source_url']) ? $pagesData['better_featured_image']['source_url'] : null;

                return view('cms.pages.detail', compact('pagesData', 'imageUrl', 'sliderData', 'sectionOne', 'sectionFourData', 'sectionFourTitle'));
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', 'Koneksi ke server gagal: ' . $e->getMessage());
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Permintaan ke server gagal: ' . $e->getMessage());
        }
    }
}
