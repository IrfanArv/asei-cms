<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthWPController;
use Illuminate\Support\Facades\Http;

class StoreCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getAuthToken()
    {
        $token = session('jwt_token');

        if ($token === null) {
            $authController = new AuthWPController();
            $authController->authenticate();
            $token = session('jwt_token');
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

    public function storeCategory(Request $request)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $data = $request->all();

        if ($data['slug_id'] === $data['slug_en']) {
            return response()->json(['success' => false, 'message' => 'Error! Slug (ID) and Slug (EN) cannot be the same.'], 400);
        }

        if (empty($data['name_id'])) {
            return response()->json(['success' => false, 'message' => 'Name (ID) is required'], 400);
        }

        if (empty($data['name_en'])) {
            return response()->json(['success' => false, 'message' => 'Name (EN) is required'], 400);
        }

        $categoryType = $data['category_type'];

        $category_id_data = [
            'name' => $data['name_id'],
            'slug' => $data['slug_id'],
            'description' => $data['description_id'],
        ];

        $response_id = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $categoryType . '?lang=id', $category_id_data);

        if (!$response_id->successful()) {
            return response()->json(['success' => false, 'message' =>  $response_id->json()], 500);
        }

        $category_id = $response_id->json()['id'];

        $category_en_data = [
            'name' => $data['name_en'],
            'slug' => $data['slug_en'],
            'description' => $data['description_en'],
            'translations' => [
                'id' => $category_id,
            ],
        ];

        $response_en = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $categoryType . '/?lang=en', $category_en_data);
        $category_en = $response_en->json()['id'];

        if (!$response_en->successful()) {
            return response()->json(['success' => false, 'message' => 'Failed to create category in lang EN. Please try again.'], 500);
        }

        if ($categoryType === 'insurance-category') {
            $mediaIdDefault = $this->uploadFeaturedMedia($category_id, $request->file('image'), $categoryType);
            $mediaIdTranslation = $this->uploadFeaturedMedia($category_en, $request->file('image'), $categoryType);

            if ($mediaIdDefault['success'] && $mediaIdTranslation['success']) {
                $category_update_data_default = [
                    'acf' => [
                        'banner' => $mediaIdDefault['media_id'],
                    ],
                ];

                $response_update_default = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $categoryType . '/' . $category_id, $category_update_data_default);

                $category_update_data_translation = [
                    'acf' => [
                        'banner' => $mediaIdTranslation['media_id'],
                    ],
                ];

                $response_update_translation = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $categoryType . '/' . $category_en, $category_update_data_translation);

                if ($response_update_default->successful() && $response_update_translation->successful()) {
                    return response()->json(['success' => true, 'message' => 'Category created successfully.'], 201);
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to update category with image. Please try again.'], 500);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Category created successfully.'], 201);
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
                'banner' => $mediaId,
            ]);

            return ['success' => $thumbnailResponse->successful()];
        }

        return ['success' => false];
    }


    public function getCategoriesID($id, $types)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $response = $this->sendAuthenticatedRequest('get', $wpApiUrl . '/wp/v2/' . $types . '/' . $id . '?_embed');

        if ($response->ok()) {
            $categoryData = $response->json();
            return response()->json(['data' => $categoryData]);
        } else {
            return response()->json(['success' => false, 'message' => $response->json()], 500);
        }
    }

    public function putCategory(Request $request)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $token = session('jwt_token');
        $authController = new AuthWPController();

        if ($token === null) {
            $authController->authenticate();
            $token = session('jwt_token');
        }

        $data = $request->all();

        if (!$data['cat_name']) {
            return response()->json(['success' => false, 'message' => 'Name is required'], 400);
        }

        if (!$data['cat_slug']) {
            return response()->json(['success' => false, 'message' => 'Slug is required'], 400);
        }

        $categoryType = $data['category_type'];
        $categoryId = $data['cat_id'];

        $category_data = [
            'name' => $data['cat_name'],
            'slug' => $data['cat_slug'],
            'description' => $data['cat_description'],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->put($wpApiUrl . '/wp/v2/' . $categoryType . '/' . $categoryId, $category_data);


        if (!$response->successful()) {
            return response()->json(['success' => false, 'message' => 'Failed to update. Please try again.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Category updated successfully.'], 201);
    }

    public function deleteCategory($types, $id, $id_en)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $response_id = $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $types . '/' . $id . '?force=true');
        $response_en = $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $types . '/' . $id_en . '?force=true');

        $errorMessages = [];

        if (!$response_id->successful()) {
            $errorMessages[] = 'Failed to delete category with ID: ' . $id;
        }

        if (!$response_en->successful()) {
            $errorMessages[] = 'Failed to delete category with EN ID: ' . $id_en;
        }

        if (empty($errorMessages)) {
            return response()->json(['success' => true, 'message' => 'Categories deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'messages' => $errorMessages], 500);
        }
    }
}
