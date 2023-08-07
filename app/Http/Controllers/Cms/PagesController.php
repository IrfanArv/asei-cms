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

    // UPDATE META DATA ON PAGE
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

    // GET ALL PAGE
    public function index()
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $token = $this->getAuthToken();
        // *PAGES

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->timeout(10)->get($wpApiUrl . '/wp/v2/pages?lang=id&_embed&per_page=100&page=1');


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
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Gagal mengirimkan permintaan ke server, Silahkan coba lagi');
        }
    }

    // GET PAGE BY ID
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

                $langData = 'id';
                // Fetch sliders data HOMe
                $homeSliders = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                    ->timeout(10)
                    ->get($wpApiUrl . '/wp/v2/home-sliders?lang=' . $langData . '&_embed');
                $sliderData = $homeSliders->json();
                // HOME PAGE ID (ENGLISH & INDONESIAN)
                // section home
                $sectionOneId = null;
                $sectionTwoId = null;
                $sectionThreeId = null;
                $sectionFourTitleId = null;
                $sectionFiveTitleId = null;
                $sectionFiveLogoOneId = null;
                $sectionFiveLogoTwoId = null;
                $sectionFiveDownloadCornerOneId = null;
                $sectionFiveDownloadCornerTwoId = null;
                $sectionFiveDownloadCornerThreeId = null;
                // end section home
                if ($id === '109') {
                    $sectionOneId = 457;
                    $sectionTwoId = 751;
                    $sectionThreeId = 757;
                    $langData = 'id';
                    $sectionFourTitleId = 461;
                    $sectionFiveTitleId = 740;
                    $sectionFiveLogoOneId = 743;
                    $sectionFiveLogoTwoId = 747;
                    $sectionFiveDownloadCornerOneId = 749;
                    $sectionFiveDownloadCornerTwoId = 753;
                    $sectionFiveDownloadCornerThreeId = 754;
                } elseif ($id === '112') {
                    $sectionOneId = 459;
                    $sectionTwoId = 752;
                    $sectionThreeId = 758;
                    $langData = 'en';
                    $sectionFourTitleId = 462;
                    $sectionFiveTitleId = 742;
                    $sectionFiveLogoOneId = 746;
                    $sectionFiveLogoTwoId = 748;
                    $sectionFiveDownloadCornerOneId = 749;
                    $sectionFiveDownloadCornerTwoId = 753;
                    $sectionFiveDownloadCornerThreeId = 754;
                }
                // END SECTION HOME

                // SECTION HOME
                $sectionOne = null;
                $sectionTwo = null;
                $sectionThree = null;
                $sectionFourData = null;
                $sectionFourTitle = null;
                $sectionFiveTitle = null;
                $sectionFiveLogoOne = null;
                $sectionFiveLogoTwo = null;
                $sectionFiveDownloadCornerOne = null;
                $sectionFiveDownloadCornerTwo = null;
                $sectionFiveDownloadCornerThree = null;
                // SECTION HOME
                // SECTION HOME
                if ($sectionOneId !== null) {
                    $sectionOneUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionOneId;
                    $responseSectionOne = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionOneUrl);

                    if ($responseSectionOne->ok()) {
                        $sectionOne = $responseSectionOne->json();
                    }

                    // * Section 2
                    $sectionTwoUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionTwoId;
                    $responseSectionTwo = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionTwoUrl);

                    if ($responseSectionTwo->ok()) {
                        $sectionTwo = $responseSectionTwo->json();
                    }

                    // * Section 3
                    $sectionThreeUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionThreeId;
                    $responseSectionThree = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionThreeUrl);

                    if ($responseSectionThree->ok()) {
                        $sectionThree = $responseSectionThree->json();
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
                    // SECTION HOME 5
                    if ($sectionFiveTitleId !== null) {
                        $sectionFiveUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFiveTitleId;
                        $responseSectionFiveUrl = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->timeout(10)
                            ->get($sectionFiveUrl);

                        if ($responseSectionFiveUrl->ok()) {
                            $sectionFiveTitle = $responseSectionFiveUrl->json();
                        }
                    }
                    // dowbload logo 1
                    if ($sectionFiveLogoOneId !== null) {
                        $sectionFiveLogoOneUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFiveLogoOneId;
                        $responsesectionFiveLogoOneUrl = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->timeout(10)
                            ->get($sectionFiveLogoOneUrl);

                        if ($responsesectionFiveLogoOneUrl->ok()) {
                            $sectionFiveLogoOne = $responsesectionFiveLogoOneUrl->json();
                        }
                    }
                    // download logo 2
                    if ($sectionFiveLogoTwoId !== null) {
                        $sectionFiveLogoTwoUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFiveLogoTwoId;
                        $responsesectionFiveLogoTwoUrl = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->timeout(10)
                            ->get($sectionFiveLogoTwoUrl);

                        if ($responsesectionFiveLogoTwoUrl->ok()) {
                            $sectionFiveLogoTwo = $responsesectionFiveLogoTwoUrl->json();
                        }
                    }
                    // download corner 1
                    if ($sectionFiveDownloadCornerOneId !== null) {
                        $sectionFiveDownloadCornerOneUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFiveDownloadCornerOneId;
                        $responsesectionFiveDownloadCornerOneUrl = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->timeout(10)
                            ->get($sectionFiveDownloadCornerOneUrl);

                        if ($responsesectionFiveDownloadCornerOneUrl->ok()) {
                            $sectionFiveDownloadCornerOne = $responsesectionFiveDownloadCornerOneUrl->json();
                        }
                    }
                    // download corner 2
                    if ($sectionFiveDownloadCornerTwoId !== null) {
                        $sectionFiveDownloadCornerTwoUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFiveDownloadCornerTwoId;
                        $responsesectionFiveDownloadCornerTwoUrl = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->timeout(10)
                            ->get($sectionFiveDownloadCornerTwoUrl);

                        if ($responsesectionFiveDownloadCornerTwoUrl->ok()) {
                            $sectionFiveDownloadCornerTwo = $responsesectionFiveDownloadCornerTwoUrl->json();
                        }
                    }
                    // download corner 3
                    if ($sectionFiveDownloadCornerThreeId !== null) {
                        $sectionFiveDownloadCornerThreeUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFiveDownloadCornerThreeId;
                        $responsesectionFiveDownloadCornerThreeUrl = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->timeout(10)
                            ->get($sectionFiveDownloadCornerThreeUrl);

                        if ($responsesectionFiveDownloadCornerThreeUrl->ok()) {
                            $sectionFiveDownloadCornerThree = $responsesectionFiveDownloadCornerThreeUrl->json();
                        }
                    }
                }
                // SECTION HOME
                // ABOUT US PAGE
                $sectionOneAboutId = null;
                $sectionThreeAboutId = null;
                $sectionFourAboutId = null;
                if ($id === '291') {
                    $langData = 'id';
                    $sectionOneAboutId = 8361;
                    $sectionThreeAboutId = 8379;
                    $sectionFourAboutId = 8382;
                } elseif ($id === '293') {
                    $langData = 'en';
                    $sectionOneAboutId = 8363;
                    $sectionThreeAboutId = 8380;
                    $sectionFourAboutId = 8384;
                }
                $sectionOneAbout = null;
                $sectionTwoAbout = null;
                $sectionThreeAbout = null;
                $sectionFourAbout = null;
                if ($sectionOneAboutId !== null) {
                    $sectionOneAboutUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionOneAboutId;
                    $resSectionAboutOne = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionOneAboutUrl);

                    if ($resSectionAboutOne->ok()) {
                        $sectionOneAbout = $resSectionAboutOne->json();
                    }
                    // section 2
                    $sectionAboutTwoUrl = $wpApiUrl . '/wp/v2/page-content?lang=' . $langData . '&group-pages=407';
                    $resSectionAboutTwo = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionAboutTwoUrl);


                    if ($resSectionAboutTwo->ok()) {
                        $sectionTwoAbout = $resSectionAboutTwo->json();
                    }

                    // section 3
                    $sectionAboutThreeUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionThreeAboutId;
                    $resSectionAboutThree = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionAboutThreeUrl);
                    if ($resSectionAboutThree->ok()) {
                        $sectionThreeAbout = $resSectionAboutThree->json();
                    }
                    // section 4
                    $sectionAboutFourUrl = $wpApiUrl . '/wp/v2/page-content/' . $sectionFourAboutId;
                    $resSectionAboutFour = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                        ->timeout(10)
                        ->get($sectionAboutFourUrl);
                    if ($resSectionAboutFour->ok()) {
                        $sectionFourAbout = $resSectionAboutFour->json();
                    }
                }

                // IMAGE BANNER FOR NON HOME PAGE
                $imageUrl = isset($pagesData['better_featured_image']['source_url']) ? $pagesData['better_featured_image']['source_url'] : null;

                return view('cms.pages.detail', compact(
                    'pagesData',
                    'imageUrl',
                    'sliderData',
                    'sectionOne',
                    'sectionTwo',
                    'sectionThree',
                    'sectionFourData',
                    'sectionFourTitle',
                    'sectionFiveTitle',
                    'sectionFiveLogoOne',
                    'sectionFiveLogoTwo',
                    'sectionFiveDownloadCornerOne',
                    'sectionFiveDownloadCornerTwo',
                    'sectionFiveDownloadCornerThree',
                    // about us
                    'sectionOneAbout',
                    'sectionTwoAbout',
                    'sectionThreeAbout',
                    'sectionFourAbout'
                ));
            } else {
                return redirect()->back()->with('error', 'Terjadi kesalahan teknis');
            }
        } catch (ConnectionException $e) {
            return redirect()->back()->with('error', 'Koneksi ke server gagal: ' . $e->getMessage());
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Permintaan ke server gagal: ' . $e->getMessage());
        }
    }

    // UPDATE PAGE SECTION
    public function updateSection(Request $request)
    {
        $sectionId = $request->input('section_id');
        $sectionType = $request->input('section_type');
        $sectionName = $request->input('section_name');
        $sectionDesc = $request->input('section_desc');
        $sectionImage = $request->file('section_image');
        $sectionUrl = $request->input('section_url') ?? '#';

        $wpApiUrl = env('WORDPRESS_API_URL');
        $endpoint = "/wp/v2/";

        if ($sectionType === 'home-sliders') {
            $endpoint .= 'home-sliders/' . $sectionId;
            $data = [
                'title' => $sectionName,
                'acf' => [
                    'description' => $sectionDesc,
                    'button_action' => [
                        'title' => '',
                        'url' => $sectionUrl,
                        'target' => '',
                    ],
                ],
            ];
        } elseif ($sectionType === 'page-content') {
            $endpoint .= 'page-content/' . $sectionId;
            $data = [
                'content' => $sectionDesc,
                'acf' => [
                    'section_name' => $sectionName,
                    'button' => [
                        'title' => '',
                        'url' => $sectionUrl,
                        'target' => '',
                    ],
                ],

            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid section_type']);
        }

        $response = $this->sendAuthenticatedRequest('put', $wpApiUrl . $endpoint, $data);

        if (!$response->successful()) {
            return response()->json(['success' => false, 'message' => 'Failed to update the post', 'dataError' => $response->json()]);
        }

        if ($sectionImage) {
            $uploadResponse = $this->uploadFeaturedMedia($sectionId, $sectionImage, $sectionType);
            if (!$uploadResponse['success']) {
                return response()->json(['success' => false, 'message' => 'Failed to upload the image']);
            }
        }

        return response()->json(['success' => true, 'message' => 'Data updated successfully']);
    }

    // CREATE NEW SLIDER
    public function storeSlider(Request $request)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');
        $data = $request->all();

        if (empty($data['name_id'])) {
            return response()->json(['success' => false, 'message' => 'Name (ID) is required'], 400);
        }

        if (empty($data['name_en'])) {
            return response()->json(['success' => false, 'message' => 'Name (EN) is required'], 400);
        }

        $slider_type = $data['slider_type'];
        $slideDataId = [
            'title' => $data['name_id'],
            'status' => 'publish',
            'acf' => [
                'description' => $data['description_id'],
                'button_action' => [
                    'title' => '',
                    'url' => $data['link_button'],
                    'target' => '',
                ],
            ],
        ];



        $responseId = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $slider_type . '?lang=id', $slideDataId);
        $lastId = $responseId->json()['id'];

        $postDataEn = [
            'title' => $data['name_en'],
            'status' => 'publish',
            'acf' => [
                'description' => $data['description_en'],
                'button_action' => [
                    'title' => '',
                    'url' => $data['link_button'],
                    'target' => '',
                ],
            ],
            'translations' => [
                'id' => $lastId,
            ],
        ];

        $responseEn = $this->sendAuthenticatedRequest('post', $wpApiUrl . '/wp/v2/' . $slider_type . '?lang=en', $postDataEn);
        $postIdEn = $responseEn->json()['id'];

        $mediaResponseId = $this->uploadFeaturedMedia($lastId, $request->file('image_banner'), $slider_type);
        $mediaResponseEn = $this->uploadFeaturedMedia($postIdEn, $request->file('image_banner'), $slider_type);

        if ($mediaResponseId['success'] && $mediaResponseEn['success']) {
            return response()->json(['success' => true, 'message' => 'New Slider Added Successfully.'], 201);
        }

        $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $slider_type . '/' . $lastId);
        $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $slider_type . '/' . $postIdEn);

        return response()->json(['success' => false, 'message' => 'Failed to add new slider'], 500);
    }

    public function deleteSlider($types, $id, $id_translate)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $sliderMediaIds = $this->getSliderMediaIds($types, $id, $id_translate);

        $response_id = $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $types . '/' . $id . '?force=true');
        $response_en = $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/' . $types . '/' . $id_translate . '?force=true');

        foreach ($sliderMediaIds as $mediaId) {
            $response_media = $this->sendAuthenticatedRequest('delete', $wpApiUrl . '/wp/v2/media/' . $mediaId);
        }

        $errorMessages = [];

        if (!$response_id->successful()) {
            $errorMessages[] = 'Failed to delete slide with ID: ' . $id;
        }

        if (!$response_en->successful()) {
            $errorMessages[] = 'Failed to delete slide with EN ID: ' . $id_translate;
        }

        if (empty($errorMessages)) {
            return response()->json(['success' => true, 'message' => 'Slider deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'messages' => $errorMessages], 500);
        }
    }

    private function getSliderMediaIds($types, $id, $id_translate)
    {
        $wpApiUrl = env('WORDPRESS_API_URL');

        $response_id = $this->sendAuthenticatedRequest('get', $wpApiUrl . '/wp/v2/' . $types . '/' . $id);
        $response_en = $this->sendAuthenticatedRequest('get', $wpApiUrl . '/wp/v2/' . $types . '/' . $id_translate);

        $sliderMediaIds = [];

        if ($response_id->successful()) {
            $sliderData_id = $response_id->json();
            if (isset($sliderData_id['featured_media']) && !empty($sliderData_id['featured_media'])) {
                $sliderMediaIds[] = $sliderData_id['featured_media'];
            }
        }

        if ($response_en->successful()) {
            $sliderData_en = $response_en->json();
            if (isset($sliderData_en['featured_media']) && !empty($sliderData_en['featured_media'])) {
                $sliderMediaIds[] = $sliderData_en['featured_media'];
            }
        }

        return $sliderMediaIds;
    }
}
