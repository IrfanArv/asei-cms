@extends('layouts.cms')
@section('title', 'Insurance')
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
        <div class="col">
            <a href="#" class="btn rounded-pill btn-primary waves-effect waves-light float-end">New Insurance</a>
        </div>
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

    {{-- <div class="card">
        <div class="card-datatable table-responsive">
            <table id="table" class="table border-top bgnew">
                <thead>
                    <tr>
                        <th></th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div> --}}
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
    {{--
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Insurance ID: ' + data['id'];
                            }
                        }),
                        type: 'column',
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.title !== '' ?
                                    '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('insurance.products.data') }}",
                    error: function(data, error, thrown) {
                        toastr.error(data.responseJSON.error, '', {
                            positionClass: 'toast-top-center',
                            toastClass: 'toastr-center',
                        });
                    }
                },
                columns: [{
                        data: ''
                    },
                    {
                        data: 'images',
                        name: 'images'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'category_names',
                        name: 'category_names'
                    },
                    {
                        data: 'date_gmt',
                        name: 'date_gmt'
                    },
                    {
                        data: 'modified_gmt',
                        name: 'modified_gmt'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                columnDefs: [

                    {
                        className: 'control',
                        orderable: false,
                        searchable: false,
                        responsivePriority: 2,
                        targets: 0,
                        render: function(data, type, full, meta) {
                            return '';
                        }
                    },
                ],
                displayLength: 7,
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>r',
                lengthMenu: [
                    [7, 10, 25, 50, 75, 100, -1],
                    [7, 10, 25, 50, 75, 100, "All"]
                ],
                buttons: [{
                    text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Insurance Product</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        window.location.href =
                            "{{ ENV('APP_URL') . '/dashboard/insurance-products/create' }}";
                    }
                }],

            });
            $('div.head-label').html('<h5 class="card-title mb-0">Insurance Products</h5>');
        });
    </script> --}}
@endpush
