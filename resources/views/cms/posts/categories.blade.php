@extends('layouts.cms')
@section('title', 'News & Event Categories')
@section('content')

    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="table" class="table border-top bgnew">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>News & Events</th>
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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('news.category.data') }}",
                    error: function(data, error, thrown) {
                        toastr.error(data.responseJSON.error, '', {
                            positionClass: 'toast-top-center',
                            toastClass: 'toastr-center',
                            onHidden: function() {
                                window.location.href = "{{ route('dashboard') }}";
                            }
                        });
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                displayLength: 7,
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>r',
                lengthMenu: [
                    [7, 10, 25, 50, 75, 100, -1],
                    [7, 10, 25, 50, 75, 100, "All"]
                ],
                buttons: [

                    {
                        text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">New Category</span>',
                        className: 'btn btn-primary create-category-post'
                    }
                ],

            });
            $('div.head-label').html('<h5 class="card-title mb-0">News & Event Categories</h5>');

            // SHOW MODAL
            $('body').on('click', '.create-category-post', function() {
                $('#titles-modal').html('Create Category News & Event');
                $('#modalCreateCategory').modal('show');
                $('#category_type').val('categories');
            });
            // SUBMIT DATA EVENT CREATE NEW CATEGORY
            $('body').on('submit', '#formCreateCategory', function(e) {
                e.preventDefault();
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: SITEURL + "/dashboard/store-category",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modals-loading').show();
                        $('.is-invalid').removeClass('is-invalid');
                        $('.error').html('');
                        $('#modalCreateCategory').modal('hide');
                    },
                    success: (data) => {
                        $('#modals-loading').hide();
                        $('#formCreateCategory').trigger("reset");
                        $('#modalCreateCategory').modal('hide');
                        $('#btn-save').html('Save Changes');
                        table.ajax.reload();
                        if (data.success) {
                            toastr.success(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        } else {
                            toastr.error(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    },
                    error: function(data) {
                        // console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                        $('#modals-loading').hide();
                        if (data.responseJSON.success === false) {
                            var errorMessage = data.responseJSON.message ||
                                'Wopps! Errors';
                            toastr.error(errorMessage, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        } else {
                            toastr.error('Wopps! Errors', {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    }
                });
            });

            // GET DATA BY ID
            $('body').on('click', '.edit-category', function() {
                var catId = $(this).data('id');
                var catName = $(this).data('name');
                var catType = $(this).data('type-category');

                $.ajax({
                    type: "get",
                    url: SITEURL + "/dashboard/store-category/" + catId + '/' + catType,
                    beforeSend: function() {
                        $('#modals-loading').show();
                    },
                    success: function(data) {
                        $('#modals-loading').hide();
                        $('#modalEditCategory').modal('show');
                        $('#titles-modal-edit').html(
                            `Edit Category ${catName} (${data.data.lang})`);
                        $('#cat_id_edit').val(catId);
                        $('#category_type_edit').val(catType);
                        $('#name').val(data.data.name);
                        $('#slug').val(data.data.slug);
                        $('#description').val(data.data.description);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        toastr.error('Wopps! Errors', {
                            positionClass: 'toast-top-center',
                            toastClass: 'toastr-center',
                        });
                    }
                });
            });

            // SUBMIT DATA EVENT UPDATE CATEGORY
            $('body').on('submit', '#formUpdateCategory', function(e) {
                e.preventDefault();
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: SITEURL + "/dashboard/put-category",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modals-loading').show();
                        $('.is-invalid').removeClass('is-invalid');
                        $('.error').html('');
                        $('#modalEditCategory').modal('hide');
                    },
                    success: (data) => {
                        $('#modals-loading').hide();
                        $('#formUpdateCategory').trigger("reset");
                        $('#modalEditCategory').modal('hide');
                        $('#btn-save').html('Save Changes');
                        table.ajax.reload();
                        if (data.success) {
                            toastr.success(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        } else {
                            toastr.error(data.message, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                        $('#modals-loading').hide();
                        if (data.responseJSON.success === false) {
                            var errorMessage = data.responseJSON.message ||
                                'Wopps! Errors';
                            toastr.error(errorMessage, {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        } else {
                            toastr.error('Wopps! Errors', {
                                positionClass: 'toast-top-center',
                                toastClass: 'toastr-center',
                            });
                        }
                    }
                });
            });

            // DELETE DATA
            $('body').on('click', '.category-delete', function() {
                var catId = $(this).data('id');
                var catIdEn = $(this).data('id_en');
                var catName = $(this).data('name');
                var catType = $(this).data('type-category');
                var text_data = 'Are you sure to delete category ' + ' ' + catName + ' ?';
                Swal.fire({
                    title: 'Delete Category',
                    text: text_data,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-1',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "DELETE",
                            url: SITEURL + "/dashboard/delete-category/" + catType + '/' +
                                catId + '/' + catIdEn,
                            dataType: "JSON",
                            beforeSend: function() {
                                $('#modals-loading').show();
                            },
                            success: function(data) {
                                $('#modals-loading').hide();
                                if (data.success == true) {
                                    table.ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'Category has been deleted.',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    })
                                }
                            },
                            error: function(data) {
                                $('#modals-loading').hide();
                                console.log('Error:', data);
                                if (data.responseJSON.success === false) {
                                    var errorMessage = data.responseJSON.message ||
                                        'Wopps! Errors';
                                    toastr.error(errorMessage, {
                                        positionClass: 'toast-top-center',
                                        toastClass: 'toastr-center',
                                    });
                                } else {
                                    toastr.error('Wopps! Errors', {
                                        positionClass: 'toast-top-center',
                                        toastClass: 'toastr-center',
                                    });
                                }
                            }

                        });
                    }
                });
            });

        });
    </script>
@endpush
