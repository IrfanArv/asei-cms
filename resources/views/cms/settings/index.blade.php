@extends('layouts.cms')
@section('title', 'Web Settings')
@section('content')
    <form method="POST" action="{{ route('updateWebSettings') }}" enctype="multipart/form-data">
        @csrf
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ ENV('APP_URL') . '/dashboard' }}">Pengaturan</a>
                        </li>
                        <li class="breadcrumb-item active">Web Setting</li>
                    </ol>
                </nav>
            </div>
            <div class="col">
                <button type="submit"
                    class="btn rounded-pill btn-primary waves-effect waves-light float-end">Update</button>
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
                                @if (!empty($item['name']))
                                    <label class="form-label">Name</label>
                                    <input type="text" name="settings[{{ $item['id'] }}][name]" class="form-control"
                                        placeholder="Name" value="{{ $item['name'] }}">
                                @endif

                                @if (!empty($item['link_value']))
                                    <label class="form-label">Link</label>
                                    <input type="text" name="settings[{{ $item['id'] }}][link_value]"
                                        class="form-control" placeholder="Link" value="{{ $item['link_value'] }}">
                                @endif
                                {{-- @if (!empty($item['images']))
                                    <label class="form-label mt-3">Image</label>
                                    <input class="form-control" type="file" id="formFile"
                                        name="settings[{{ $item['id'] }}][image]" accept="image/*">
                                    <img class="img-fluid mt-3" id="modal-preview" src="{{ $item['images'] }}">
                                @endif --}}
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
