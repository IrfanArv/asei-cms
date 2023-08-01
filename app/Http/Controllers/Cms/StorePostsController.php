<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthWPController;
use Illuminate\Support\Facades\Http;

class StorePostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function store(Request $request)
    // {
    //     $wpApiUrl = env('WORDPRESS_API_URL');
    //     $data = $request->all();

    //     $request->validate([
    //         'title_post_id' => 'required|string|max:255',
    //         'title_post_en' => 'required|string|max:255',
    //         'slug_post_id' => 'required|string|max:255',
    //         'slug_post_en' => 'required|string|max:255',
    //         'subtitle_id' => 'nullable|string',
    //         'subtitle_en' => 'nullable|string',
    //         'content_id' => 'required|string',
    //         'content_en' => 'required|string',
    //         'post_status' => 'in:publish,draft,schedule',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'post_category' => 'required',
    //         'post_category_en' => 'required',
    //         'type_post' => 'required'
    //     ]);
    //     $post_type = $data['type_post'];
    //     $categoriesId = $data['post_category'];
    //     $categoriesEn = $data['post_category_en'];

    //     if ($post_type === 'posts') {
    //         $postDataId = [
    //             'title' => $data['title_post_id'],
    //             'slug' => $data['slug_post_id'],
    //             'content' => $data['content_id'],
    //             'status' => $data['post_status'],
    //             'categories' => $categoriesId,
    //         ];
    //         if ($data['post_status'] === 'schedule') {
    //             $postDataId['status'] = 'future';
    //             $postDataId['date'] = $data['publish_datetime'];
    //         } else {
    //             $postDataId['status'] = $data['post_status'];
    //         }
    //     } else {
    //         $postDataId = [
    //             'title' => $data['title_post_id'],
    //             'slug' => $data['slug_post_id'],
    //             'content' => $data['content_id'],
    //             'acf' => [
    //                 'sub_title' => $data['subtitle_id'],
    //                 'deskripsi' => $data['content_id'],
    //             ],
    //             'status' => $data['post_status'],
    //         ];
    //     }


    //     $responseId = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '?lang=id', $postDataId);
    //     $postIdId = $responseId->json()['id'];
    //     if ($post_type === 'posts') {
    //         $postDataEn = [
    //             'title' => $data['title_post_en'],
    //             'slug' => $data['slug_post_en'],
    //             'content' => $data['content_en'],
    //             'status' => $data['post_status'],
    //             'categories' => $categoriesEn,
    //         ];

    //         if ($data['post_status'] === 'schedule') {
    //             $postDataEn['status'] = 'future';
    //             $postDataEn['date'] = $data['publish_datetime'];
    //         } else {
    //             $postDataEn['status'] = $data['post_status'];
    //         }
    //     } else {
    //         $postDataEn = [
    //             'title' => $data['title_post_en'],
    //             'slug' => $data['slug_post_en'],
    //             'content' => $data['content_en'],
    //             'acf' => [
    //                 'sub_title' => $data['subtitle_en'],
    //                 'deskripsi' => $data['content_en'],
    //             ],
    //             'status' => $data['post_status'],
    //             'translations' => [
    //                 'id' => $postIdId,
    //             ],
    //         ];
    //     }

    //     $responseEn = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '?lang=en', $postDataEn);

    //     if (!$responseId->successful() || !$responseEn->successful()) {
    //         if ($post_type === 'posts') {
    //             return redirect()->route('posts.index')->with('error', 'Failed to create post. Please try again.');
    //         } else {
    //             return redirect()->route('insurance.index')->with('error', 'Failed to create insurance products. Please try again.');
    //         }
    //     }

    //     $postIdEn = $responseEn->json()['id'];

    //     $mediaResponseId = $this->uploadFeaturedMedia($postIdId, $request->file('image'), $post_type);
    //     $mediaResponseEn = $this->uploadFeaturedMedia($postIdEn, $request->file('image'), $post_type);

    //     if ($mediaResponseId['success'] && $mediaResponseEn['success']) {
    //         $categoryIdId = $request->input('post_category');
    //         $categoryIdEn = $request->input('post_category_en');


    //         if ($post_type === 'posts') {
    //             $responseId = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/posts/' . $postIdId . '/?lang=id', [
    //                 'categories' => $categoriesId,

    //             ]);

    //             $responseEn = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/posts/' . $postIdEn . '/?lang=en', [
    //                 'categories' => $categoriesEn,

    //             ]);
    //         } else {
    //             $responseId = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/insurance-products/' . $postIdId . '/?lang=id', [
    //                 'insurance-category' => [$categoryIdId],
    //             ]);

    //             $responseEn = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/insurance-products/' . $postIdEn . '/?lang=en', [
    //                 'insurance-category' => [$categoryIdEn],
    //             ]);
    //         }

    //         if ($responseId->successful() && $responseEn->successful()) {
    //             if ($post_type === 'posts') {
    //                 return redirect()->route('posts.index')->with('success', 'News created successfully');
    //             } else {
    //                 return redirect()->route('insurance.index')->with('success', 'Insurance Product created successfully');
    //             }
    //         }
    //     }

    //     $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postIdId);
    //     $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postIdEn);

    //     if ($post_type === 'posts') {
    //         return redirect()->route('posts.index')->with('error', 'Failed to associate posts with categories. Please try again.');
    //     } else {
    //         return redirect()->route('insurance.index')->with('error', 'Failed to associate insurance product with categories. Please try again.');
    //     }
    // }

    public function store(Request $request)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $data = $request->all();

        $request->validate([
            'title_post_id' => 'required|string|max:255',
            'title_post_en' => 'required|string|max:255',
            'slug_post_id' => 'required|string|max:255',
            'slug_post_en' => 'required|string|max:255',
            'subtitle_id' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'content_id' => 'required|string',
            'content_en' => 'required|string',
            'post_status' => 'in:publish,draft,schedule',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'post_category' => 'required|array', // Use 'array' validation to handle multiple categories
            'post_category_en' => 'required|array', // Use 'array' validation to handle multiple categories
            'type_post' => 'required',
        ]);

        $post_type = $data['type_post'];
        $categoriesId = $data['post_category'];
        $categoriesEn = $data['post_category_en'];

        // Save the post data for ID language
        $postDataId = [
            'title' => $data['title_post_id'],
            'slug' => $data['slug_post_id'],
            'content' => $data['content_id'],
            'status' => $data['post_status'],
            'categories' => $categoriesId,
        ];

        if ($data['post_status'] === 'schedule') {
            $postDataId['status'] = 'future';
            $postDataId['date'] = $data['publish_datetime'];
        }

        $responseId = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '?lang=id', $postDataId);
        $postIdId = $responseId->json()['id'];

        // Save the post data for EN language using Polylang
        $postDataEn = [
            'title' => $data['title_post_en'],
            'slug' => $data['slug_post_en'],
            'content' => $data['content_en'],
            'status' => $data['post_status'],
            'categories' => $categoriesEn,
            'translations' => [
                'id' => $postIdId, // Assign the translation to the corresponding ID post
            ],
        ];

        if ($data['post_status'] === 'schedule') {
            $postDataEn['status'] = 'future';
            $postDataEn['date'] = $data['publish_datetime'];
        }

        $responseEn = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '?lang=en', $postDataEn);
        $postIdEn = $responseEn->json()['id'];

        // Upload featured media for both ID and EN posts
        $mediaResponseId = $this->uploadFeaturedMedia($postIdId, $request->file('image'), $post_type);
        $mediaResponseEn = $this->uploadFeaturedMedia($postIdEn, $request->file('image'), $post_type);

        if ($mediaResponseId['success'] && $mediaResponseEn['success']) {
            // Associate categories with ID and EN posts
            $responseId = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postIdId . '/?lang=id', [
                'categories' => $categoriesId,
            ]);

            $responseEn = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postIdEn . '/?lang=en', [
                'categories' => $categoriesEn,
            ]);

            if ($responseId->successful() && $responseEn->successful()) {
                // Redirect with success message upon successful creation
                if ($post_type === 'posts') {
                    return redirect()->route('posts.index')->with('success', 'News created successfully');
                } else {
                    return redirect()->route('insurance.index')->with('success', 'Insurance Product created successfully');
                }
            }
        }

        // If any step fails, delete the created posts and show an error message
        $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postIdId);
        $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $post_type . '/' . $postIdEn);

        if ($post_type === 'posts') {
            return redirect()->route('posts.index')->with('error', 'Failed to create post. Please try again.');
        } else {
            return redirect()->route('insurance.index')->with('error', 'Failed to create insurance products. Please try again.');
        }
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
}
