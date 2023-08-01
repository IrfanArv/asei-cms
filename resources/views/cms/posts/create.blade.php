@extends('layouts.cms')
@section('title', 'Create News')
@section('content')

    <form method="POST" action="{{ route('posts.store') }}" id="store_posts" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type_post" id="type_post" value="posts">
        <div class="row">
            <div class="col-md-9">
                <div class="card mb-3">
                    <div class="card-header">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="content-id-tab" data-bs-toggle="tab" href="#content-id"
                                    role="tab" aria-controls="content-id" aria-selected="true">ID</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="content-en-tab" data-bs-toggle="tab" href="#content-en"
                                    role="tab" aria-controls="content-en" aria-selected="false">EN</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="postsTabContent">
                        <div class="tab-pane fade show active" id="content-id" role="tabpanel"
                            aria-labelledby="content-id-tab">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="post_category">Category (ID)</label>
                                    <select id="post_category" name="post_category[]" class="form-select" multiple>
                                        @foreach ($categoriesId as $category)
                                            <option value="{{ $category['translations']['id'] ?? '' }}">
                                                {{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="title_post_id">Title (ID)</label>
                                    <input type="text" id="title_post_id" name="title_post_id" class="form-control"
                                        placeholder="Title (ID)">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="slug_post_id">Slug (ID)</label>
                                    <input type="text" id="slug_post_id" name="slug_post_id" class="form-control"
                                        placeholder="Slug Url (ID)">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="content_id">Content (ID)</label>
                                    <textarea class="ckeditor" name="content_id" id="content_id" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="content-en" role="tabpanel" aria-labelledby="content-en-tab">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label" for="post_category_en">Category (EN)</label>
                                    <select id="post_category_en" name="post_category_en[]" class="form-select" multiple>
                                        @foreach ($categoriesEn as $category)
                                            <option value="{{ $category['translations']['en'] ?? '' }}">
                                                {{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="title_post_en">Title (EN)</label>
                                    <input type="text" id="title_post_en" name="title_post_en" class="form-control"
                                        placeholder="Title (EN)">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="slug_post_en">Slug (EN)</label>
                                    <input type="text" id="slug_post_en" name="slug_post_en" class="form-control"
                                        placeholder="Slug Url (EN)">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label" for="content_en">Content (EN)</label>
                                    <textarea class="ckeditor" name="content_en" id="content_en" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 p-3">
                    <div class="row g-3">
                        {{-- <div class="col-md-12">
                            <label class="form-label" for="post_status">Status</label>
                            <select id="post_status" name="post_status" class="form-select">
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div> --}}
                        <div class="col-md-12">
                            <label class="form-label">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="post_status"
                                    id="publish_immediately" value="publish" checked>
                                <label class="form-check-label" for="publish_immediately">Publish Immediately</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="post_status" id="schedule_publish"
                                    value="schedule">
                                <label class="form-check-label" for="schedule_publish">Schedule Publish</label>
                            </div>
                            <div id="schedule_publish_datetime" style="display: none;">
                                <label class="form-label" for="publish_datetime">Publish Date and Time</label>
                                <input type="text" id="publish_datetime" name="publish_datetime" class="form-control"
                                    placeholder="Select Date and Time">
                            </div>
                        </div>



                        <div class="col-md-12">
                            <label class="form-label" for="cover">Cover</label>
                            <div class="row mb-2">
                                <div class="col-auto">
                                    <img class="img-fluid rounded" id="modal-preview"
                                        src="https://dummyimage.com/1000x500/005e9d/fff.png"><br><br>
                                    <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                        <button class="btn-upload">Upload Cover</button>
                                        <input id="image" type="file" name="image" accept="image/*"
                                            onchange="readURL(this);">
                                    </div>
                                    <input type="hidden" name="hidden_image" id="hidden_image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="d-grid gap-2 col-lg-12 mx-auto">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Create</button>
                        <button class="btn btn-label-secondary waves-effect waves-light" type="button">Cancel</button>
                    </div>
                </div>
            </div>
    </form>
@endsection
@push('scripts')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif

    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('store_posts');
            const publishImmediately = document.getElementById('publish_immediately');
            const schedulePublish = document.getElementById('schedule_publish');
            const schedulePublishDatetime = document.getElementById('schedule_publish_datetime');

            function showHideDatetimePicker() {
                schedulePublishDatetime.style.display = schedulePublish.checked ? 'block' : 'none';
            }

            publishImmediately.addEventListener('change', function() {
                if (this.checked) {
                    showHideDatetimePicker();
                }
            });

            schedulePublish.addEventListener('change', function() {
                if (this.checked) {
                    showHideDatetimePicker();
                }
            });

            flatpickr(publish_datetime, {
                enableTime: true,
                dateFormat: "Y-m-d H:i:S",
                minDate: "today",
            });


            const titleIdInput = document.getElementById('title_post_id');
            const titleEnInput = document.getElementById('title_post_en');
            const slugIdInput = document.getElementById('slug_post_id');
            const slugEnInput = document.getElementById('slug_post_en');

            function updateSlug(inputValue, slugInput) {
                const slug = inputValue.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
                slugInput.value = slug;
            }

            titleIdInput.addEventListener('keyup', function() {
                updateSlug(this.value, slugIdInput);
            });

            titleEnInput.addEventListener('keyup', function() {
                updateSlug(this.value, slugEnInput);
            });
        });
    </script>

@endpush
