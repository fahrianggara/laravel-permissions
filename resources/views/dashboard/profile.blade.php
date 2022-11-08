@extends('layouts.dashboard')

@section('title')
    {{ Auth::user()->name }} | Profile
@endsection

@push('css')
    <style>
        .preview {
            overflow: hidden;
            width: 160px;
            height: 160px;
            margin: 0 0 30px 0;
            border-radius: 50%;
            border: 1px solid #007bff;
        }

        .modal-lg {
            max-width: 1000px !important;
        }

        .cropper-modal {
            border-radius: 8px !important;
        }

        .profile-user-img {
            border: 3px solid #007bff !important;
            width: 120px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @php
                                if (!empty(Auth::user()->photo) && Auth::user()->photo != '' && file_exists('assets/dashboard/img/users/' . Auth::user()->photo)) {
                                    $avatar = asset('assets/dashboard/img/users/' . Auth::user()->photo);
                                } else {
                                    $avatar = asset('assets/dashboard/img/avatar.png');
                                }
                            @endphp

                            <img id="pictureProfile" src="{{ $avatar }}" class="profile-user-img img-fluid img-circle">
                        </div>

                        <h3 id="userName" class="profile-username text-center">
                            {{ Auth::user()->name }}
                        </h3>

                        <p class="text-muted text-center">
                            {{ Auth::user()->roles->first()->name }}
                        </p>

                        <form id="formUploadPhoto" enctype="multipart/form-data" action="{{ route('profile.updatePhoto') }}"
                            method="POST">
                            @csrf
                            <div id="buttonUpload">
                                <input type="file" name="photo" id="photoUpload" class="avatar d-none">
                                <input type="hidden" name="base64image" name="base64image" id="base64image">

                                <button type="button" id="changePicture" class="btn btn-primary btn-block">
                                    <b>Change Picture</b>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal upload --}}
                <div class="modal fade bd-example-modal-lg imagecrop" id="modalUpload" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Chage Picture</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="img-container">
                                    <div class="row">
                                        <div class="col-md-3 d-flex justify-content-center">
                                            <div class="preview"></div>
                                        </div>
                                        <div class="col-md-9 d-flex justify-content-center">
                                            <img class="img-fluid" id="sampleImage" src="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary crop" id="cropBtn">Crop Picture</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#aboutme" data-toggle="tab">About Me</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                            <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Password</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            {{-- PROFILE --}}
                            <div class="active tab-pane" id="aboutme">
                                <div class="form-horizontal">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-2 col-form-label">
                                            <i class="fas fa-phone mr-1 text-primary"></i>
                                            Phone
                                        </label>
                                        <div class="col-sm-10" id="phoneAbout">
                                            @if (Auth::user()->phone != null)
                                                {{ Auth::user()->phone }}
                                            @else
                                                <span class="text-muted">Your number phone is empty!</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="biography" class="form-group row align-items-start">
                                        <label class="col-sm-2 col-form-label">
                                            <i class="fas fa-quote-left mr-1 text-primary"></i>
                                            Biography
                                        </label>
                                        <div class="col-sm-10" id="bioAbout" style="margin-top: 6px;">
                                            @if (Auth::user()->bio != null)
                                                {!! Markdown::convert(Auth::user()->bio)->getContent() !!}
                                            @else
                                                <span class="text-muted">Your biography is empty!</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- SETTINGS --}}
                            <div class="tab-pane" id="settings">
                                <form id="formUpdateProfile" method="POST" action="{{ route('profile.updateProfile') }}"
                                    class="form-horizontal">
                                    @csrf
                                    @method('put')

                                    <div class="form-group row align-items-center">
                                        <label for="name" class="col-sm-2 col-form-label"><i
                                                class="fas fa-signature mr-2 text-primary"></i>Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" id="name"
                                                value="{{ Auth::user()->name }}">
                                            <span class="invalid-feedback d-block error-text name_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="inputEmail" class="col-sm-2 col-form-label"><i
                                                class="fas fa-envelope mr-2 text-primary"></i>Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="emailSet"
                                                value="{{ Auth::user()->email }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label for="phone" class="col-sm-2 col-form-label"><i
                                                class="fas fa-phone mr-2 text-primary"></i>Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="phone" id="phone"
                                                placeholder="{{ Auth::user()->phone == null ? 'Your number phone is empty!' : '' }}"
                                                value="{{ Auth::user()->phone == null ? '' : Auth::user()->phone }}">
                                            <span class="invalid-feedback d-block error-text phone_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-start">
                                        <label for="bio" class="col-sm-2 col-form-label"><i
                                                class="fas fa-quote-left mr-2 text-primary"></i>Biography</label>
                                        <div class="col-sm-10">
                                            <textarea onkeyup="countChar(this)" class="form-control" name="bio" id="bio" rows="6"
                                                placeholder="{{ Auth::user()->bio == null ? 'Your biography is empty!' : '' }}">{{ Auth::user()->bio == null ? '' : Auth::user()->bio }}</textarea>
                                            <small class="float-right text-muted" id="charLimit"></small>
                                            <span class="invalid-feedback d-block error-text bio_error"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button id="btnSetting" type="submit" class="btn btn-primary">Change
                                                Profile</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- PASSWORD --}}
                            <div class="tab-pane" id="password">
                                <form id="formUpdatePassword" method="POST"
                                    action="{{ route('profile.updatePassword') }}" class="form-horizontal">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row align-items-center">
                                        <label for="password" class="col-sm-3 col-form-label"><i
                                                class="fas fa-lock mr-2 text-primary"></i>Old Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="oldpass" id="oldpass"
                                                placeholder="Enter your old password here!">
                                            <span class="invalid-feedback d-block error-text oldpass_error"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label for="password" class="col-sm-3 col-form-label"><i
                                                class="fas fa-key mr-2 text-primary"></i>New Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="newpass" id="newpass"
                                                placeholder="Enter your new password here!">
                                            <span class="invalid-feedback d-block error-text newpass_error"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label for="emailSet" class="col-sm-3 col-form-label"><i
                                                class="fas fa-check mr-2 text-primary"></i>Confirm Password</label>
                                        <div class="col-sm-9">
                                            <input id="confirmpass" type="password" class="form-control"
                                                name="confirmpass" placeholder="Enter your confirm password here!">
                                            <span class="invalid-feedback d-block error-text confirmpass_error"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="offset-sm-3 col-sm-9">
                                            <button type="submit" class="btn btn-primary btnPass">Change
                                                Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Count limit character
        function countChar(val) {
            let max = 2000
            let limit = val.value.length;
            if (limit >= max) {
                val.value = val.value.substring(0, max);
                $('#charLimit').text('You have reached the limit');
            } else {
                var char = max - limit;
                $('#charLimit').text(char + ' Characters left');
            };
        }

        $(document).ready(function() {
            // CSRF TOKEN
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // TOAST
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            let $modal = $("#modalUpload");
            let sampleImg = document.getElementById('sampleImage');
            let cropper, cropped;

            $(document).on("click", '#changePicture', function() {
                $("#photoUpload").click();
            });

            // UPLOAD FOTO KE DALAM MODAL
            $(document).on("change", "#photoUpload", function(e) {
                let files = e.target.files;
                let done = function(url) {
                    sampleImg.src = url;
                    $modal.modal('show');
                }
                let reader, file, url;

                if (files && files.length > 0) {
                    file = files[0];
                    const fileSize = file.size / 1024 / 1024;
                    const validImageTypes = ["image/jpeg", "image/jpg", "image/png"];

                    if (validImageTypes.includes(file['type'])) {
                        if (fileSize <= 1) {
                            if (URL) {
                                done(URL.createObjectURL(file));
                            } else if (FileReader) {
                                reader = new FileReader();
                                reader.onload = function(e) {
                                    done(reader.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        } else {
                            Swal.fire({
                                icon: "error",
                                html: "File must not be greater than 1024 KB(1 MB)!",
                                allowOutsideClick: false,
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: "error",
                            html: "File must be of type: jpeg, jpg or png!",
                            allowOutsideClick: false,
                        });
                    }
                }
            });

            // CROPPER JS
            $modal.on('shown.bs.modal', function() {
                cropper = new Cropper(sampleImg, {
                    aspectRatio: 1,
                    viewMode: 1,
                    preview: '.preview'
                })
            }).on('hidden.bs.modal', function() {
                cropper.destroy();
                cropper = null;
            });

            // JIKA SUDAH DICROP
            $(document).on('click', '#cropBtn', function(e) {
                canvas = cropper.getCroppedCanvas({
                    width: 1000,
                    height: 1000,
                });

                canvas.toBlob((blob) => {
                    url = URL.createObjectURL(blob);
                    let reader = new FileReader();
                    reader.readAsDataURL(blob);
                    reader.onloadend = () => {
                        let base64data = reader.result;

                        $("#base64image").val(base64data);
                        $("#pictureProfile").attr('src', base64data);
                        $("#sideProfile").attr('src', base64data);

                        $modal.modal('hide');

                        $("#buttonUpload button").remove();
                        $("#buttonUpload").append(
                            "<button type='submit' class='btn btn-success btn-block'><b>Update Picture</b></button>"
                        );
                    }
                });
            });

            // SEND TO BACKEND
            $("#formUploadPhoto").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        $("#buttonUpload button b").html(
                            '<i class="fas fa-spin fa-spinner"></i>');
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            $("#base64image").val('');
                            $("#buttonUpload button").remove();
                            $("#buttonUpload").append(
                                "<button type='button' id='changePicture' class='btn btn-primary btn-block'><b>Change Picture</b></button>"
                            );

                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        } else if (response.status == 201) {
                            Swal.fire({
                                icon: 'warning',
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        } else if (response.status == 401) {
                            Swal.fire({
                                icon: 'error',
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                html: response.message.photo[0],
                                allowOutsideClick: false,
                            });
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(status.xhr + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });

            $("#formUpdateProfile").on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('#btnSetting').attr('disabled', true);
                        $("#btnSetting").html("<i class='fas fa-spin fa-spinner'></i>");
                    },
                    complete: function() {
                        $('#btnSetting').attr('disabled', false);
                        $("#btnSetting").html("Change Profile");
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            $.each(response.errors, function(key, val) {
                                $("span." + key + "_error").text(val[0]);
                                $("#" + key).addClass('is-invalid');
                            });
                        } else if (response.status == 404) {
                            Toast.fire({
                                icon: 'warning',
                                title: response.message
                            });
                        } else if (response.status == 401) {
                            Swal.fire({
                                icon: "error",
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        } else {
                            // Name
                            const usernames = document.querySelectorAll("#userName");
                            for (let i = 0; i < usernames.length; i++) {
                                usernames[i].innerHTML = $("#name").val();
                            }
                            // Phone
                            $("#phoneAbout").html($("#phone").val());

                            // BIO
                            if ($("#bio").val() == '') {
                                document.getElementById('bioAbout').innerHTML =
                                    '<span class="text-muted">Your Biography is empty!</span>';
                            } else {
                                document.getElementById('bioAbout').innerHTML = marked.parse($(
                                    "#bio").val());
                            }

                            $(document).find('span.error-text').text('');
                            $(document).find('.form-control').removeClass(
                                'is-invalid');

                            $('#charLimit').text('');

                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });

            // CHANGE PASSWORD
            $("#formUpdatePassword").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        $('.btnPass').attr('disabled', true);
                        $(".btnPass").html("<i class='fas fa-spin fa-spinner'></i>");
                    },
                    complete: function() {
                        $('.btnPass').attr('disabled', false);
                        $(".btnPass").html("Change Password");
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            $.each(response.errors, function(key, val) {
                                $("span." + key + "_error").text(val[0]);
                                $("#" + key).addClass('is-invalid');
                            });
                        } else if (response.status == 401) {
                            Swal.fire({
                                icon: "error",
                                html: response.message,
                                allowOutsideClick: false,
                            });
                        } else if (response.status == 201) {
                            Toast.fire({
                                icon: 'warning',
                                title: response.message
                            });
                        } else {
                            $("#formUpdatePassword")[0].reset();
                            $(document).find('span.error-text').text('');
                            $(document).find('.form-control').removeClass('is-invalid');

                            Swal.fire({
                                icon: "success",
                                html: response.message,
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // window.location.href = "{{ url('dashboard/profile') }}#password";
                                    window.location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });
        });
    </script>
@endpush
