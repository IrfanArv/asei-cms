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
            <a href="{{ $pagesData['lang'] === 'id' ? ENV('APP_URL') . '/dashboard/web-pages/' . $pagesData['translations']['en'] : ENV('APP_URL') . '/dashboard/web-pages/' . $pagesData['id'] }}"
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
                                                <img class="img-fluid img-thumbnail" id="modal-preview"
                                                    src="{{ $imageUrl }}"><br><br>
                                            @else
                                                <img class="img-fluid rounded" id="modal-preview"
                                                    src="https://dummyimage.com/1100x345/005e9d/fff.png"><br><br>
                                            @endif
                                            <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                                <button class="btn-upload">Change Banner</button>
                                                <input id="image" type="file" name="image" accept="image/*"
                                                    onchange="readURL(this);">
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
                        <div class="card-action-title">Home Slider
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
                                        @endphp
                                        <tr>

                                            <td><img src="{{ $image_slider }}" alt="thumbnail"
                                                    class="img-thumbnail rounded"></td>
                                            <td>{{ $sliders['title']['rendered'] }}</td>
                                            {{-- <td>{{ $sliders['acf']['description'] }}</td> --}}
                                            <td>{{ $updated }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-label-primary dropdown-toggle waves-effect"
                                                        data-bs-toggle="dropdown" aria-expanded="false">Details</button>
                                                    <ul class="dropdown-menu" style="">
                                                        <li><a class="dropdown-item"
                                                                href="#">{{ $sliders['id'] }}ID</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="#">{{ $sliders['translations']['en'] }}EN</a>
                                                        </li>
                                                    </ul>
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
        {{-- sectionOne --}}

        <div class="row">
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
                        <div class="collapse show">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sectionOne['acf']['section_name'] }}</h5>
                                <img class="img-fluid d-flex mx-auto my-4 rounded"
                                    src="{{ $sectionOne['better_featured_image']['source_url'] }}" alt="Card image cap">
                                <p class="card-text">{!! $sectionOne['content']['rendered'] !!}</p>
                                <a href="javascript:void(0);" class="card-link">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                    <div class="collapse show">
                        <div class="row">
                            @if ($sectionFourTitle)
                                <div class="col-md-12">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $sectionOne['acf']['section_name'] }}</h5>
                                        <p class="card-text">
                                            {!! $sectionOne['content']['rendered'] !!}
                                        </p>
                                        <a href="javascript:void(0)" class="card-link">Edit</a>
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
                                            <a href="javascript:void(0);" class="card-link">Edit</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
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
            $('#slider_table').DataTable({
                "order": [], // Disable initial sorting if needed
                "paging": true, // Enable pagination
                "lengthMenu": [10, 25, 50], // Number of items per page options
                "pageLength": 10, // Default number of items per page
                "searching": true, // Enable searching
                "info": true, // Show information about current page and total records
                "language": {
                    "search": "Search records:",
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
        });
    </script>

@endpush
