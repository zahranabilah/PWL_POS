@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ url('profile') }}" enctype="multipart/form-data" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Foto Profil</label>
                <div class="col-11">
                    <div class="mb-3">
                        <img src="{{ $user->getProfilePhotoUrl() }}" class="img-thumbnail" alt="Foto Profil" style="width:120px; height:120px; object-fit:cover;">
                    </div>
                    <input type="file" name="profile_photo" class="form-control-file">
                    @error('profile_photo')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Password</label>
                <div class="col-11">
                    <input type="password" class="form-control" name="password">
                    @error('password')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @else
                        <small class="form-text text-muted">Abaikan jika tidak ingin mengganti password.</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="col-11 offset-1">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Profil</button>
                    <a href="{{ url('/') }}" class="btn btn-default btn-sm ml-1">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
