@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Supplier</h3>
    </div>
    <div class="card-body">
        <form id="form-supplier" method="POST" action="{{ url('supplier') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="supplier_kode" value="{{ old('supplier_kode') }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="supplier_nama" value="{{ old('supplier_nama') }}" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Alamat</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="supplier_alamat" value="{{ old('supplier_alamat') }}" required>
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

@push('js')
<script>
$(function(){
    var $form = $('#form-supplier');
    function submitAjax(form){
        $.ajax({ url: form.action, type: form.method, data: $(form).serialize(), success:function(resp){ if(resp.status){ Swal.fire({icon:'success',title:'Berhasil',text:resp.message}); setTimeout(function(){ window.location='{{ url("supplier") }}'; },800); } else { if(resp.msgField){ $.each(resp.msgField,function(k,v){ var $el=$('[name="'+k+'"]'); $el.closest('.form-group').find('.text-danger, .invalid-feedback').remove(); $el.closest('.form-group').append('<small class="form-text text-danger">'+v[0]+'</small>'); }); } } }, error:function(){ Swal.fire({icon:'error',title:'Gagal',text:'Terjadi kesalahan'}); } }); }

    if ($.isFunction($.fn.validate)){
        $form.validate({ rules:{ supplier_kode:{required:true}, supplier_nama:{required:true}, supplier_alamat:{required:true} }, errorPlacement:function(error,element){ element.closest('.form-group').find('.text-danger, .invalid-feedback').remove(); element.closest('.form-group').append('<small class="form-text text-danger">'+error.text()+'</small>'); }, submitHandler:function(form){ submitAjax(form); return false; } });
    } else { $form.on('submit', function(e){ e.preventDefault(); submitAjax(this); }); }
});
</script>
@endpush

@endsection