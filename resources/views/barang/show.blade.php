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
                    <label class="col-2 control-label col-form-label">Kategori</label>
                    <div class="col-md-9">
                        <p>{{ $barang->kategori->kategori_nama }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Kode Barang</label>
                    <div class="col-md-9">
                        <p>{{ $barang->barang_kode }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Nama Barang</label>
                    <div class="col-md-9">
                        <p>{{ $barang->barang_nama }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Harga Beli</label>
                    <div class="col-md-9">
                        <p>Rp. {{ number_format($barang->harga_beli, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 control-label col-form-label">Harga Jual</label>
                    <div class="col-md-9">
                        <p>Rp. {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-secondary" href="{{ url('barang') }}">Kembali</a>
    </div>
</div>
@endsection
