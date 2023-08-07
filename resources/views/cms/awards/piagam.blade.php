@extends('layouts.cms')
@section('title', 'Piagam Penghargaan')
@section('content')
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Penghargaan & Link </li>
                    <li class="breadcrumb-item active">Piagam </li>
                </ol>
            </nav>
        </div>
        <div class="col text-end">
            <button type="button" class="btn rounded-pill btn-primary waves-effect waves-light">
                <span class="ti-xs ti ti-circle-plus me-1"></span>Upload Piagam
            </button>
        </div>
    </div>


    <div class="row">
        @foreach ($dataPiagam as $item)
            @php
                $image_url = $item['image_url'] ? asset($item['image_url']) : asset('img/cms/avatars/no-thumbnail-medium.png');
            @endphp
            <div class="col-md-3">
                <div class="card">
                    <img class="card-img-top" src="{{ $image_url }}" alt="Card image cap">
                    <div class="card-body">
                        <button type="button" class="btn rounded-pill btn-danger waves-effect waves-light">
                            <span class="ti-xs ti ti-trash me-1"></span>Delete
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
