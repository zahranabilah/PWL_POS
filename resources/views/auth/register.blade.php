<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Registrasi Pengguna</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">

<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{ url('/') }}" class="h1"><b>Registrasi</b></a>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('register') }}" method="POST" id="form-register">
                @csrf

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" value="{{ old('nama') }}">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-id-card"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" minlength="6">
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <a href="{{ url('login') }}">Sudah punya akun? Login</a>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
<script>
    $(function () {
        $('#form-register').validate({
            rules: {
                username: {
                    required: true,
                    minlength: 4,
                    maxlength: 20
                },
                nama: {
                    required: true,
                    maxlength: 100
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 50
                },
                password_confirmation: {
                    required: true,
                    minlength: 6,
                    equalTo: '#password'
                }
            },
            messages: {
                password: {
                    minlength: 'Password harus minimal 6 karakter.'
                },
                password_confirmation: {
                    equalTo: 'Konfirmasi password harus sama dengan password.'
                }
            },
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            errorPlacement: function (error, element) {
                element.closest('.input-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
</body>
</html>