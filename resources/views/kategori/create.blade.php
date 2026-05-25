@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <form id="form-kategori" method="POST" action="{{ url('kategori') }}" class="form-horizontal">
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

@push('js')
<script>
$(function(){
    var $form = $('#form-kategori');
    function submitAjax(form){
        $.ajax({ url: form.action, type: form.method, data: $(form).serialize(), success: function(resp){ if(resp.status){ Swal.fire({icon:'success',title:'Berhasil',text:resp.message}); setTimeout(function(){ window.location='{{ url("kategori") }}'; },800); } else { if(resp.msgField){ $.each(resp.msgField,function(k,v){ var $el=$('[name="'+k+'"]'); $el.closest('.form-group').find('.text-danger, .invalid-feedback').remove(); $el.closest('.form-group').append('<small class="form-text text-danger">'+v[0]+'</small>'); }); } } }, error:function(){ Swal.fire({icon:'error',title:'Gagal',text:'Terjadi kesalahan'}); } }); }

    if ($.isFunction($.fn.validate)){
        $form.validate({ rules: { kategori_kode:{required:true}, kategori_nama:{required:true} }, errorPlacement:function(error,element){ element.closest('.form-group').find('.text-danger, .invalid-feedback').remove(); element.closest('.form-group').append('<small class="form-text text-danger">'+error.text()+'</small>'); }, submitHandler:function(form){ submitAjax(form); return false; } });
    } else { $form.on('submit', function(e){ e.preventDefault(); submitAjax(this); }); }
});
</script>
@endpush

@endsection
