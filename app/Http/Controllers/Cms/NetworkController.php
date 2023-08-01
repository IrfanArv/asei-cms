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

class NetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cms.networks.index');
    }

    public function getNetworkData()
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
        ])->get($wpApiUrl . '/wp/v2/networks?per_page=100&page=1');

        if ($response->ok()) {
            $networkData = $response->json();
            $networks = [];

            foreach ($networkData as $networkItem) {
                $network = [
                    'id' => $networkItem['id'],
                    'date_gmt' => $networkItem['date_gmt'],
                    'modified_gmt' => $networkItem['modified_gmt'],
                    'status' => $networkItem['status'],
                    'link' => $networkItem['link'],
                    'title' => $networkItem['title']['rendered'],
                    'kota' => $networkItem['acf']['kota'],
                    'alamat_lengkap' => $networkItem['acf']['alamat_lengkap'],
                    'kantor_pusat' => $networkItem['acf']['kantor_pusat'],
                    'slug' => $networkItem['slug'],
                    'maps_url' => $networkItem['acf']['maps_url']['url'],
                ];

                $networks[] = $network;
            }
            usort($networks, function ($a, $b) {
                return strcasecmp($a['title'], $b['title']);
            });

            // return response()->json(['data' => $networks]);
            return DataTables::of($networks)
                ->addIndexColumn()
                ->editColumn('kota', function ($row) {
                    $kota = $row['kota'];
                    return new HtmlString(html_entity_decode($kota));
                })
                ->editColumn('maps_url', function ($row) {
                    return '<a href="' . $row['maps_url'] . '" target="_blank" class="btn btn-sm rounded-pill btn-label-primary waves-effect waves-light">' . $row['maps_url'] . '</a>';
                })
                ->editColumn('alamat_lengkap', function ($row) {
                    $alamat_lengkap = $row['alamat_lengkap'];
                    $limit = 25;
                    $truncatedAlamat = Str::limit($alamat_lengkap, $limit);
                    return new HtmlString(html_entity_decode($truncatedAlamat));
                })
                ->editColumn('status', function ($row) {
                    if ($row['status'] === 'publish') {
                        return '<button type="button" class="btn btn-sm rounded-pill btn-label-success waves-effect waves-light">' . $row['status'] . '</button>';
                    } else {
                        return '<button type="button" class="btn btn-sm rounded-pill btn-label-secondary waves-effect waves-light">' . $row['status'] . '</button>';
                    }
                })
                ->editColumn('kantor_pusat', function ($row) {
                    if ($row['kantor_pusat'] === true) {
                        return '<button type="button" class="btn btn-sm rounded-pill btn-label-success waves-effect waves-light"> Yes </button>';
                    } else {
                        return '<button type="button" class="btn btn-sm rounded-pill btn-label-secondary waves-effect waves-light"> No </button>';
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
                ->rawColumns(['action', 'kota', 'maps_url', 'alamat_lengkap', 'status', 'date_gmt', 'modified_gmt', 'kantor_pusat'])
                ->make(true);
        } else {
            $authController->authenticate();
            $token = session('jwt_token');
        }
    }
}
