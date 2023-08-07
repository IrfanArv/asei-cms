@extends('layouts.cms')
@section('title', 'Penghargaan')
@section('content')
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ ENV('APP_URL') . '/dashboard' }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Penghargaan & Link </li>
                    <li class="breadcrumb-item active">Penghargaan </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="table" class="table border-top bgnew">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Rewards</th>
                        <th>Publish At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataAward as $item)
                        @php
                            $updated = \Carbon\Carbon::parse($item['modified_gmt'])->format('d M Y H:i');
                        @endphp
                        <tr>
                            <td>{!! $item['title'] !!}</td>
                            <td>{{ $item['rewards'] }}</td>
                            <td>{{ $updated }}</td>
                            <td>
                                <div class="d-inline-block">
                                    <button type="button" class="btn btn-sm btn-icon awards-edit">
                                        <i class="text-warning ti ti-pencil"></i></button>
                                    <button type="button" class="btn btn-sm btn-icon awards-delete">
                                        <i class="text-danger ti ti-trash"></i>
                                    </button>

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
                processing: true,

                displayLength: 25,
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>r',
                lengthMenu: [
                    [7, 10, 25, 50, 75, 100, -1],
                    [7, 10, 25, 50, 75, 100, "All"]
                ],
                buttons: [

                    {
                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New</span>',
                        className: 'btn btn-primary create-penghargaan',
                    }
                ],
            });
            $('div.head-label').html('<h5 class="card-title mb-0">List Awards</h5>');
        });
    </script>
@endpush
