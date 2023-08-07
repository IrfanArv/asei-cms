@extends('layouts.cms')
@section('title', 'Halaman ' . $pagesData['title']['rendered'])
@section('content')
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard/web-pages/' }}">Halaman</a>
                    </li>
                    <li class="breadcrumb-item active">Halaman {{ $pagesData['title']['rendered'] }}</li>
                </ol>
            </nav>
        </div>
        <div class="col">
            <a href="{{ $pagesData['lang'] === 'id' ? ENV('APP_URL') . '/dashboard/web-pages/' . $pagesData['translations']['en'] : ENV('APP_URL') . '/dashboard/web-pages/' . $pagesData['translations']['id'] }}"
                class="btn rounded-pill btn-outline-primary waves-effect float-end">
                <span class="ti-xs ti ti-switch-horizontal me-1"></span>
                @if ($pagesData['lang'] === 'id')
                    Switch to English Data
                @elseif ($pagesData['lang'] === 'en')
                    Switch to Indonesia Data
                @else
                    Switch to Language {{ $pagesData['lang'] }}
                @endif
            </a>


        </div>
    </div>
    {{-- META DATA DONT SHOW IF HOME PAGE --}}
    @if ($pagesData['id'] !== 112 && $pagesData['id'] !== 109)
        <div class="row">
            <div class="col-md-12">
                <div class="card card-action mb-4">
                    <div class="card-header">
                        <div class="card-action-title">Meta Data
                        </div>
                        <div class="card-action-element">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible"><i
                                            class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="collapse show">
                        <form method="POST" action="{{ route('web.pages.update', $pagesData['id']) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label" for="title">Page Name</label>
                                        <input type="text" id="title" name="title" class="form-control"
                                            placeholder="Page Name" value="{{ $pagesData['title']['rendered'] }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="content">Meta Title</label>
                                        <textarea class="ckeditor" id="content" name="content" rows="5">{{ $pagesData['content']['rendered'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="cover">Banner Page</label>
                                    <div class="row mb-2">
                                        <div class="col-auto">
                                            @if ($imageUrl)
                                                <img class="img-fluid img-thumbnail" id="modal-old"
                                                    src="{{ $imageUrl }}"><br><br>
                                            @else
                                                <img class="img-fluid rounded" id="modal-old"
                                                    src="https://dummyimage.com/1100x345/005e9d/fff.png"><br><br>
                                            @endif
                                            <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                                <button class="btn-upload">Change Banner</button>
                                                <input id="image" type="file" name="image" accept="image/*"
                                                    onchange="oldReadURL(this);">
                                            </div>
                                            <input type="hidden" name="hidden_image" id="hidden_image">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary waves-effect waves-light float-end mb-4 mt-0"
                                    type="submit">Update
                                    Meta Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- RENDER DATA HOME PAGE --}}
    @if ($pagesData['id'] === 112 || $pagesData['id'] == 109)
        {{-- HOME SLIDER --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-action mb-4">
                    <div class="card-header">
                        <div class="card-action-title">Home Slider</div>
                        <div class="card-action-element">
                            <ul class="list-inline mt-1">
                                <div class="col">
                                    <button type="button" class="btn rounded-pill btn-label-primary waves-effect new-slide"
                                        data-slide_type="home-sliders">
                                        <span class="ti-xs ti ti-circle-plus me-1"></span>New Slide
                                    </button>
                                </div>
                                {{-- <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible"><i
                                            class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="collapse show">
                        <div class="card-datatable table-responsive">
                            <table id="slider_table" class="table border-top bgnew">
                                <thead>
                                    <tr>

                                        <th>Image</th>
                                        <th>Title</th>
                                        {{-- <th>Subtitle</th> --}}
                                        <th>Last update</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sliderData as $sliders)
                                        @php
                                            $image_slider = $sliders['better_featured_image']['source_url'] ? $sliders['better_featured_image']['source_url'] : asset('img/cms/avatars/no-thumbnail-medium.png');
                                            $title = $sliders['title'];
                                            $updated = \Carbon\Carbon::parse($sliders['modified_gmt'])->format('d M Y H:i');
                                            if (isset($sliders['acf']['button_action']) && is_array($sliders['acf']['button_action'])) {
                                                $url = isset($sliders['acf']['button_action']['url']) ? $sliders['acf']['button_action']['url'] : '';
                                            } else {
                                                $url = '';
                                            }
                                        @endphp
                                        <tr>

                                            <td><img src="{{ $image_slider }}" alt="thumbnail"
                                                    class="img-thumbnail rounded"></td>
                                            <td>{{ $sliders['title']['rendered'] }}</td>
                                            {{-- <td>{{ $sliders['acf']['description'] }}</td> --}}
                                            <td>{{ $updated }}</td>
                                            <td>
                                                <div class="d-inline-block">
                                                    <button type="button" class="btn btn-sm btn-icon page-edit"
                                                        data-post_type="home-sliders" data-id="{{ $sliders['id'] }}"
                                                        data-name="{{ $sliders['title']['rendered'] }}"
                                                        data-label_button="Button Link"
                                                        data-desc="{{ $sliders['acf']['description'] }}"
                                                        data-url_button="{{ htmlspecialchars($url) }}"
                                                        data-image="{{ $image_slider }}"><i
                                                            class="text-warning ti ti-pencil"></i></button>
                                                    <button type="button" class="btn btn-sm btn-icon page-delete"
                                                        data-id="{{ $sliders['id'] }}" data-slide_type="home-sliders"
                                                        @if ($pagesData['id'] === 112) data-id_translate="{{ $sliders['translations']['id'] }}"
                                                            @elseif ($pagesData['id'] === 109)
                                                                data-id_translate="{{ $sliders['translations']['en'] }}" @endif
                                                        data-name="{{ $sliders['title']['rendered'] }}">
                                                        <i class="text-danger ti ti-trash"></i>
                                                    </button>

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- sectionOne --}}
            @if ($sectionOne)
                <div class="col-md-6">
                    <div class="card card-action mb-4">
                        <div class="card-header">
                            <div class="card-action-title">Section 1
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sectionOne['acf']['section_name'] }}</h5>
                                <img class="img-fluid d-flex mx-auto my-4 rounded"
                                    src="{{ $sectionOne['better_featured_image']['source_url'] }}" alt="Card image cap">
                                <p class="card-text">{!! $sectionOne['content']['rendered'] !!}</p>
                                <a href="javascript:void(0);" class="card-link page-edit" data-post_type="page-content"
                                    data-id="{{ $sectionOne['id'] }}"
                                    data-name="{{ $sectionOne['acf']['section_name'] }}"
                                    data-desc="{!! $sectionOne['content']['rendered'] !!}"
                                    data-image="{{ $sectionOne['better_featured_image']['source_url'] }}">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($sectionTwo)
                <div class="col-md-6">
                    <div class="card card-action mb-4">
                        <div class="card-header">
                            <div class="card-action-title">Section 2
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sectionTwo['acf']['section_name'] }}</h5>
                                <p class="card-text">{!! $sectionTwo['content']['rendered'] !!}</p>
                                <a href="javascript:void(0);" class="card-link page-edit" data-post_type="page-content"
                                    data-available_button="0" data-post_type="page-content"
                                    data-id="{{ $sectionTwo['id'] }}" data-label_button="Button Link"
                                    data-name="{{ $sectionTwo['acf']['section_name'] }}"
                                    data-url_button="{{ $sectionTwo['acf']['button']['url'] }}"
                                    data-desc="{!! $sectionTwo['content']['rendered'] !!}">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($sectionThree)
                <div class="col-md-6">
                    <div class="card card-action mb-4">
                        <div class="card-header">
                            <div class="card-action-title">Section 3
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sectionThree['acf']['section_name'] }}</h5>
                                <p class="card-text">{!! $sectionThree['content']['rendered'] !!}</p>
                                <a href="javascript:void(0);" class="card-link page-edit" data-post_type="page-content"
                                    data-available_button="0" data-post_type="page-content"
                                    data-id="{{ $sectionThree['id'] }}" data-label_button="Button Link"
                                    data-name="{{ $sectionThree['acf']['section_name'] }}"
                                    data-url_button="{{ $sectionThree['acf']['button']['url'] }}"
                                    data-desc="{!! $sectionThree['content']['rendered'] !!}">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- sectionFour --}}
            @if ($sectionFourTitle && $sectionFourData)
                <div class="col-md-6">
                    <div class="card card-action mb-4">
                        <div class="card-header">
                            <div class="card-action-title">Section 4
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="row">
                                @if ($sectionFourTitle)
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $sectionFourTitle['acf']['section_name'] }}</h5>
                                            <p class="card-text">
                                                {!! $sectionFourTitle['content']['rendered'] !!}
                                            </p>
                                            <a href="javascript:void(0)" class="card-link page-edit"
                                                data-post_type="page-content" data-id="{{ $sectionFourTitle['id'] }}"
                                                data-name="{{ $sectionFourTitle['acf']['section_name'] }}"
                                                data-desc="{!! $sectionFourTitle['content']['rendered'] !!}" data-image="">Edit</a>
                                        </div>
                                        <hr>
                                    </div>
                                @endif

                                @if ($sectionFourData)
                                    @foreach ($sectionFourData as $item)
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $item['title']['rendered'] }}</h5>
                                                <img class="img-fluid d-flex mx-auto my-4 rounded"
                                                    src="{{ $item['better_featured_image']['source_url'] }}"
                                                    alt="Card image cap">
                                                <p class="card-text">{!! $item['content']['rendered'] !!}</p>
                                                <a href="javascript:void(0);" class="card-link page-edit"
                                                    data-id="{{ $item['id'] }}" data-post_type="page-content"
                                                    data-name="{{ $item['title']['rendered'] }}"
                                                    data-desc="{!! $item['content']['rendered'] !!}"
                                                    data-image="{{ $item['better_featured_image']['source_url'] }}">Edit</a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- sectionFive --}}
            @if ($sectionFiveTitle)
                @php
                    if (isset($sectionFiveTitle['acf']['button']) && is_array($sectionFiveTitle['acf']['button'])) {
                        $url = isset($sectionFiveTitle['acf']['button']['url']) ? $sectionFiveTitle['acf']['button']['url'] : '';
                    } else {
                        $url = '';
                    }
                @endphp
                <div class="col-md-12">
                    <div class="card card-action mb-4">
                        <div class="card-header">
                            <div class="card-action-title">Section 5
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <div class="row">
                                    <h5 class="card-title">{{ $sectionFiveTitle['acf']['section_name'] }}
                                        <a href="javascript:void(0);" class="card-link page-edit"
                                            data-available_button="0" data-post_type="page-content"
                                            data-label_button="Video Embed"
                                            data-url_button="{{ htmlspecialchars($url) }}"
                                            data-id="{{ $sectionFiveTitle['id'] }}"
                                            data-name="{{ $sectionFiveTitle['acf']['section_name'] }}"
                                            data-desc="{!! $sectionFiveTitle['content']['rendered'] !!}"
                                            data-image="{{ $sectionFiveTitle['better_featured_image']['source_url'] }}">Edit</a>
                                    </h5>
                                    <p class="card-text m-0">{!! $sectionFiveTitle['content']['rendered'] !!}</p>

                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img class="img-fluid d-flex mx-auto my-4 rounded"
                                                    src="{{ $sectionFiveLogoOne['better_featured_image']['source_url'] }}"
                                                    alt="Card image cap">
                                                <p class="card-text">{!! $sectionFiveLogoOne['content']['rendered'] !!}
                                                    <a href="javascript:void(0);" class="card-link page-edit"
                                                        data-show_name="1" data-post_type="page-content"
                                                        data-id="{{ $sectionFiveLogoOne['id'] }}"
                                                        data-desc="{!! $sectionFiveLogoOne['content']['rendered'] !!}"
                                                        data-image="{{ $sectionFiveLogoOne['better_featured_image']['source_url'] }}">Edit</a>
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <img class="img-fluid d-flex mx-auto my-4 rounded pb-5"
                                                    src="{{ $sectionFiveLogoTwo['better_featured_image']['source_url'] }}"
                                                    alt="Card image cap">
                                                <p class="card-text">{!! $sectionFiveLogoTwo['content']['rendered'] !!}
                                                    <a href="javascript:void(0);" class="card-link page-edit"
                                                        data-post_type="page-content" data-show_name="1"
                                                        data-id="{{ $sectionFiveLogoTwo['id'] }}"
                                                        data-desc="{!! $sectionFiveLogoTwo['content']['rendered'] !!}"
                                                        data-image="{{ $sectionFiveLogoTwo['better_featured_image']['source_url'] }}">Edit</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 class="card-title">Download Corner
                                            </h5>
                                            <button type="button" data-available_button="0" data-show_desc="1"
                                                data-post_type="page-content" data-label_button="Link Company Profile"
                                                data-id="{{ $sectionFiveDownloadCornerOne['id'] }}"
                                                data-name="{{ $sectionFiveDownloadCornerOne['acf']['section_name'] }}"
                                                data-url_button="{{ $sectionFiveDownloadCornerOne['acf']['button']['url'] }}"
                                                class="btn rounded-pill btn-outline-primary waves-effect page-edit">{!! $sectionFiveDownloadCornerOne['acf']['section_name'] !!}</button>
                                            <button type="button" data-available_button="0" data-show_desc="1"
                                                data-post_type="page-content" data-label_button="Link Annual Report"
                                                data-id="{{ $sectionFiveDownloadCornerTwo['id'] }}"
                                                data-name="{{ $sectionFiveDownloadCornerTwo['acf']['section_name'] }}"
                                                data-url_button="{{ $sectionFiveDownloadCornerTwo['acf']['button']['url'] }}"
                                                class="btn rounded-pill btn-outline-primary waves-effect page-edit">{!! $sectionFiveDownloadCornerTwo['acf']['section_name'] !!}</button>
                                            <button type="button" data-available_button="0" data-show_desc="1"
                                                data-post_type="page-content"
                                                data-label_button="Link Laporan Berkelanjutan"
                                                data-id="{{ $sectionFiveDownloadCornerThree['id'] }}"
                                                data-name="{{ $sectionFiveDownloadCornerThree['acf']['section_name'] }}"
                                                data-url_button="{{ $sectionFiveDownloadCornerThree['acf']['button']['url'] }}"
                                                class="btn rounded-pill btn-outline-primary waves-effect page-edit">{!! $sectionFiveDownloadCornerThree['acf']['section_name'] !!}</button>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <img class="img-fluid d-flex mx-auto my-4 rounded" style="height: 350px;"
                                            src="{{ $sectionFiveTitle['better_featured_image']['source_url'] }}"
                                            alt="Card image cap">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
    {{-- about page data --}}
    @if ($pagesData['id'] === 291 || $pagesData['id'] == 293)
        <div class="row">
            @if ($sectionOneAbout)
                <div class="col-md-6">
                    <div class="card card-action">
                        <div class="card-header">
                            <div class="card-action-title">Section 1
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $sectionOneAbout['acf']['section_name'] }}
                                        <a href="javascript:void(0);" class="card-link page-edit"
                                            data-post_type="page-content" data-available_button="0"
                                            data-label_button="Video Embed"
                                            data-url_button="{{ $sectionOneAbout['acf']['button']['url'] }}"
                                            data-id="{{ $sectionOneAbout['id'] }}"
                                            data-name="{{ $sectionOneAbout['acf']['section_name'] }}"
                                            data-desc="{!! $sectionOneAbout['content']['rendered'] !!}"
                                            data-image="{{ $sectionOneAbout['better_featured_image']['source_url'] }}">Edit</a>
                                    </h5>
                                    <img class="img-fluid d-flex mx-auto my-4 rounded"
                                        src="{{ $sectionOneAbout['better_featured_image']['source_url'] }}"
                                        alt="Card image cap">
                                    <p class="card-text">{!! $sectionOneAbout['content']['rendered'] !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($sectionTwoAbout)
                <div class="col-md-6">
                    <div class="card card-action">
                        <div class="card-header">
                            <div class="card-action-title">Section 2
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <div class="card-body">
                                    <ul class="timeline mb-0">
                                        @foreach ($sectionTwoAbout as $item)
                                            <li class="timeline-item timeline-item-transparent">
                                                <span class="timeline-point timeline-point-primary"></span>
                                                <div class="timeline-event">
                                                    <div class="d-flex flex-sm-row flex-column">
                                                        <img src="{{ $item['better_featured_image']['source_url'] }}"
                                                            class="rounded mb-sm-0 mb-3 me-3" alt="Shoe img"
                                                            height="62" width="62">
                                                        <div>
                                                            <div class="timeline-header flex-wrap mb-2">
                                                                <h6 class="mb-0">{{ $item['acf']['section_name'] }}</h6>
                                                                <span class="text-muted">
                                                                    <a href="javascript:void(0);"
                                                                        class="card-link page-edit"
                                                                        data-post_type="page-content"
                                                                        data-id="{{ $item['id'] }}"
                                                                        data-name="{{ $item['acf']['section_name'] }}"
                                                                        data-desc="{!! $item['content']['rendered'] !!}"
                                                                        data-image="{{ $item['better_featured_image']['source_url'] }}">Edit</a>
                                                                </span>
                                                            </div>
                                                            <p>
                                                                {!! $item['content']['rendered'] !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($sectionThreeAbout)
                <div class="col-md-6 mt-4">
                    <div class="card card-action">
                        <div class="card-header">
                            <div class="card-action-title">Section 3
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sectionThreeAbout['acf']['section_name'] }}</h5>
                                <p class="card-text">{!! $sectionThreeAbout['content']['rendered'] !!}</p>
                                <a href="javascript:void(0);" class="card-link page-edit" data-post_type="page-content"
                                    data-post_type="page-content" data-id="{{ $sectionThreeAbout['id'] }}"
                                    data-name="{{ $sectionThreeAbout['acf']['section_name'] }}"
                                    data-desc="{!! $sectionThreeAbout['content']['rendered'] !!}">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($sectionFourAbout)
                <div class="col-md-6 mt-4">
                    <div class="card card-action">
                        <div class="card-header">
                            <div class="card-action-title">Section 4
                            </div>
                            <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0);" class="card-collapsible"><i
                                                class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="collapse">
                            <div class="card-body">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $sectionFourAbout['acf']['section_name'] }}
                                        <a href="javascript:void(0);" class="card-link page-edit"
                                            data-post_type="page-content" data-id="{{ $sectionFourAbout['id'] }}"
                                            data-name="{{ $sectionFourAbout['acf']['section_name'] }}"
                                            data-desc="{!! $sectionFourAbout['content']['rendered'] !!}"
                                            data-image="{{ $sectionFourAbout['better_featured_image']['source_url'] }}">Edit</a>
                                    </h5>
                                    <img class="img-fluid d-flex mx-auto my-4 rounded"
                                        src="{{ $sectionFourAbout['better_featured_image']['source_url'] }}"
                                        alt="Card image cap">
                                    <p class="card-text">{!! $sectionFourAbout['content']['rendered'] !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

@endsection
@push('scripts')
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}');
        </script>
    @endif
    <script src="{{ asset('assets/js/cards-actions.js') }}"></script>
    <script>
        $(document).ready(function() {
            var slider_table = $('#slider_table').DataTable({
                "order": [],
                "paging": true,
                "lengthMenu": [10, 25, 50],
                "pageLength": 10,
                "searching": true,
                "info": true,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ records per page",
                    "zeroRecords": "No matching records found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
            // GET DATA BY ID
            $('body').on('click', '.page-edit', function() {
                var sectionImage = $(this).data('image');

                $('#modalEditSection').modal('show');

                var sectionType = $(this).data('post_type');
                var availableButton = $(this).data('available_button');
                var showTitle = $(this).data('show_name');
                var showDesc = $(this).data('show_desc');

                var title = (sectionType === 'home-sliders') ? `Edit Slide` : `Edit Section`;
                $('#titles-modal-edit-section').html(title);
                $('#label_button').html($(this).data('label_button'));
                $('#section_id').val($(this).data('id'));
                $('#section_type').val(sectionType);
                $('#section_name').val($(this).data('name'));
                $('#section_desc').val($(this).data('desc'));
                $('#section_url').val($(this).data('url_button'));


                if (sectionImage) {

                    $('#section_image_div').show();
                    $('#modal-preview').attr('src', sectionImage);
                } else {
                    $('#section_image_div').hide();

                }
                if (sectionType === 'home-sliders' || availableButton === 0) {
                    $('#section_button_div').show();
                } else {
                    $('#section_button_div').hide();
                }
                if (showTitle === 1) {
                    $('#section_title_div').hide();
                } else {
                    $('#section_title_div').show();
                }
                if (showDesc === 1) {
                    $('#section_desc_div').hide();
                } else {
                    $('#section_desc_div').show();
                }

            });
            // SUBMIT DATA EVENT UPDATE CATEGORY
            $('body').on('submit', '#formUpdateSection', function(e) {
                e.preventDefault();
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('updateSection') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modals-loading').show();
                        $('.is-invalid').removeClass('is-invalid');
                        $('.error').html('');
                        $('#modalEditSection').modal('hide');
                    },
                    success: (data) => {
                        $('#modals-loading').hide();
                        $('#formUpdateSection').trigger("reset");
                        $('#modalEditSection').modal('hide');
                        $('#btn-save').html('Save Changes');

                        if (data.success) {
                            toastr.success(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                            location.reload();
                        } else {
                            toastr.error(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    },

                    error: function(data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                        $('#modals-loading').hide();
                        if (data.responseJSON.success === false) {
                            var errorMessage = data.responseJSON.message ||
                                'Wopps! Errors';
                            toastr.error(errorMessage, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        } else {
                            toastr.error('Wopps! Errors', {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    }
                });
            });
            // CREATE SLIDERS
            $('body').on('click', '.new-slide', function() {
                $('#modalCreateSliders').modal('show');
                $('#title_slides').html('Add Slider');
                var slideType = $(this).data('slide_type');
                $('#slider_type').val(slideType);
            });
            // SUBMIT NEW SLIDERS
            $('body').on('submit', '#formCreateSliders', function(e) {
                e.preventDefault();
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('storeSlider') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modals-loading').show();
                        $('.is-invalid').removeClass('is-invalid');
                        $('.error').html('');
                        $('#modalCreateSliders').modal('hide');
                    },
                    success: (data) => {
                        $('#modals-loading').hide();
                        $('#formCreateSliders').trigger("reset");
                        $('#modalCreateSliders').modal('hide');
                        $('#btn-save').html('Save Changes');

                        if (data.success) {
                            toastr.success(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                            location.reload();
                        } else {
                            toastr.error(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    },

                    error: function(data) {
                        // console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                        $('#modals-loading').hide();
                        $('#modalCreateSliders').modal('hide');
                        $('#formCreateSliders').trigger("reset");
                        if (data.responseJSON.success === false) {
                            var errorMessage = data.responseJSON.message ||
                                'Wopps! Errors';
                            toastr.error(errorMessage, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        } else {
                            toastr.error('Wopps! Errors', {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    }
                });
            });
            // DELETE SLIDERS
            $('body').on('click', '.page-delete', function() {
                var slideID = $(this).data('id');
                var slideIDTranslate = $(this).data('id_translate');
                var slideName = $(this).data('name');
                var slideType = $(this).data('slide_type');
                var text_data = 'Are you sure to delete slider ' + ' ' + slideName + ' ?';
                Swal.fire({
                    title: 'Delete Slider',
                    text: text_data,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-1',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "DELETE",
                            url: SITEURL + "/dashboard/delete-sliders/" + slideType + '/' +
                                slideID + '/' + slideIDTranslate,
                            dataType: "JSON",
                            beforeSend: function() {
                                $('#modals-loading').show();
                            },
                            success: function(data) {
                                $('#modals-loading').hide();
                                if (data.success == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'Slider has been deleted.',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    })
                                    location.reload();
                                }
                            },
                            error: function(data) {
                                $('#modals-loading').hide();
                                console.log('Error:', data);
                                if (data.responseJSON.success === false) {
                                    var errorMessage = data.responseJSON.message ||
                                        'Wopps! Errors';
                                    toastr.error(errorMessage, {
                                        positionClass: 'toast-top-center',
                                        toastClass: 'toastr-center',
                                    });
                                } else {
                                    toastr.error('Wopps! Errors', {
                                        positionClass: 'toast-top-center',
                                        toastClass: 'toastr-center',
                                    });
                                }
                            }

                        });
                    }
                });
            });

        });
    </script>

@endpush
