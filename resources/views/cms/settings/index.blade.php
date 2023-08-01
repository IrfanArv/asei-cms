@extends('layouts.cms')
@section('title', 'Web Settings')
@section('content')
    <form method="POST" action="{{ route('updateWebSettings') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mb-4">
            <div class="col">
                <h4 class="fw-bold"><span class="text-muted fw-light">Dashboard/ Settings</span>/ Web Setting</h4>
            </div>
            <div class="col">
                <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light float-end">Update</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card pb-5 pt-3">
                    <div class="row g-3 p-3">
                        @foreach ($dataSettings as $item)
                            <input type="hidden" name="settings[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                            <div class="col-md-12">
                                <h6 class="mb-3 fw-semibold">{{ $item['title'] }}</h6>
                                @if (!empty($item['setting_value']))
                                    <label class="form-label">Title</label>
                                    <input type="text" name="settings[{{ $item['id'] }}][setting_value]"
                                        class="form-control" placeholder="Title" value="{{ $item['setting_value'] }}">
                                @endif

                                @if (!empty($item['link_value']))
                                    <label class="form-label">Link</label>
                                    <input type="text" name="settings[{{ $item['id'] }}][link_value]"
                                        class="form-control" placeholder="Link" value="{{ $item['link_value'] }}">
                                @endif
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
    </form>
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
@endpush
