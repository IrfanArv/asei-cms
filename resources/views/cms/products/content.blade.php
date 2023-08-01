@extends('layouts.cms')
@section('title', 'Create Insurace Product')
@section('content')
    <div class="row mb-4">
        <div class="col">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard/insurance/' }}">Produk Asuransi</a>
                    </li>
                    <li class="breadcrumb-item active">Konten halaman {{ $categoryData['name'] ?? '' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            {{-- META DATA --}}
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
                <div class="collapse">
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-0">
                            <ul class="nav nav-tabs nav-card" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="content-id-tab" data-bs-toggle="tab" href="#content-id"
                                        role="tab" aria-controls="content-id" aria-selected="true">ID</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="content-en-tab" data-bs-toggle="tab" href="#content-en"
                                        role="tab" aria-controls="content-en" aria-selected="false">EN</a>
                                </li>
                            </ul>
                            <div class="tab-content pt-0" id="postsTabContent">
                                <div class="tab-pane fade show active" id="content-id" role="tabpanel"
                                    aria-labelledby="content-id-tab">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label" for="cat_name_id">Name (ID)</label>
                                            <input type="text" id="cat_name_id" name="cat_name_id" class="form-control"
                                                placeholder="Category Name (ID)" value="{{ $categoryData['name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="cat_slug_id">Slug (ID)</label>
                                            <input type="text" id="cat_slug_id" name="cat_slug_id" class="form-control"
                                                placeholder="Slug Url (ID)" value="{{ $categoryData['slug'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="cat_description_id">Description (ID)</label>
                                            <textarea class="form-control" id="cat_description_id" name="cat_description_id" rows="3">{{ $categoryData['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="content-en" role="tabpanel" aria-labelledby="content-en-tab">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label" for="cat_name_en">Name (EN)</label>
                                            <input type="text" id="cat_name_en" name="cat_name_en" class="form-control"
                                                placeholder="Category Name (EN)"
                                                value="{{ $translatedCategoryData['name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="cat_slug_en">Slug (EN)</label>
                                            <input type="text" id="cat_slug_en" name="cat_slug_en" class="form-control"
                                                placeholder="Slug Url (EN)"
                                                value="{{ $translatedCategoryData['slug'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="cat_description_en">Description
                                                (EN)</label>
                                            <textarea class="form-control" id="cat_description_en" name="cat_description_en" rows="3">{{ $translatedCategoryData['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="cover">Banner Page</label>
                                    <div class="row mb-2">
                                        <div class="col-auto">
                                            @if ($imageUrl)
                                                <img class="img-fluid rounded" id="modal-preview"
                                                    src="{{ $imageUrl }}}"><br><br>
                                            @else
                                                <img class="img-fluid rounded" id="modal-preview"
                                                    src="https://dummyimage.com/1000x500/005e9d/fff.png"><br><br>
                                            @endif
                                            <div
                                                class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                                <button class="btn-upload">Change Banner</button>
                                                <input id="image" type="file" name="image" accept="image/*"
                                                    onchange="readURL(this);">
                                            </div>
                                            <input type="hidden" name="hidden_image" id="hidden_image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">

                            <button class="btn btn-primary waves-effect waves-light float-end mb-4 mt-0"
                                type="submit">Update Meta
                                Data</button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- GREETINGS --}}
            @if ($greetingData)
                <div class="card card-action mb-4">
                    <div class="card-header">
                        <div class="card-action-title">Introduction
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
                        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label" for="cat_name_id">Title</label>
                                        <input type="text" id="title_id" name="title_id" class="form-control"
                                            placeholder="Title" value="{{ $greetingData['title_greeting'] ?? '' }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="greeting_desc_id">Description
                                            (ID)</label>
                                        <textarea class="ckeditor" id="greeting_desc_id" name="greeting_desc_id" rows="3">{{ $greetingData['description_greeting'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label class="form-label" for="cover">IMAGE</label>
                                    <div class="row mb-2">
                                        <div class="col-auto">
                                            @if (isset($greetingData['images_greeting']) && $greetingData['images_greeting'])
                                                <img class="img-fluid img-thumbnail" id="modal-preview"
                                                    src="{{ $greetingData['images_greeting'] }}"><br><br>
                                            @else
                                                <img class="img-fluid rounded" id="modal-preview"
                                                    src="https://dummyimage.com/1000x500/005e9d/fff.png"><br><br>
                                            @endif
                                            <div
                                                class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                                <button class="btn-upload">Change Image</button>
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
                                    type="submit">Update Introduction</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            {{-- SLIDERS --}}
            @if ($sliderData)
                <div class="card card-action mb-4">
                    <div class="card-header">
                        <div class="card-action-title">Sliders
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
                        <div class="card-body p-0">
                            <div class="card-datatable table-responsive">
                                <table id="table" class="table border-top bgnew">
                                    <thead>
                                        <tr>
                                            <th>Slide Image</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sliderData as $slide)
                                            @php
                                                $image_slide = $slide['images_slide'] ? asset($slide['images_slide']) : asset('img/cms/avatars/no-thumbnail-medium.png');
                                                $title = $slide['title_slide'];
                                                $status = $slide['status_slide'] === 'publish' ? '<button type="button" class="btn btn-sm rounded-pill btn-label-success waves-effect waves-light">' . $slide['status_slide'] . '</button>' : '<button type="button" class="btn btn-sm rounded-pill btn-label-secondary waves-effect waves-light">' . $slide['status_slide'] . '</button>';
                                            @endphp
                                            <tr>
                                                <td><img src="{{ $image_slide }}" alt="thumbnail"
                                                        class="img-thumbnail rounded"></td>
                                                <td>{{ $slide['title_slide'] }}</td>
                                                <td>{{ Str::limit($slide['description_slide'], 25) }}</td>
                                                <td>{!! $status !!}</td>
                                                <td>
                                                    <div class="d-inline-block">
                                                        <a href="javascript:;" class="btn btn-sm btn-icon item-edit"><i
                                                                class="text-warning ti ti-pencil"></i></a>
                                                        <a href="javascript:;" class="btn btn-sm btn-icon item-delete"><i
                                                                class="text-danger ti ti-trash"></i></a>
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
            @endif

        </div>

    @endsection
    @push('scripts')
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}');
                </script>
            @endforeach
        @endif
        <script src="{{ asset('assets/js/cards-actions.js') }}"></script>
        <script defer>
            document.addEventListener('DOMContentLoaded', function() {
                const nameIdInput = document.getElementById('cat_name_id');
                const nameEnInput = document.getElementById('cat_name_en');
                const slugIdInput = document.getElementById('cat_slug_id');
                const slugEnInput = document.getElementById('cat_slug_en');

                function updateSlug(inputValue, slugInput) {
                    const slug = inputValue.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
                    slugInput.value = slug;
                }

                nameIdInput.addEventListener('keyup', function() {
                    updateSlug(this.value, slugIdInput);
                });

                nameEnInput.addEventListener('keyup', function() {
                    updateSlug(this.value, slugEnInput);
                });
            });
            // table slider
            $(document).ready(function() {
                $('#table').DataTable();
            });
        </script>
    @endpush
