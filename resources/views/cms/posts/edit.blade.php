@extends('layouts.cms')
@section('title', 'Edit Berita ' . $post['title'])
@section('content')

    <form method="POST" action="{{ route('posts.store') }}" id="store_posts" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type_post" id="type_post" value="posts">
        <div class="row">
            <div class="col-md-9">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-3">Edit Berita - {{ $post['title'] }}</h5>
                    </div>
                    <div class="row g-3 px-3">
                        <div class="col-md-12">
                            <label class="form-label" for="post_category">Category</label>
                            <select id="post_category" name="post_category[]" class="form-select" multiple>
                                @foreach ($categories as $category)
                                    @php $selected = in_array($category['id'], $post['category_ids']) ? 'selected' : ''; @endphp
                                    <option value="{{ $category['id'] }}" {{ $selected }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="title_post">Title </label>
                            <input type="text" id="title_post" name="title_post" class="form-control"
                                placeholder="Title (ID)" value="{{ $post['title'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="slug_post">Slug</label>
                            <input type="text" id="slug_post" name="slug_post" class="form-control"
                                placeholder="Slug Url" value="{{ $post['slug'] }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="content">Content</label>
                            <textarea class="ckeditor" name="content" id="content" cols="30" rows="10">{{ $post['content'] }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-3 p-3">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Status</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="post_status" id="draft"
                                    value="draft" @if ($post['status'] === 'draft') checked @endif>
                                <label class="form-check-label" for="draft">Draft</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="post_status" id="publish_immediately"
                                    value="publish" @if ($post['status'] === 'publish') checked @endif>
                                <label class="form-check-label" for="publish_immediately">Publish</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="post_status" id="schedule_publish"
                                    value="schedule" @if ($post['status'] === 'schedule') checked @endif>
                                <label class="form-check-label" for="schedule_publish">Scheduled</label>
                            </div>
                            <div id="schedule_publish_datetime" style="display: none;">
                                <label class="form-label mt-2" for="publish_datetime">Set date and time</label>
                                <input type="text" id="publish_datetime" name="publish_datetime" class="form-control"
                                    placeholder="Select Date and Time" value="{{ $post['date_gmt'] }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="cover">Cover</label>
                            <div class="row mb-2">
                                <div class="col-auto">
                                    @if (!empty($post['images']))
                                        <img class="img-fluid rounded" id="modal-preview"
                                            src="{{ $post['images'] }}"><br><br>
                                    @else
                                        <img class="img-fluid rounded" id="modal-preview"
                                            src="https://dummyimage.com/1000x500/005e9d/fff.png"><br><br>
                                    @endif
                                    <div class="upload-btn-wrapper d-flex justify-content-center align-self-center">
                                        <button class="btn-upload">Change Cover</button>
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
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
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


            const titleIdInput = document.getElementById('title_post');
            const slugIdInput = document.getElementById('slug_post');

            function updateSlug(inputValue, slugInput) {
                const slug = inputValue.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
                slugInput.value = slug;
            }

            titleIdInput.addEventListener('keyup', function() {
                updateSlug(this.value, slugIdInput);
            });
        });
    </script>

@endpush
