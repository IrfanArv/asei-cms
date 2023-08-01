@extends('layouts.cms')
@section('title', 'Networks')
@section('content')

    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="table" class="table border-top bgnew">
                <thead>
                    <tr>
                        <th></th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Head Office</th>
                        <th>Link Maps</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Network ID: ' + data['id'];
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
                    url: "{{ route('network.data') }}",
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
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'alamat_lengkap',
                        name: 'alamat_lengkap'
                    },
                    {
                        data: 'kantor_pusat',
                        name: 'kantor_pusat'
                    },
                    {
                        data: 'maps_url',
                        name: 'maps_url'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                    text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Network</span>',
                    className: 'btn btn-primary',
                    action: function() {
                        window.location.href =
                            "{{ ENV('APP_URL') . '/dashboard' }}";
                    }
                }],

            });
            $('div.head-label').html('<h5 class="card-title mb-0">Networks</h5>');
        });
    </script>
@endpush
