@extends('layouts.dashboard')

@section('title')
    Roles
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @if (count($roles) > 0)
                    <div class="card">

                        @if (auth()->user()->can('create role'))
                            <div class="card-header">
                                @can('create role')
                                    <button id="btnCreate" class="btn btn-info" data-toggle="modal" data-target="#modalCreate">
                                        <i class="fas fa-plus mr-1"></i> Add Role
                                    </button>
                                @endcan
                            </div>
                        @endif

                        <div class="card-body table-responsive p-3">

                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Roles Name</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                @foreach ($roles as $role)
                                    <tbody>
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @if (auth()->user()->can('edit role') ||
                                                    auth()->user()->can('delete role'))
                                                    <div class="btn-group dropleft">
                                                        <button
                                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div
                                                            class="dropdown-menu dashboard-dropdown dropdown-menu-start mr-3">
                                                            @can('edit role')
                                                                <button value="{{ $role->id }}"
                                                                    class="dropdown-item d-flex align-items-center edit_btn">
                                                                    <i class="fas fa-pen text-warning"></i>
                                                                    <span class="ml-2">Edit Role</span>
                                                                </button>
                                                            @endcan

                                                            @can('delete role')
                                                                <button value="{{ $role->id }}"
                                                                    data-name="{{ $role->name }}"
                                                                    class="dropdown-item d-flex align-items-center mt-1 del_btn">
                                                                    <i class="fas fa-trash text-danger"></i>
                                                                    <span class="ml-2">Delete Role</span>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach

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
                    <h5 class="modal-title" id="modalCreate">Form - Role Add</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formRoleAdd" action="{{ route('roles.store') }}" autocomplete="off" method="POST">
                    @csrf
                    @method('POST')

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="title">Role Name</label>
                            <input type="text" class="form-control" id="add_name" name="name"
                                placeholder="Enter role name here!" autofocus>
                            <span class="invalid-feedback d-block error-text name_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title" class="d-flex">
                                Give Permissions
                                <input type="checkbox" value="" class="ml-1" id="checkAllAdd">
                            </label>
                            <div class="form-control h-100" id="add_permissions">
                                <div class="row" style="margin-left: -9px;margin-bottom: 4px">
                                    @foreach ($permissions as $permission)
                                        <ul class="list-group mx-1">
                                            <li class="list-group-item mt-1 bg-info text-white">
                                                {{ $permission->title }}
                                            </li>

                                            @foreach ($permission->labelPermissions as $item)
                                                <li class="list-group-item">
                                                    <div class="form-check">

                                                        <input id="add{{ $item->id }}" name="permissions[]"
                                                            class="form-check-input checkPermissionAdd" type="checkbox"
                                                            value="{{ $item->id }}">

                                                        <label for="add{{ $item->id }}" class="form-check-label checks">
                                                            {{ $item->name }}
                                                        </label>

                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                            <span class="invalid-feedback d-block error-text permissions_error"></span>
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

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-centered">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEdit">Form - Role Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formRoleEdit" action="#" autocomplete="off" method="PUT">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="role_id">

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="title">Role Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name"
                                placeholder="Enter role name here!" autofocus>
                            <span class="invalid-feedback d-block error-text edit_name_error"></span>
                        </div>
                        <div id="editPermissions" class="form-group mb-3">
                            <label for="title" class="d-flex">
                                Give Permissions
                                <input type="checkbox" value="" class="ml-1" id="checkAllEdit">
                            </label>

                            <div class="form-control h-100" id="edit_permissions">
                                <div class="row" style="margin-left: -9px;margin-bottom: 4px">
                                    @foreach ($permissions as $permission)
                                        <ul class="list-group mx-1">
                                            <li class="list-group-item mt-1 bg-info text-white">
                                                {{ $permission->title }}
                                            </li>

                                            @foreach ($permission->labelPermissions as $item)
                                                <li class="list-group-item">
                                                    <div class="form-check">

                                                        <input id="edit{{ $item->id }}" name="permissions[]"
                                                            class="form-check-input checkPermissionEdit" type="checkbox"
                                                            value="{{ $item->id }}">

                                                        <label for="edit{{ $item->id }}" class="form-check-label checks">
                                                            {{ $item->name }}
                                                        </label>

                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                            </div>

                            <span class="invalid-feedback d-block error-text edit_permissions_error"></span>
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

                <form action="" method="DELETE" id="formRoleDelete">
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
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find(":input:not(:button):visible:enabled:not([readonly]):first").focus();
            });

            // ADD
            $('#checkAllAdd').click(function(e) {
                if ($(this).is(':checked')) {
                    $('.checkPermissionAdd').prop('checked', true);
                } else {
                    $('.checkPermissionAdd').prop('checked', false);
                }
            });
            $('.checkPermissionAdd').click(function(e) {
                let checkedLength = $('.checkPermissionAdd').filter(':checked').length;
                let checkedCount = $('input:checkbox[name="permissions"]:checked').length;

                if (checkedLength == 0) {
                    if ($('#checkAllAdd').is(':checked')) {
                        $('#checkAllAdd').prop('checked', false);
                    }
                } else if (checkedLength == $('.checkPermissionAdd').length) {
                    if (!$('#checkAllAdd').is(':checked')) {
                        $('#checkAllAdd').prop('checked', true);
                    }
                }
            });

            // INSERT TO DATABASE WITH AJAX
            $('#formRoleAdd').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    beforeSend: function() {
                        $('.submitAdd').attr('disabled', true);
                        $('.submitAdd').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('input.form-control').removeClass(
                            'is-invalid');
                    },
                    complete: function() {
                        $('.submitAdd').removeAttr('disabled');
                        $('.submitAdd').html('Add <i class="fas fa-plus"></i>');
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            $.each(response.errors, function(key, val) {
                                $('span.' + key + '_error').text(val[0]);
                                $(".form-control#add_" + key).addClass('is-invalid');
                            });
                        } else {
                            $('#formRoleAdd')[0].reset();
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

            // SHOW MODAL EDIT
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();

                let id = $(this).val();
                $("#modalEdit").modal('show');

                $.ajax({
                    type: "GET",
                    url: "{{ url('dashboard/roles') }}/" + id,
                    success: function(response) {
                        if (response.status == 200) {
                            $('#role_id').val(id);
                            $('#edit_name').val(response.data.name);

                            $.ajax({
                                type: "GET",
                                url: "{{ url('dashboard/fetch-edit-permission/') }}/" + id,
                                beforeSend: function() {
                                    $(".checkPermissionEdit").remove();
                                    $('.form-check.show_edit').prepend(
                                        '<i class="fas fa-spin fa-spinner mr-1"></i>'
                                    );
                                },
                                success: function(response) {
                                    $('#edit_permissions').html(response);

                                    $('#checkAllEdit').click(function(e) {
                                        if ($(this).is(':checked')) {
                                            $('.checkPermissionEdit').prop(
                                                'checked', true);
                                        } else {
                                            $('.checkPermissionEdit').prop(
                                                'checked', false);
                                        }
                                    });

                                }
                            });

                        } else {
                            $("#modalEdit").modal('hide');
                            $(document).find('span.error-text').text('');
                            $(document).find('input.form-control').removeClass(
                                'is-invalid');

                            Swal.fire({
                                icon: 'warning',
                                html: response.message,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });

            // UPDATE ROLE
            $('#formRoleEdit').on('submit', function(e) {
                e.preventDefault();

                let id = $('#role_id').val();

                let permissions = [];
                $('.checkPermissionEdit').each(function() {
                    if ($(this).is(':checked')) {
                        permissions.push($(this).val());
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "{{ url('dashboard/roles') }}/" + id,
                    data: {
                        "name": $('#edit_name').val(),
                        "permissions": permissions,
                        "_token": "{{ csrf_token() }}",
                    },
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('.submitEdit').attr('disabled', true);
                        $('.submitEdit').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('input.form-control').removeClass(
                            'is-invalid');
                    },
                    complete: function() {
                        $('.submitEdit').removeAttr('disabled');
                        $('.submitEdit').html('Edit <i class="fas fa-pen"></i>');
                    },
                    success: function(response) {

                        if (response.status == 200) {
                            Swal.fire({
                                icon: 'success',
                                html: response.message,
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#modalEdit').modal('hide');
                                    window.location.reload();
                                }
                            });
                        } else if (response.status == 201) {
                            $('#modalEdit').modal('hide');
                            Swal.fire({
                                icon: 'warning',
                                html: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (response.status == 400) {
                            $.each(response.errors, function(key, val) {
                                $('span.edit_' + key + '_error').text(val[0]);
                                $(".form-control#edit_" + key).addClass('is-invalid');
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

            // Show Delete
            $(document).on('click', '.del_btn', function(e) {
                e.preventDefault();

                let id = $(this).val();
                let name = $(this).data('name');
                $('#modalDelete').modal('show');
                $('#del_id').val(id);
                $('#text_del').text('Do you want to delete the ' + name + ' role?');
            });

            // process deleting
            $("#formRoleDelete").on('submit', function(e) {
                e.preventDefault();

                let id = $('#del_id').val();

                $.ajax({
                    type: "DELETE",
                    url: "{{ url('dashboard/roles') }}/" + id,
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}",
                    },
                    dataType: "json",
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
                            $('#modalDelete').modal('hide');

                            if (response.title == null) {
                                msg = ''
                            } else {
                                msg = response.title
                            }

                            Swal.fire({
                                icon: 'error',
                                title: msg,
                                html: response.message,
                                allowOutsideClick: false,
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
        });
    </script>
@endpush
