@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <form method="POST" action="{{ url('barang') }}" class="form-horizontal">
        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kategori</label>
                <div class="col-md-9">
                    <select class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->kategori_id }}" {{ old('kategori_id') == $k->kategori_id ? 'selected' : '' }}>{{ $k->kategori_nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode Barang</label>
                <div class="col-md-9">
                    <input type="text" class="form-control @error('barang_kode') is-invalid @enderror" name="barang_kode" value="{{ old('barang_kode') }}" required>
                    @error('barang_kode')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Barang</label>
                <div class="col-md-9">
                    <input type="text" class="form-control @error('barang_nama') is-invalid @enderror" name="barang_nama" value="{{ old('barang_nama') }}" required>
                    @error('barang_nama')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Harga Beli</label>
                <div class="col-md-9">
                    <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" name="harga_beli" value="{{ old('harga_beli') }}" required min="0">
                    @error('harga_beli')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Harga Jual</label>
                <div class="col-md-9">
                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" name="harga_jual" value="{{ old('harga_jual') }}" required min="0">
                    @error('harga_jual')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a class="btn btn-secondary" href="{{ url('barang') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
