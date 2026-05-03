@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <form method="POST" action="{{ url('kategori') }}" class="form-horizontal">
        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode Kategori</label>
                <div class="col-md-9">
                    <input type="text" class="form-control @error('kategori_kode') is-invalid @enderror" name="kategori_kode" value="{{ old('kategori_kode') }}" required>
                    @error('kategori_kode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Kategori</label>
                <div class="col-md-9">
                    <input type="text" class="form-control @error('kategori_nama') is-invalid @enderror" name="kategori_nama" value="{{ old('kategori_nama') }}" required>
                    @error('kategori_nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="{{ url('kategori') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
