@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Edit Supplier</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('supplier/'.$supplier->supplier_id) }}" class="form-horizontal">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="supplier_kode" value="{{ old('supplier_kode', $supplier->supplier_kode) }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="supplier_nama" value="{{ old('supplier_nama', $supplier->supplier_nama) }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Alamat</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="supplier_alamat" value="{{ old('supplier_alamat', $supplier->supplier_alamat) }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('supplier') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection