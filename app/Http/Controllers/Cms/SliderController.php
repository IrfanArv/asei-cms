<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthWPController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use DataTables;
use Carbon\Carbon;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cms.sliders.index');
    }

    public function getSliderData()
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
        ])->get($wpApiUrl . '/wp/v2/home-sliders?lang=id&_embed');

        if ($response->ok()) {
            $sliderData = $response->json();
            $sliders = [];

            foreach ($sliderData as $sliderItem) {
                $slider = [
                    'id' => $sliderItem['id'],
                    'date_gmt' => $sliderItem['date_gmt'],
                    'modified_gmt' => $sliderItem['modified_gmt'],
                    'status' => $sliderItem['status'],
                    'link' => $sliderItem['link'],
                    'title' => $sliderItem['title']['rendered'],
                    'description' => $sliderItem['acf']['description'],
                    'images' => $sliderItem['better_featured_image']['source_url'],
                    'slug' => $sliderItem['slug'],
                    'lang' => $sliderItem['lang'],
                    'translations' => $sliderItem['translations'],
                ];

                $sliders[] = $slider;
            }

            // return response()->json(['data' => $sliders]);
            return DataTables::of($sliders)
                ->addIndexColumn()
                ->editColumn('images', function ($row) {
                    if (empty($row['images'])) {
                        return '<img src="' . asset('/img/cms/avatars/no-thumbnail-medium.png') . '" alt="thumbnail" class="img-thumbnail rounded">';
                    } else {
                        return '<img src="' . $row['images'] . '" alt="thumbnail" class="img-thumbnail rounded">';
                    }
                })
                ->editColumn('title', function ($row) {
                    $title = $row['title'];
                    return new HtmlString(html_entity_decode($title));
                })
                ->editColumn('description', function ($row) {
                    $description = $row['description'];
                    $limit = 25;
                    $truncatedDescription = Str::limit($description, $limit);
                    return new HtmlString(html_entity_decode($truncatedDescription));
                })
                ->editColumn('status', function ($row) {
                    if ($row['status'] === 'publish') {
                        return '<button type="button" class="btn btn-sm rounded-pill btn-label-success waves-effect waves-light">' . $row['status'] . '</button>';
                    } else {
                        return '<button type="button" class="btn btn-sm rounded-pill btn-label-secondary waves-effect waves-light">' . $row['status'] . '</button>';
                    }
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
                ->rawColumns(['action', 'images', 'description', 'status', 'date_gmt', 'modified_gmt'])
                ->make(true);
        } else {
            $authController->authenticate();
            $token = session('jwt_token');
        }
    }
}
