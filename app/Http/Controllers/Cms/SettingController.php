<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Api\RequestApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requestWPController = new RequestApiController();
        $dataSettings = $requestWPController->getWebSettings();
        // return response()->json(['data' => $dataSettings]);
        return view('cms.settings.index', compact('dataSettings'));
    }
}
