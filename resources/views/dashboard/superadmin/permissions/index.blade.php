@extends('layouts.dashboard')

@section('title')
    Permissions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    @if (auth()->user()->can('create permission'))
                        <div class="card-header">
                            @can('create permission')
                                <button id="btnCreate" class="btn btn-info" data-toggle="modal" data-target="#modalCreate">
                                    <i class="fas fa-plus mr-1"></i> Add Permission
                                </button>
                            @endcan
                        </div>
                    @endif

                    @if (count($labels) > 0)
                        <div class="form-control h-100" id="add_permissions">
                            <div class="row" style="margin-left: -9px;margin-bottom: 4px">
                                @foreach ($labels as $permission)
                                    <ul class="list-group mx-1">
                                        @php
                                            $bg = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'];
                                        @endphp
                                        <li class="list-group-item mt-1 {{ $bg[$loop->index] }} text-white">
                                            {{ $permission->title }}
                                        </li>

                                        @foreach ($permission->labelPermissions as $item)
                                            <li class="list-group-item">

                                                <label for="add{{ $item->id }}" class="form-check-label">
                                                    {{ $item->name }}
                                                </label>

                                                @if (auth()->user()->can('edit permission') ||
                                                    auth()->user()->can('delete permission'))
                                                    <div class="btn-group dropright float-right">
                                                        <button
                                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <div
                                                            class="dropdown-menu dashboard-dropdown dropdown-menu-start ml-3">
                                                            @can('edit permission')
                                                                <button value="{{ $item->id }}"
                                                                    class="edit_btn dropdown-item d-flex align-items-center">
                                                                    <i class="fas fa-pen text-warning"></i>
                                                                    <span class="ml-2"> Edit Permission</span>
                                                                </button>
                                                            @endcan

                                                            @can('delete permission')
                                                                <button value="{{ $item->id }}"
                                                                    class="del_btn dropdown-item d-flex align-items-center mt-1"
                                                                    data-name="{{ $item->name }}">
                                                                    <i class="fas fa-trash text-danger"></i>
                                                                    <span class="ml-2"> Delete Permission</span>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                @endif

                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="col-12 mt-3">
                            <div class="alert alert-warning text-white text-center" role="alert">
                                Oops, Nothing Here!
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal create --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="replayTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-centered">
                <div class="modal-header">
                    <h5 class="modal-title" id="replayTitle">Form - Permission Add</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formPermissionAdd" action="{{ route('permissions.store') }}" autocomplete="off" method="POST">
                    @csrf
                    @method('POST')

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="title">Permission Name</label>
                            <input type="text" class="form-control" id="add_name" name="name"
                                placeholder="Enter permission name here!" autofocus>
                            <span class="invalid-feedback d-block error-text name_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Permission Group</label>
                            <select name="label" id="add_label" class="form-control">
                                <option value="#" selected disabled>Choose Group</option>
                                @foreach ($labelcruds as $label)
                                    <option value="{{ $label->id }}">{{ $label->title }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback d-block error-text label_error"></span>
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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-centered">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEdit">Form - Permission Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formPermissionEdit" action="#" autocomplete="off" method="PUT">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="permission_id">

                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="title">Permission Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name"
                                placeholder="Enter permission name here!" autofocus>
                            <span class="invalid-feedback d-block error-text edit_name_error"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Permission Group</label>
                            <select name="label" id="edit_label" class="form-control">
                                <option value="#" selected disabled>Choose Group</option>
                                @foreach ($labelcruds as $label)
                                    <option value="{{ $label->id }}">
                                        {{ $label->title }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback d-block error-text label_error"></span>
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

                <form action="" method="DELETE" id="formPermissionDelete">
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
            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find(":input:not(:button):visible:enabled:not([readonly]):first").focus();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#formPermissionAdd').on('submit', function(e) {
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
                                $("input#add_" + key).addClass('is-invalid');
                            });
                        } else {
                            $('#formPermissionAdd')[0].reset();
                            $('#modalCreate').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                html: response.message,
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
                    url: "{{ url('dashboard/permissions') }}/" + id,
                    success: function(response) {
                        if (response.status == 200) {
                            $('#permission_id').val(id);
                            $('#edit_name').val(response.data.name);
                            $('#edit_label').val(response.data.label_permissions[0].id).trigger(
                                'change');
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

            // UPDATE PERMISSION
            $('#formPermissionEdit').on('submit', function(e) {
                e.preventDefault();

                let id = $('#permission_id').val();

                $.ajax({
                    type: "PUT",
                    url: "{{ url('dashboard/permissions') }}/" + id,
                    data: {
                        "name": $('#edit_name').val(),
                        "label": $('#edit_label').val(),
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
                                    $('#formPermissionEdit')[0].reset();
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
                                $("input#edit_" + key).addClass('is-invalid');
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
                $('#text_del').text('Do you want to delete the "' + name + '" permission?');
            });

            // process deleting
            $("#formPermissionDelete").on('submit', function(e) {
                e.preventDefault();

                let id = $('#del_id').val();

                $.ajax({
                    type: "DELETE",
                    url: "{{ url('dashboard/permissions') }}/" + id,
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
