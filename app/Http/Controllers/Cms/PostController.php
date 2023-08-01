<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthWPController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cms.posts.index');
    }

    public function create()
    {
        $categoryData = $this->getCategoryData();

        $categoriesId = [];
        $categoriesEn = [];

        foreach ($categoryData as $categoryItem) {
            if ($categoryItem['slug'] === 'uncategorized') {
                continue;
            }
            $category = [
                'id' => $categoryItem['id'],
                'count' => $categoryItem['count'],
                'link' => $categoryItem['link'],
                'name' => $categoryItem['name'],
                'slug' => $categoryItem['slug'],
                'description' => $categoryItem['description'],
                'lang' => $categoryItem['lang'],
                'translations' => $categoryItem['translations'],
            ];

            if ($categoryItem['lang'] === 'id') {
                $categoriesId[] = $category;
            } elseif ($categoryItem['lang'] === 'en') {
                $categoriesEn[] = $category;
            }
        }

        return view('cms.posts.create', compact('categoriesId', 'categoriesEn'));
    }

    public function getPostData()
    {
        $postLists = $this->fetchPostData();
        return DataTables::of($postLists)
            ->addIndexColumn()
            ->editColumn('images', function ($row) {
                $imageUrl = $row['images'] ? $row['images'] : asset('/img/cms/avatars/no-thumbnail-medium.png');
                return '<img src="' . $imageUrl . '" alt="thumbnail" class="img-thumbnail rounded">';
            })
            ->editColumn('title', function ($row) {
                $title = $row['title'];
                if ($row['link']) {
                    $title = '<a href="' . $row['link'] . '" target="_blank">' . $title . '</a>';
                }
                return new HtmlString(html_entity_decode($title));
            })
            ->editColumn('category_names', function ($row) {
                $categoryNames = is_array($row['category_names']) ? implode(', ', $row['category_names']) : '';
                return new HtmlString($categoryNames);
            })
            ->editColumn('author', function ($row) {
                $authorName = is_array($row['author']) ? implode(', ', $row['author']) : '';
                return new HtmlString($authorName);
            })
            ->editColumn('status', function ($row) {
                $statusClass = $row['status'] === 'publish' ? 'success' : 'warning';
                return '<button type="button" class="btn btn-sm rounded-pill btn-label-' . $statusClass . ' waves-effect waves-light">' . $row['status'] . '</button>';
            })
            ->editColumn('date_gmt', function ($row) {
                return Carbon::parse($row['date_gmt'])->format('d M Y H:i');
            })
            ->editColumn('modified_gmt', function ($row) {
                return Carbon::parse($row['modified_gmt'])->format('d M Y H:i');
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="text-warning ti ti-pencil"></i></a>
                        <a href="javascript:;" class="btn btn-sm btn-icon item-edit"><i class="text-danger ti ti-trash"></i></a>
                        </div>';
            })
            ->rawColumns(['action', 'images', 'category_names', 'status', 'date_gmt', 'modified_gmt', 'author'])
            ->make(true);
    }

    public function newsCategory()
    {
        return view('cms.posts.categories');
    }

    public function getCategories()
    {
        $categories = $this->fetchCategories();
        return DataTables::of($categories)
            ->addIndexColumn()
            ->editColumn('count', function ($row) {
                return $row['count'] . ' Posts';
            })
            ->editColumn('name', function ($row) {
                $name = $row['name'];
                if ($row['link']) {
                    $name = '<a href="' . $row['link'] . '" target="_blank">' . $name . '</a>';
                }
                return new HtmlString(html_entity_decode($name));
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-inline-block">
                <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-warning ti ti-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-icon category-delete" data-id="' . $row['translations']['id'] . '" data-id_en="' . $row['translations']['en'] . '" data-name="' . $row['name'] . '" data-type-category="categories"><i class="text-danger ti ti-trash"></i></button>
                <ul class="dropdown-menu dropdown-menu-end m-0">
                <li><div class="dropdown-item text-primary">Select Language to edit</div></li>
                <div class="dropdown-divider"></div>
                  <li><button type="button" class="dropdown-item edit-category" data-id="' . $row['translations']['id'] . '" data-name="' . $row['name'] . '" data-type-category="categories">ID</button></li>
                  <li><button type="button" class="dropdown-item edit-category" data-id="' . $row['translations']['en'] . '" data-name="' . $row['name'] . '" data-type-category="categories">EN</button></li>
                </ul>
              </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getCategoryData()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = session('jwt_token');
        $authController = new AuthWPController();

        if ($token === null) {
            $authController->authenticate();
            $token = session('jwt_token');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/categories');

        return $response->json();
    }

    // private function fetchPostData()
    // {
    //     $wpApiUrl = env('WORDPRESS_API_URL');
    //     $token = session('jwt_token');
    //     $authController = new AuthWPController();

    //     if ($token === null) {
    //         $authController->authenticate();
    //         $token = session('jwt_token');
    //     }

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->get($wpApiUrl . '/wp/v2/posts?lang=id&_embed');

    //     if ($response->ok()) {
    //         $postsData = $response->json();
    //         $postLists = [];

    //         foreach ($postsData as $postItems) {
    //             $categoryNames = [];
    //             if (isset($postItems['_embedded']['wp:term'])) {
    //                 foreach ($postItems['_embedded']['wp:term'][0] as $category) {
    //                     $categoryNames[] = $category['name'];
    //                 }
    //             }
    //             $author = [];
    //             if (isset($postItems['_embedded']['author'])) {
    //                 foreach ($postItems['_embedded']['author'] as $authorName) {
    //                     $author[] = $authorName['name'];
    //                 }
    //             }
    //             $posts = [
    //                 'id' => $postItems['id'],
    //                 'date_gmt' => $postItems['date_gmt'],
    //                 'modified_gmt' => $postItems['modified_gmt'],
    //                 'status' => $postItems['status'],
    //                 'link' => $postItems['link'],
    //                 'title' => $postItems['title']['rendered'],
    //                 'images' => $postItems['better_featured_image']['source_url'],
    //                 'slug' => $postItems['slug'],
    //                 'lang' => $postItems['lang'],
    //                 'translations' => $postItems['translations'],
    //                 'category_names' => $categoryNames,
    //                 'author' => $author,
    //             ];

    //             $postLists[] = $posts;
    //         }

    //         return $postLists;
    //     } else {
    //         $authController->authenticate();
    //         $token = session('jwt_token');
    //     }
    // }

    private function fetchPostData()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = session('jwt_token');
        $authController = new AuthWPController();

        if ($token === null) {
            $authController->authenticate();
            $token = session('jwt_token');
        }

        $statuses = ['publish', 'future', 'draft']; // Add other statuses as needed
        $posts = new Collection();

        foreach ($statuses as $status) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($wpApiUrl . '/wp/v2/posts?lang=id&_embed&status=' . $status);

            if ($response->ok()) {
                $statusPosts = $response->json();
                $posts = $posts->merge($statusPosts);
            }
        }

        $postLists = [];

        foreach ($posts as $postItems) {
            $categoryNames = [];
            if (isset($postItems['_embedded']['wp:term'])) {
                foreach ($postItems['_embedded']['wp:term'][0] as $category) {
                    $categoryNames[] = $category['name'];
                }
            }
            $author = [];
            if (isset($postItems['_embedded']['author'])) {
                foreach ($postItems['_embedded']['author'] as $authorName) {
                    $author[] = $authorName['name'];
                }
            }
            $post = [
                'id' => $postItems['id'],
                'date_gmt' => $postItems['date_gmt'],
                'modified_gmt' => $postItems['modified_gmt'],
                'status' => $postItems['status'],
                'link' => $postItems['link'],
                'title' => $postItems['title']['rendered'],
                'images' => $postItems['better_featured_image']['source_url'],
                'slug' => $postItems['slug'],
                'lang' => $postItems['lang'],
                'translations' => $postItems['translations'],
                'category_names' => $categoryNames,
                'author' => $author,
            ];

            $postLists[] = $post;
        }

        return $postLists;
    }

    private function fetchCategories()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = session('jwt_token');
        $authController = new AuthWPController();

        if ($token === null) {
            $authController->authenticate();
            $token = session('jwt_token');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/categories?lang=id&_embed');

        if ($response->ok()) {
            $categoryData = $response->json();
            $categories = [];

            foreach ($categoryData as $categoryItem) {
                if ($categoryItem['slug'] === 'uncategorized') {
                    continue;
                }
                $category = [
                    'id' => $categoryItem['id'],
                    'count' => $categoryItem['count'],
                    'link' => $categoryItem['link'],
                    'name' => $categoryItem['name'],
                    'slug' => $categoryItem['slug'],
                    'description' => $categoryItem['description'],
                    'lang' => $categoryItem['lang'],
                    'translations' => $categoryItem['translations'],
                ];

                $categories[] = $category;
            }

            return $categories;
        } else {
            $authController->authenticate();
            $token = session('jwt_token');
        }
    }
}
