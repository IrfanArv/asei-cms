<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RequestApiController extends Controller
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

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->{$method}($url, $data);

        return $response;
    }

    public function getCategoriesID($id, $types)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $response = $this->sendAuthenticatedRequest('get', $wpApiUrl . '/wp/v2/' . $types . '/' . $id . '?_embed');

        if ($response->ok()) {
            $categoryData = $response->json();
            return $categoryData;
        } else {
            return redirect()->back()->with('error', $response->json());
        }
    }

    public function getInsuranceCategories()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/insurance-category?lang=id&_embed');

        if ($response->ok()) {
            $categoryData = $response->json();
            $categories = [];

            foreach ($categoryData as $categoryItem) {
                $mediaId = $categoryItem['acf']['banner'];
                $mediaResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get($wpApiUrl . '/wp/v2/media/' . $mediaId);

                if ($mediaResponse->ok()) {
                    $mediaData = $mediaResponse->json();
                    $imageUrl = isset($mediaData['source_url']) ? $mediaData['source_url'] : '';

                    $category = [
                        'id' => $categoryItem['id'],
                        'count' => $categoryItem['count'],
                        'link' => $categoryItem['link'],
                        'name' => $categoryItem['name'],
                        'slug' => $categoryItem['slug'],
                        'description' => $categoryItem['description'],
                        'image_url' => $imageUrl,
                        'lang' => $categoryItem['lang'],
                        'translations' => $categoryItem['translations'],
                    ];

                    $categories[] = $category;
                }
            }

            return $categories;
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
        }
    }

    public function getInsuranceProducts($id)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/insurance-products?insurance-category=' . $id . '&_embed');

        if ($response->ok()) {
            $productData = $response->json();
            $products = [];

            foreach ($productData as $productItem) {
                $categoryNames = [];
                if (isset($productItem['_embedded']['wp:term'])) {
                    foreach ($productItem['_embedded']['wp:term'][0] as $category) {
                        $categoryNames[] = $category['name'];
                    }
                }
                $product = [
                    'id' => $productItem['id'],
                    'date_gmt' => $productItem['date_gmt'],
                    'modified_gmt' => $productItem['modified_gmt'],
                    'status' => $productItem['status'],
                    'link' => $productItem['link'],
                    'title' => $productItem['title']['rendered'],
                    'sub_title' => $productItem['acf']['sub_title'],
                    'desc' => $productItem['acf']['deskripsi'],
                    'images' => $productItem['better_featured_image']['source_url'],
                    'slug' => $productItem['slug'],
                    'lang' => $productItem['lang'],
                    'translations' => $productItem['translations'],
                    'category_names' => $categoryNames,
                ];
                $products[] = $product;
            }

            return $products;
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
        }
    }

    public function getSliderInsurance($id)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/slide-insurace?insurance-category=' . $id);
        if ($response->ok()) {
            $sliderData = $response->json();
            $sliderList = [];
            foreach ($sliderData as $sliderItems) {
                $slide = [
                    'id_slide' => $sliderItems['id'],
                    'title_slide' => $sliderItems['title']['rendered'],
                    'status_slide' => $sliderItems['status'],
                    'description_slide' => $sliderItems['acf']['description'],
                    'images_slide' => $sliderItems['better_featured_image']['source_url'],
                    'lang_slide' => $sliderItems['lang'],
                    'translations_slide' => $sliderItems['translations'],
                ];

                $sliderList[] = $slide;
            }
            return $sliderList;
        } else {
            return redirect()->back()->with(' error ', ' Terjadi kesalahan teknis');
        }
    }

    public function getGreetingInsurance($id)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/greeting-insurance?insurance-category=' . $id);
        if ($response->ok()) {
            $greetingData = $response->json();
            $greetingList = [];
            foreach ($greetingData as $greetingItems) {
                $greeting = [
                    'id_greeting' => $greetingItems['id'],
                    'title_greeting' => $greetingItems['title']['rendered'],
                    'status_greeting' => $greetingItems['status'],
                    'description_greeting' => $greetingItems['content']['rendered'],
                    'images_greeting' => $greetingItems['better_featured_image']['source_url'],
                    'lang_greeting' => $greetingItems['lang'],
                    'translations_greeting' => $greetingItems['translations'],
                ];

                $greetingList[] = $greeting;
            }
            return $greetingList;
        } else {
            return redirect()->back()->with(' error ', ' Terjadi kesalahan teknis');
        }
    }

    // * SETTINGS
    public function getWebSettings()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $response = $this->sendAuthenticatedRequest('get', $wpApiUrl . '/wp/v2/web-settings?_embed');

        if ($response->ok()) {
            $webSettings = $response->json();
            $settingList = [];
            foreach ($webSettings as $webSetting) {
                $excludeNameField = isset($webSetting['web-setting-group']) && in_array(179, $webSetting['web-setting-group']);

                $greeting = [
                    'id' => isset($webSetting['id']) ? $webSetting['id'] : null,
                    'title' => isset($webSetting['title']['rendered']) ? $webSetting['title']['rendered'] : null,
                    'images' => isset($webSetting['better_featured_image']['source_url']) ? $webSetting['better_featured_image']['source_url'] : null,
                    'setting_value' => $excludeNameField ? null : (isset($webSetting['acf']['name']) ? $webSetting['acf']['name'] : null),
                    'link_value' => isset($webSetting['acf']['external_link']['url']) ? $webSetting['acf']['external_link']['url'] : null,
                ];

                $settingList[] = $greeting;
            }

            return $settingList;
            // return [$settingList[0]];
        } else {
            return redirect()->back()->with('error', $response->json());
        }
    }

    // * UPDATE SETTINGS
    public function updateSettings(Request $request)
    {
        $token = $this->getAuthToken();
        $settings = $request->input('settings');

        foreach ($settings as $setting) {
            $id = $setting['id'];
            $settingValue = $setting['setting_value'] ?? '';
            $linkValue = $setting['link_value'] ?? '';

            $updatedData = [
                'acf' => [
                    'name' => $settingValue,
                    'external_link' => [
                        'title' => '',
                        'url' => $linkValue,
                        'target' => '_blank'
                    ]
                ]
            ];

            $wpApiUrl = env('WORDPRESS_API_URL');
            $response = Http::withHeaders(['Authorization' => 'Bearer' . $token])
                ->put("$wpApiUrl/wp/v2/web-settings/$id", $updatedData);

            if (!$response->successful()) {
                // return redirect()->back()->with('error', 'Failed to update data.');
                return redirect()->back()->with('success', 'Data updated successfully.');
                // dd($response->json());
            }
        }

        return redirect()->back()->with('success', 'Data updated successfully.');
    }
}
