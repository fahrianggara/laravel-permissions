@extends('layouts.dashboard')

@section('title')
    Permission Labels
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
                                    <i class="fas fa-plus mr-1"></i> Add Label
                                </button>
                            @endcan
                        </div>
                    @endif

                    @if (count($labels) > 0)
                        <div class="card-body table-responsive p-3">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Permission Labels</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                @foreach ($labels as $label)
                                    <tbody>
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $label->title }}</td>
                                            <td>
                                                @if (auth()->user()->can('edit permission') || auth()->user()->can('delete permission'))
                                                    <div class="btn-group dropleft">
                                                        <button
                                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>

                                                        <div
                                                            class="dropdown-menu dashboard-dropdown dropdown-menu-start mr-3">
                                                            @can('edit permission')
                                                                <button value="{{ $label->id }}"
                                                                    class="edit_btn dropdown-item d-flex align-items-center">
                                                                    <i class="fas fa-pen text-warning"></i>
                                                                    <span class="ml-2"> Edit Label</span>
                                                                </button>
                                                            @endcan

                                                            @can('delete permission')
                                                                <button value="{{ $label->id }}"
                                                                    class="del_btn dropdown-item d-flex align-items-center mt-1"
                                                                    data-name="{{ $label->title }}">
                                                                    <i class="fas fa-trash text-danger"></i>
                                                                    <span class="ml-2"> Delete Label</span>
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

        {{-- Modal create --}}
        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="replayTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-centered">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replayTitle">Form - Label Add</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="formLabelAdd" action="{{ route('label-permissions.store') }}" autocomplete="off"
                        method="POST">
                        @csrf
                        @method('POST')

                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="title">Permission Label</label>
                                <input type="text" class="form-control" id="add_title" name="title"
                                    placeholder="Enter Permission Label name here!" autofocus>
                                <span class="invalid-feedback d-block error-text title_error"></span>
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
                        <h5 class="modal-title" id="modalEdit">Form - Label Edit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form id="formLabelEdit" action="#" autocomplete="off" method="PUT">
                        @csrf
                        @method('PUT')

                        <input type="hidden" id="edit_id">

                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="title">Name Permission Labels</label>
                                <input type="text" class="form-control" id="edit_title" name="title"
                                    placeholder="Enter label permission name here!" autofocus>
                                <span class="invalid-feedback d-block error-text edit_title_error"></span>
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

                    <form action="" method="DELETE" id="formLabelDelete">
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
                $('body').on('shown.bs.modal', '.modal', function() {
                    $(this).find(":input:not(:button):visible:enabled:not([readonly]):first").focus();
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#formLabelAdd').on('submit', function(e) {
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
                                $('#formLabelAdd')[0].reset();
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
                        url: "{{ url('dashboard/label-permissions') }}/" + id,
                        success: function(response) {
                            if (response.status == 200) {
                                $('#edit_id').val(id);
                                $('#edit_title').val(response.data.title);
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

                // UPDATE PERMISSION LABELS
                $('#formLabelEdit').on('submit', function(e) {
                    e.preventDefault();

                    let id = $('#edit_id').val();

                    $.ajax({
                        type: "PUT",
                        url: "{{ url('dashboard/label-permissions') }}/" + id,
                        data: {
                            "title": $('#edit_title').val(),
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
                                        $('#formLabelEdit')[0].reset();
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
                    $('#text_del').text('Are you sure? Want to delete the "' + name + '" permission label?');
                });

                // process deleting
                $("#formLabelDelete").on('submit', function(e) {
                    e.preventDefault();

                    let id = $('#del_id').val();

                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('dashboard/label-permissions') }}/" + id,
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
                                    msg = response.message
                                } else {
                                    msg = response.title
                                }

                                Swal.fire({
                                    icon: 'error',
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
