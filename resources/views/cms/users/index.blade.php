@extends('layouts.cms')
@section('title', 'Admin Users')
@section('content')

    <div class="d-flex justify-content-between">
        <div>
            <h4 class="fw-bold mb-4">
                <span class="text-muted fw-light">Settings / Admin Users /</span> Users
            </h4>
        </div>
        <div>
            <button type="button" id="addUser" class="btn btn-outline-primary rounded-pill me-2">
                <span class="tf-icons ti-xs ti ti-plus me-1"></span>New User
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="admin-users-table" class="table border-top bgnew">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUser" aria-labelledby="offcanvasUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasUserLabel" class="offcanvas-title">
                </h5>
                <button type="button" class="btn-close text-reset" id="cancel" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                <form id="adminUserForm" name="adminUserForm" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="row mb-3 justify-content-center">
                        <div class="col-auto">
                            <img class="avatar-lg ms-3 rounded-circle" id="modal-preview"
                                src="https://via.placeholder.com/250"><br><br>
                            <div class="upload-btn-wrapper">
                                <button class="btn-upload">Upload Avatar</button>
                                <input id="image" type="file" name="image" accept="image/*"
                                    onchange="readURL(this);">
                            </div>
                            <input type="hidden" name="hidden_image" id="hidden_image">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Full Name" name="name"
                            aria-label="Full Name" />
                        <div class="invalid-feedback" id="name_error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" id="email" class="form-control" placeholder="your@mail.com"
                            aria-label="your@mail.com" name="email" />
                        <div class="invalid-feedback" id="email_error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Password"
                            aria-label="Password" name="password" />
                        <div class="invalid-feedback" id="password_error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="confirm_password">Password</label>
                        <input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password"
                            aria-label="Confirm Password" name="confirm_password" />
                        <div class="invalid-feedback" id="confirm_password_error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="roles">User Role</label>
                        {!! Form::select('roles[]', $roles, [], ['class' => 'form-select', 'id' => 'roles']) !!}
                        <div class="invalid-feedback" id="roles"></div>

                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit" id="btn-save" value="create">
                        Submit
                    </button>
                    <button type="button" id="cancel-submit" class="btn btn-label-secondary">
                        Cancel
                    </button>
                </form>
            </div>
        </div>


    </div>
@endsection
@push('scripts')
    <script>
        const cancelBtn = document.getElementById("cancel");
        const cancelSubmit = document.getElementById("cancel-submit");
        const offCanvas = document.getElementById("offcanvasUser");

        $(document).ready(function() {
            $("#addUser").click(function() {
                $('#adminUserForm').trigger("reset");
                offCanvas.classList.add("show");
                $('#offcanvasUserLabel').html("Add User");
                $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
                $('#name').val('');
                $('#email').val('');
                $('#password').val('');
                $('#confirm_password').val('');
            });

            var table = $('#admin-users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin-users.index') }}",
                columns: [{
                        data: 'user_image',
                        name: 'user_image'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                responsive: true

            });


            cancelBtn.addEventListener("click", function() {
                offCanvas.classList.remove("show");
                $('#adminUserForm').trigger("reset");
            });
            cancelSubmit.addEventListener("click", function() {
                offCanvas.classList.remove("show");
                $('#adminUserForm').trigger("reset");
            });

            $('body').on('click', '.edit-admin-user', function() {
                var user_id = $(this).data('id');
                $.ajax({
                    type: "get",
                    url: SITEURL + "/dashboard/admin-users/edit/" + user_id,
                    beforeSend: function() {
                        $('#modals-loading').show();
                    },
                    success: function(data) {
                        $('#modals-loading').hide();
                        $('#offcanvasUserLabel').html("Edit User");
                        $('#btn-save').val("edit-user");
                        offCanvas.classList.add("show");
                        $('#user_id').val(data.data.id);
                        $('#name').val(data.data.name);
                        $('#email').val(data.data.email);
                        $('#password').val('');
                        $('#confirm_password').val('');
                        var selectedRoles = Object.keys(data.userRole);
                        $('#roles').val(selectedRoles);
                        $('#modal-preview').attr('alt', 'No image available');
                        if (data.data.image) {
                            $('#modal-preview').attr('src', '{{ URL::to('/img/user') }}' + '/' +
                                data.data
                                .image);
                            $('#hidden_image').attr('src', '{{ URL::to('/img/user') }}' + '/' +
                                data.data
                                .image);
                        } else {
                            $('#modal-preview').attr('src',
                                'https://avatars.dicebear.com/api/adventurer/' +
                                data.data.name + '.svg');
                            $('#hidden_image').attr('src',
                                'https://avatars.dicebear.com/api/adventurer/' +
                                data.data.name + '.svg');

                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            });

            $('body').on('submit', '#adminUserForm', function(e) {
                e.preventDefault();
                var actionType = $('#btn-save').val();
                $('#btn-save').html('Sending..');
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: SITEURL + "/dashboard/admin-users/store",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#modals-loading').show();
                        $('.is-invalid').removeClass('is-invalid');
                        $('.error').html('');
                    },
                    success: (data) => {
                        $('#modals-loading').hide();
                        $('#adminUserForm').trigger("reset");
                        offCanvas.classList.remove("show");
                        $('#btn-save').html('Save Changes');
                        table.ajax.reload();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#btn-save').html('Save Changes');
                        $('#modals-loading').hide();
                        if (data.responseJSON && data.responseJSON.errors) {
                            var errors = data.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + '_error').html(value);
                            });
                        }
                    }
                });
            });

            $('body').on('click', '.delete-admin-user', function() {
                var user_id = $(this).data('id');
                var user_name = $(this).data('name');
                var text_data = 'Are you sure to delete Admin user ' + ' ' + user_name + ' ?';
                Swal.fire({
                    title: 'Delete User',
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
                            type: "GET",
                            url: SITEURL + "/dashboard/admin-users/destroy/" + user_id,
                            dataType: "JSON",
                            beforeSend: function() {
                                $('#modals-loading').show();
                            },
                            success: function(data) {
                                $('#modals-loading').hide();
                                if (data.status == true) {
                                    table.ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: 'Admin User has been deleted.',
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    })
                                }
                            },
                            error: function(data) {
                                $('#modals-loading').hide();
                                console.log('Error:', data);
                            }

                        });
                    }
                });
            });

        });
    </script>
@endpush
