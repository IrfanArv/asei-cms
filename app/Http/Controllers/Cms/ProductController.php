<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Api\RequestApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // * VIEW OF INSURANCE INDEX
    public function index()
    {
        $requestWPController = new RequestApiController();
        $categories = $requestWPController->getInsuranceCategories();
        return view('cms.products.index', compact('categories'));
    }

    // * VIEW OF PRODUCTS INSURANCE
    public function product($id)
    {
        $requestWPController = new RequestApiController();
        $products = $requestWPController->getInsuranceProducts($id);
        if (empty($products)) {
            return redirect()->route('insurance.index')->with('error', 'ID tidak ditemukan');
        }

        $catName = $products[0]['category_names'][0];
        return view('cms.products.product', compact('products', 'catName'));
    }

    // * VIEW OF PAGE CONTENTS INSURANCE
    public function insuracePage($id)
    {
        $types = 'insurance-category';
        $requestWPController = new RequestApiController();

        // ** FETCH CATEGORY DATA
        $categoryData = $requestWPController->getCategoriesID($id, $types);

        if (isset($categoryData['translations']['en'])) {
            $translatedId = $categoryData['translations']['en'];
            $translatedCategoryData = $requestWPController->getCategoriesID($translatedId, $types);
        } else {
            $translatedCategoryData = null;
        }

        // ** FETCH CATEGORY BANNER
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = Session::get('jwt_token');

        $mediaId = $categoryData['acf']['banner'];
        $mediaResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get($wpApiUrl . '/wp/v2/media/' . $mediaId);
        $mediaData = $mediaResponse->json();
        $imageUrl = isset($mediaData['source_url']) ? $mediaData['source_url'] : null;

        // ** FETCH SLIDERS
        $requestWPController = new RequestApiController();
        $sliderData = $requestWPController->getSliderInsurance($id);

        // ** FETCH GREETING DATA
        $requestWPController = new RequestApiController();
        $greetingData = collect($requestWPController->getGreetingInsurance($id))->first();

        // return response()->json(['data' => $greetingData]);

        return view('cms.products.content', compact('categoryData', 'translatedCategoryData', 'imageUrl', 'sliderData', 'greetingData'));
    }

    // * VIEW OF CREATE INSURANCES PRODUCTS
    public function create()
    {
        $categoriesId = [];
        $categoriesEn = [];

        $requestWPController = new RequestApiController();
        $categoryData = $requestWPController->getInsuranceCategories();
        foreach ($categoryData as $categoryItem) {
            if ($categoryItem['lang'] === 'id') {
                $categoriesId[] = $categoryItem;
            } elseif ($categoryItem['lang'] === 'en') {
                $categoriesEn[] = $categoryItem;
            }
        }

        return view('cms.products.create', compact('categoriesId', 'categoriesEn'));
    }

    // public function getSampleData()
    // {
    //     $requestWPController = new RequestApiController();
    //     $sliderData = $requestWPController->getSliderInsurance();
    //     return response()->json(['data' => $sliderData]);
    // }

    // public function categoriesInsurance()
    // {
    //     return view('cms.products.categories');
    // }

    // GET INSURANCE CATEGORIES FOR JSON RESPONSE
    // public function getCategoryInsurance()
    // {
    //     $categories = $this->getInsuranceCategories();

    //     return DataTables::of($categories)
    //         ->addIndexColumn()
    //         ->editColumn('image_url', function ($row) {
    //             if (empty($row['image_url'])) {
    //                 return '<img src="' . asset('/img/cms/avatars/no-thumbnail-medium.png') . '" alt="thumbnail" class="img-thumbnail rounded">';
    //             } else {
    //                 return '<img src="' . $row['image_url'] . '" alt="thumbnail" class="img-thumbnail rounded">';
    //             }
    //         })
    //         ->editColumn('count', function ($row) {
    //             return $row['count'] . ' Products';
    //         })
    //         ->editColumn('name', function ($row) {
    //             $name = $row['name'];
    //             if ($row['link']) {
    //                 $name = '<a href="' . $row['link'] . '" target="_blank">' . $name . '</a>';
    //             }
    //             return new HtmlString(html_entity_decode($name));
    //         })
    //         ->editColumn('description', function ($row) {
    //             $description = $row['description'];
    //             $limitedDescription = Str::limit($description, 25);
    //             return $limitedDescription;
    //         })
    //         ->addColumn('action', function ($row) {
    //             return '<div class="d-inline-block">
    //                         <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="text-warning ti ti-pencil"></i></button>
    //                         <button type="button" class="btn btn-sm btn-icon category-delete" data-id="' .  $row['translations']['id'] . '" data-id_en="' .  $row['translations']['en'] . '" data-name="' .  $row['name'] . '" data-type-category="insurance-category"><i class="text-danger ti ti-trash"></i></button>
    //                         <ul class="dropdown-menu dropdown-menu-end m-0">
    //                             <li><div class="dropdown-item text-primary">Select Language to edit</div></li>
    //                             <div class="dropdown-divider"></div>
    //                             <li><button type="button" class="dropdown-item edit-category" data-id="' .  $row['translations']['id'] . '" data-name="' .  $row['name'] . '" data-type-category="insurance-category">ID</button></li>
    //                             <li><button type="button" class="dropdown-item edit-category" data-id="' .  $row['translations']['en'] . '" data-name="' .  $row['name'] . '" data-type-category="insurance-category">EN</button></li>
    //                         </ul>
    //                     </div>';
    //         })
    //         ->rawColumns(['action', 'image_url'])
    //         ->make(true);
    // }
}
