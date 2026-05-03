@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kode Kategori</label>
                    <div class="col-md-9">
                        <p>{{ $kategori->kategori_kode }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Kategori</label>
                    <div class="col-md-9">
                        <p>{{ $kategori->kategori_nama }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-secondary" href="{{ url('kategori') }}">Kembali</a>
    </div>
</div>
@endsection
