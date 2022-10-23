@extends('layouts.dashboard')

@section('title')
    Users
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if (count($users) > 0)
                    <div class="card">
                        @if (auth()->user()->can('create user'))
                            <div class="card-header">
                                @can('create user')
                                    <button id="btnCreate" class="btn btn-info" data-toggle="modal" data-target="#modalCreate">
                                        <i class="fas fa-plus mr-1"></i> Add User
                                    </button>
                                @endcan
                            </div>
                        @endif
                        <div class="card-body table-responsive p-3">
                            <table id="tableUsers" class="table table-hover align-items-center overflow-hidden">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="d-flex align-items-center"
                                                    style="cursor: default">

                                                    @if (file_exists('assets/dashboard/img/users/' . $user->photo))
                                                        <img src="{{ asset('assets/dashboard/img/users/' . $user->photo) }}"
                                                            width="40" class="avatar rounded-circle me-3">
                                                    @else
                                                        <img src="{{ asset('assets/dashboard/img/avatar.png') }}"
                                                            width="40" class="avatar rounded-circle me-3">
                                                    @endif

                                                    <div class="d-block ml-3">
                                                        <span class="fw-bold name-user">{{ $user->name }}</span>
                                                        <div class="small text-secondary">
                                                            {{ $user->email ?? '(anonymous)' }}
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $user->phone ?? '==> EMPTY <==' }}
                                            </td>
                                            <td>
                                                {{ $user->roles->first()->name }}
                                            </td>
                                            <td>
                                                @if (auth()->user()->can('edit user') ||
                                                    auth()->user()->can('delete user'))
                                                    {{-- Selain dari role superadmin, bisa diedit --}}
                                                    @if (!$user->superadminRole())
                                                        <div class="btn-group dropleft">
                                                            <button
                                                                class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div
                                                                class="dropdown-menu dashboard-dropdown dropdown-menu-start mr-3 mb-3">
                                                                @can('edit user')
                                                                    <button value="{{ $user->id }}"
                                                                        class="dropdown-item d-flex align-items-center edit_btn">
                                                                        <i class="fas fa-pen text-warning"></i>
                                                                        <span class="ml-2">Edit User</span>
                                                                    </button>
                                                                @endcan

                                                                @can('delete user')
                                                                    <button value="{{ $user->id }}"
                                                                        data-name="{{ $user->name }}"
                                                                        class="dropdown-item d-flex align-items-center mt-1 del_btn">
                                                                        <i class="fas fa-trash text-danger"></i>
                                                                        <span class="ml-2">Delete User</span>
                                                                    </button>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-white text-center" role="alert">
                        Oops, Nothing Here!
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal create --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreate" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-centered">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreate">Form - Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formAddUser" action="{{ route('users.store') }}" autocomplete="off" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-body m-0">
                                <div class="form-group">
                                    <label for="title">Name</label>
                                    <input type="text" class="form-control" id="add_name" name="name"
                                        placeholder="Enter user name here!" autofocus>
                                    <span class="invalid-feedback d-block error-text name_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="title">Email</label>
                                    <input type="email" class="form-control" id="add_email" name="email"
                                        placeholder="Enter user email here!">
                                    <span class="invalid-feedback d-block error-text email_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="title">Phone</label>
                                    <input type="text" class="form-control" id="add_phone" name="phone"
                                        placeholder="Enter user phone here!">
                                    <span class="invalid-feedback d-block error-text phone_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input">
                                        <label class="custom-file-label" id="add_photo" for="photo">
                                            Search photo user
                                        </label>
                                    </div>
                                    <span class="invalid-feedback d-block error-text photo_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="modal-body m-0">
                                <div class="form-group">
                                    <label for="title">Role</label>
                                    <select name="role" id="add_role" class="form-control">
                                        <option value="#" selected disabled>Choose Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback d-block error-text role_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="title">New Password</label>
                                    <input type="password" class="form-control" id="add_password" name="password"
                                        placeholder="Enter new Password here!" autofocus>
                                    <span class="invalid-feedback d-block error-text password_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Confirm Password</label>
                                    <input type="password" class="form-control" id="add_password_confirmation"
                                        name="password_confirmation" placeholder="Enter confirm Password here!" autofocus>
                                    <span class="invalid-feedback d-block error-text password_confirmation_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="submitAdd btn btn-info">
                            Add <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-centered">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEdit">Form - Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formEditUser" action="{{ route('users.store') }}" autocomplete="off" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')


                    <div class="row">
                        <div class="col-lg-6">
                            <div class="modal-body m-0">

                                <input type="hidden" id="edit_id">

                                <div class="form-group">
                                    <label for="title">Name</label>
                                    <input type="text" class="form-control" id="edit_name" name="name"
                                        placeholder="Enter user name here!" autofocus>
                                    <span class="invalid-feedback d-block error-text edit_name_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="title">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email"
                                        placeholder="Enter user email here!" readonly>
                                    <span class="invalid-feedback d-block error-text edit_email_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="title">Phone</label>
                                    <input type="text" class="form-control" id="edit_phone" name="phone"
                                        placeholder="Enter user phone here!">
                                    <span class="invalid-feedback d-block error-text edit_phone_error"></span>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="modal-body m-0">
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <div class="custom-file">
                                        <input type="file" name="photo" class="custom-file-input">
                                        <label class="custom-file-label" id="edit_photo" for="photo">
                                            Search photo user
                                        </label>
                                    </div>
                                    <span class="invalid-feedback d-block error-text edit_photo_error"></span>
                                </div>

                                <div class="form-group">
                                    <label for="title">Role</label>
                                    <select name="role" id="edit_role" class="form-control">
                                        <option value="#" selected disabled>Choose Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback d-block error-text edit_role_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="submitEdit btn btn-warning">
                            Edit <i class="fas fa-pen"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Modal delete --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">

                <form action="" method="DELETE" id="formUserDelete">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">
                        <input id="del_id" type="hidden" name="id">
                        <p id="text_del"></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger btnDelete">
                            Delete <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find(":input:not(:button):visible:enabled:not([readonly]):first").focus();
            });

            // Show name file
            $(document).on('change', 'input[type="file"]', function(event) {
                let fileName = $(this).val();

                if (fileName == undefined || fileName == "") {
                    $(this).next('.custom-file-label').html('No image selected..')
                } else {
                    $(this).next('.custom-file-label').html(event.target.files[0].name);
                }
            });

            $('#formAddUser').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        $('.submitAdd').attr('disabled', true);
                        $('.submitAdd').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('.form-control').removeClass(
                            'is-invalid');
                    },
                    complete: function() {
                        $('.submitAdd').removeAttr('disabled');
                        $('.submitAdd').html('Add <i class="fas fa-plus"></i>');
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            $.each(response.message, function(key, val) {
                                $('span.' + key + '_error').text(val[0]);
                                $("#add_" + key).addClass('is-invalid');
                            });
                        } else if (response.status == 401) {
                            Swal.fire({
                                icon: 'error',
                                title: response.title,
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        } else {

                            $('#formAddUser')[0].reset();
                            $('#modalCreate').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "You doesn't have permission to access this!",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            $('.edit_btn').on('click', function(e) {
                e.preventDefault();

                let id = $(this).val();
                $('#modalEdit').modal('show');

                $.ajax({
                    type: "GET",
                    url: "{{ url('dashboard/users') }}/" + id,
                    success: function(response) {
                        $('#edit_id').val(id);
                        $('#edit_name').val(response.data.name)
                        $('#edit_email').val(response.data.email)
                        $('#edit_phone').val(response.data.phone == null ? '==> EMPTY <==' :
                            response.data.phone)
                        $('#edit_photo').html(response.data.photo)
                        $('#edit_role').val(response.data.roles[0].id).trigger('change');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });

            $("#formEditUser").on("submit", function(e) {
                e.preventDefault();

                let id = $('#edit_id').val();

                $.ajax({
                    url: "{{ url('dashboard/users/') }}/" + id + "",
                    type: $(this).attr('method'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('.submitEdit').attr('disabled', true);
                        $('.submitEdit').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('input.form-control').removeClass('is-invalid');
                    },
                    complete: function() {
                        $('.submitEdit').removeAttr('disabled');
                        $('.submitEdit').html('Edit <i class="fas fa-pen"></i>');
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            $.each(response.message, function(key, val) {
                                $('span.edit_' + key + '_error').text(val[0]);
                                $("#edit_" + key).addClass('is-invalid');
                            });
                        } else if (response.status == 401) {
                            Swal.fire({
                                icon: 'error',
                                title: response.title,
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        } else {
                            $('#modalEdit').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "You doesn't have permission to access this!",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            $(document).on('click', '.del_btn', function(e) {
                e.preventDefault();

                let id = $(this).val();
                let name = $(this).data('name');

                $('#modalDelete').modal('show');
                $('#del_id').val(id);
                $('#text_del').text("Do you want to delete " + name + " user?");
            });

            $("#formUserDelete").on('submit', function(e) {
                e.preventDefault();

                let id = $('#del_id').val();

                $.ajax({
                    type: "DELETE",
                    url: "{{ url('dashboard/users') }}/" + id,
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function() {
                        $('.btnDelete').attr('disabled', true);
                        $('.btnDelete').html('<i class="fas fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.btnDelete').removeAttr('disabled');
                        $('.btnDelete').html('Delete <i class="fas fa-trash"></i>');
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            $('#modalDelete').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.title,
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });
        });
    </script>
@endpush
