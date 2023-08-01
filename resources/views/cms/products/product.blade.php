@extends('layouts.cms')
@section('title', 'Insurance Products')
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
                    <li class="breadcrumb-item active">{{ $catName }} </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="table" class="table border-top bgnew">
                <thead>
                    <tr>
                        <th></th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        @php
                            $image_url = $product['images'] ? asset($product['images']) : asset('img/cms/avatars/no-thumbnail-medium.png');
                            $title = $product['title'];
                            if ($product['link']) {
                                $title = '<a href="' . $product['link'] . '" target="_blank">' . $title . '</a>';
                            }
                            $status = $product['status'] === 'publish' ? '<button type="button" class="btn btn-sm rounded-pill btn-label-success waves-effect waves-light">' . $product['status'] . '</button>' : '<button type="button" class="btn btn-sm rounded-pill btn-label-secondary waves-effect waves-light">' . $product['status'] . '</button>';
                            $created = \Carbon\Carbon::parse($product['date_gmt'])->format('d M Y H:i');
                            $updated = \Carbon\Carbon::parse($product['modified_gmt'])->format('d M Y H:i');
                        @endphp
                        <tr>
                            <td></td>
                            <td><img src="{{ $image_url }}" alt="thumbnail" class="img-thumbnail rounded"></td>
                            <td>{!! $title !!}</td>
                            <td>{{ implode(', ', $product['category_names']) }}</td>
                            <td>{!! $status !!}</td>
                            <td>{{ $created }}</td>
                            <td>{{ $updated }}</td>
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
        $(document).ready(function() {
            var catName = '{{ $catName }}'
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
                    text: '<span class="d-none d-sm-inline-block">New Insurance Product</span>',
                    className: 'btn rounded-pill btn-primary waves-effect waves-light',
                    action: function() {
                        window.location.href =
                            "{{ ENV('APP_URL') . '/dashboard/insurance-products/create' }}";
                    }
                }],

            });
            $('div.head-label').html('<h5 class="card-title mb-0">Insurance Products ' + catName + '</h5>');
        });
    </script>
@endpush
