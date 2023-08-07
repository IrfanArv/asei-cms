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

    public function getPostData(Request $request)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = session('jwt_token');
        $authController = new AuthWPController();

        if ($token === null) {
            $authController->authenticate();
            $token = session('jwt_token');
        }

        $statuses = ['publish', 'future', 'draft'];

        $totalPosts = 0;

        foreach ($statuses as $status) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($wpApiUrl . '/wp/v2/posts?status=' . $status);

            if ($response->ok()) {
                $totalPosts += $response->header('X-WP-Total');
            }
        }
        $postsPerPage = 10;
        $currentPage = request()->input('page', 1);

        $startIndex = ($currentPage - 1) * $postsPerPage;

        $posts = new Collection();

        foreach ($statuses as $status) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($wpApiUrl . '/wp/v2/posts?lang=id&_embed&per_page=' . $postsPerPage . '&page=' . $currentPage . '&status=' . $status);

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
                'status' => $postItems['status'] ?? null,
                'link' => $postItems['link'],
                'title' => $postItems['title']['rendered'],
                'images' => $postItems['better_featured_image']['source_url'] ?? null,
                'slug' => $postItems['slug'],
                'lang' => $postItems['lang'] ?? null,
                'translations' => $postItems['translations'] ?? null,
                'category_names' => $categoryNames,
                'author' => $author,
            ];

            $postLists[] = $post;
        }
        return DataTables::of($postLists)
            ->addIndexColumn()
            ->editColumn('images', function ($row) {
                $imageUrl = $row['images'] ? $row['images'] : asset('/img/cms/avatars/no-thumbnail-medium.png');
                return '<img src="' . $imageUrl . '" alt="thumbnail" class="img-thumbnail rounded">';
            })
            ->editColumn('title', function ($row) {
                $title = $row['title'];
                $maxChars = 15;

                if (strlen($title) > $maxChars) {
                    $trimmedTitle = substr($title, 0, $maxChars) . '...';
                    $title = '<span title="' . html_entity_decode($title) . '">' . $trimmedTitle . '</span>';
                }

                if ($row['link']) {
                    $title = '<a href="' . $row['link'] . '" target="_blank">' . $title . '</a>';
                }

                return new HtmlString(html_entity_decode($title));
            })

            ->editColumn('category_names', function ($row) {
                if (isset($row['category_names']) && is_array($row['category_names'])) {
                    $categoryNames = implode(', ', $row['category_names']);
                    return new HtmlString($categoryNames);
                } else {
                    return '';
                }
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
                $idLink = route('edit.post', ['id' => $row['id']]);
                $enLink = isset($row['translations']) && isset($row['translations']['en'])
                    ? route('edit.post', ['id' => $row['translations']['en']])
                    : '';

                return '<div class="d-inline-block">
                    <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-warning ti ti-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-icon posts-delete" data-id="' . $row['id'] . '" data-id_en="' . $enLink . '" data-name="' . $row['title'] . '"><i class="text-danger ti ti-trash"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end m-0">
                        <li><a href="' . $idLink . '" class="dropdown-item">Edit ID Data</a></li>
                        ' . ($enLink ? '<li><a href="' . $enLink . '" class="dropdown-item">Edit EN Data</a></li>' : '') . '
                    </ul>
                </div>';
            })
            ->with([
                'recordsTotal' => $totalPosts,
                'recordsFiltered' => $totalPosts,
            ])
            ->rawColumns(['action', 'images', 'category_names', 'status', 'date_gmt', 'modified_gmt', 'author'])
            ->make(true);
    }

    public function getPostById($id)
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
        ])->get($wpApiUrl . '/wp/v2/posts/' . $id . '?_embed');

        if ($response->ok()) {
            $postData = $response->json();

            $categoryNames = [];
            if (isset($postData['_embedded']['wp:term'])) {
                foreach ($postData['_embedded']['wp:term'][0] as $category) {
                    $categoryNames[] = $category['name'];
                }
            }

            $author = [];
            if (isset($postData['_embedded']['author'])) {
                foreach ($postData['_embedded']['author'] as $authorName) {
                    $author[] = $authorName['name'];
                }
            }

            $post = [
                'id' => $postData['id'],
                'date_gmt' => $postData['date_gmt'] ?? null,
                'modified_gmt' => $postData['modified_gmt'] ?? null,
                'status' => $postData['status'] ?? null,
                'title' => $postData['title']['rendered'] ?? null,
                'content' => $postData['content']['rendered'] ?? null,
                'images' => $postData['better_featured_image']['source_url'] ?? null,
                'slug' => $postData['slug'] ?? null,
                'lang' => $postData['lang'] ?? null,
                'translations' => $postData['translations'] ?? null,
                'category_names' => $categoryNames,
                'author' => $author,
            ];

            // return response()->json($postData);
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

            $categories = ($post['lang'] === 'id') ? $categoriesId : $categoriesEn;
            $categoryIds = array_column($post['category_names'], 'id');
            $post['category_ids'] = $categoryIds;


            return view('cms.posts.edit', compact('post', 'categories'));
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
        }
    }


    public function newsCategory()
    {
        return view('cms.posts.categories');
    }

    public function getCategories(Request $request)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = session('jwt_token');
        $authController = new AuthWPController();

        if ($token === null) {
            $authController->authenticate();
            $token = session('jwt_token');
        }
        $totalCategories = 0;
        $responseTotal = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/categories');

        if ($responseTotal->ok()) {
            $totalCategories = $responseTotal->header('X-WP-Total');
        }

        $postsPerPage = 100;
        $currentPage = request()->input('page', 1);
        $startIndex = ($currentPage - 1) * $postsPerPage;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/categories?per_page=' . $postsPerPage . '&page=' . $currentPage);

        $categoryData = $response->json();


        return DataTables::of($categoryData)
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
                $id = isset($row['translations']) ? $row['translations']['id'] : '';
                $en = isset($row['translations']) && isset($row['translations']['en']) ? $row['translations']['en'] : '';

                return '<div class="d-inline-block">

                    <button type="button" class="btn btn-sm btn-icon edit-category" data-id="' . $id . '" data-id_en="' . $en . '" data-name="' . $row['name'] . '" data-type-category="categories"><i class="text-warning ti ti-pencil"></i></button>
                    <button type="button" class="btn btn-sm btn-icon category-delete" data-id="' . $id . '" data-id_en="' . $en . '" data-name="' . $row['name'] . '" data-type-category="categories"><i class="text-danger ti ti-trash"></i></button>

                </div>';
            })
            ->with([
                'recordsTotal' => $totalCategories,
                'recordsFiltered' => $totalCategories,
            ])

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

        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        // ])->get($wpApiUrl . '/wp/v2/categories');

        // return $response->json();

        $perPage = 7; // Number of categories per page
        $currentPage = request()->input('page', 1); // Get the current page from the request query parameters

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/categories?per_page=' . $perPage . '&page=' . $currentPage);

        $categoryData = $response->json();
        return $categoryData;
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
