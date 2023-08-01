@extends('layouts.cms')
@section('title', 'Tanggung Jawab Sosial')
@section('content')
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Tanggung Jawab Sosial </li>
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
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listData as $data)
                        @php
                            $image_url = $data['images'] ? asset($data['images']) : asset('img/cms/avatars/no-thumbnail-medium.png');
                            $title = $data['title'];
                            if ($data['link']) {
                                $title = '<a href="' . $data['link'] . '" target="_blank">' . $title . '</a>';
                            }
                            $created = \Carbon\Carbon::parse($data['date_gmt'])->format('d M Y H:i');
                            $status = $data['status'] === 'publish' ? '<button type="button" class="btn btn-sm rounded-pill btn-label-success waves-effect waves-light">' . $data['status'] . '</button>' : '<button type="button" class="btn btn-sm rounded-pill btn-label-secondary waves-effect waves-light">' . $data['status'] . '</button>';
                            $updated = \Carbon\Carbon::parse($data['modified_gmt'])->format('d M Y H:i');
                        @endphp
                        <tr>
                            <td></td>
                            <td><img src="{{ $image_url }}" alt="thumbnail" class="img-thumbnail rounded"></td>
                            <td>{!! $title !!}</td>
                            <td>{!! $status !!}</td>
                            <td>{{ $created }}</td>
                            <td>{{ $updated }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-label-primary dropdown-toggle waves-effect"
                                        data-bs-toggle="dropdown" aria-expanded="false">Details</button>
                                    <ul class="dropdown-menu" style="">
                                        <li><a class="dropdown-item"
                                                href="{{ ENV('APP_URL') . '/dashboard/web-pages/' . $data['id'] }}">ID</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ ENV('APP_URL') . '/dashboard/web-pages/' . $data['english'] }}">EN</a>
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
            var table = $('#table').DataTable({
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Page ID: ' + data['id'];
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
                displayLength: 25,
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>r',
                lengthMenu: [
                    [7, 10, 25, 50, 75, 100, -1],
                    [7, 10, 25, 50, 75, 100, "All"]
                ],
                buttons: [],
            });
            $('div.head-label').html('<h5 class="card-title mb-0">Tanggung Jawab Sosial</h5>');
        });
    </script>
@endpush
