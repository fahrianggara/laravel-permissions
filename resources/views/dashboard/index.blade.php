@extends('layouts.dashboard')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            @if (auth()->user()->can('view user') ||
                auth()->user()->can('view role') ||
                auth()->user()->can('view permission'))
                @can('view user')
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $users }}</h3>

                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="{{ route('users.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endcan

                @can('view role')
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $roles }}</h3>

                                <p>Roles</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <a href="{{ route('roles.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endcan

                @can('view permission')
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <div class="d-flex align-items-center">
                                    <h3>{{ $permissions }}</h3>
                                    <h6 class="ml-2">({{ $groups }} Groups)</h6>
                                </div>

                                <p>Permissions</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <a href="{{ route('permissions.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endcan
            @endif
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn mr-1"></i>
                            Informations
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="callout callout-info">
                            <b>Hello, {{ Auth::user()->name }}!</b>

                            @if (Session::has('login'))
                                <hr>
                                <p>
                                    {{ Session::get('login') }}
                                @elseif (Session::has('register'))
                                    {{ Session::get('register') }}
                                </p>
                            @endif

                            <hr>

                            @if (!Session::has('register'))
                                <div id="qoute">
                                    <span class='text-info'><b>Quote:</b></span>
                                    <span>???Be yourself!??? ??? Fahri Anggara.</span>
                                </div>
                            @endif

                            @if (Session::has('register'))
                                <p>Regards, Admin.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script>
        displayQuote = () => {
            let index = Math.floor(Math.random() * data.length);
            let quote = data[index].text;
            let author = data[index].author;
            if (!author) {
                author = 'Anonymous'
            }

            $('#qoute').html(`<span class='text-info'><b>Quote:</b></span> <span>???${quote}??? ??? ${author}.</span>`);
        }

        fetch("https://type.fit/api/quotes")
            .then((res) => {
                return res.json();
            }).then((data) => {
                this.data = data;
                displayQuote();
            });
    </script>
@endpush
