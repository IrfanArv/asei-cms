@extends('layouts.cms')
@section('title', 'Produk Asuransi')
@section('content')

    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Produk Asuransi </li>
                </ol>
            </nav>
        </div>
        {{-- <div class="col">
            <a href="#"
                class="btn rounded-pill btn-primary waves-effect waves-light float-end create-category-insurance">Tambah
                Kategori Asuransi</a>
        </div> --}}
    </div>

    <div class="row mb-5">
        @foreach ($categories as $insuraces)
            <div class="col-md-6">
                <div class="card mb-3">
                    @php
                        $image_url = $insuraces['image_url'] ? asset($insuraces['image_url']) : asset('img/cms/avatars/no-thumbnail-medium.png');
                        $description = Str::limit($insuraces['description'], 60);
                    @endphp
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img class="card-img card-img-left image-product" src="{{ $image_url }}"
                                alt="{{ $insuraces['name'] }}">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $insuraces['name'] }}</h5>
                                <p class="card-text">
                                    {{ $description }}
                                </p>
                                <a href="{{ ENV('APP_URL') . '/dashboard/insurance/products/' . $insuraces['id'] }}"
                                    class="btn btn-outline-primary waves-effect">Produk

                                </a>
                                <a href="{{ ENV('APP_URL') . '/dashboard/insurance/contents/' . $insuraces['id'] }}"
                                    class="btn btn-outline-secondary waves-effect">Konten Halaman</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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
    <script>
        // $(document).ready(function() {
        //     $('body').on('click', '.create-category-insurance', function() {
        //         $('#titles-modal').html('Tambah Kategori Asuransi');
        //         $('#modalCreateCategory').modal('show');
        //         $('#category_type').val('insurance-category');
        //         $('#section_image_div').show();
        //     });
        // });
    </script>
@endpush
