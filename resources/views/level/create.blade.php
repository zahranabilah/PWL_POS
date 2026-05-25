@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Tambah Level</h3>
    </div>
    <div class="card-body">
        <form id="form-level" method="POST" action="{{ url('level') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Kode Level</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="level_kode" value="{{ old('level_kode') }}" required>
                    @error('level_kode') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Level</label>
                <div class="col-11">
                    <input type="text" class="form-control" name="level_nama" value="{{ old('level_nama') }}" required>
                    @error('level_nama') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('level') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
$(function(){
    var $form = $('#form-level');
    function submitAjax(form){
        $('[id^="error-"]').html('');
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(resp){
                if(resp.status){
                    Swal.fire({icon:'success',title:'Berhasil',text:resp.message});
                    setTimeout(function(){ window.location = '{{ url("level") }}'; },800);
                } else {
                    if(resp.msgField){ $.each(resp.msgField,function(k,v){ var $el=$('[name="'+k+'"]'); $el.closest('.form-group').find('.text-danger, .invalid-feedback').remove(); $el.closest('.form-group').append('<small class="form-text text-danger">'+v[0]+'</small>'); }); }
                }
            },
            error:function(){ Swal.fire({icon:'error',title:'Gagal',text:'Terjadi kesalahan'}); }
        });
    }

    if ($.isFunction($.fn.validate)){
        $form.validate({
            rules: { level_kode: { required:true }, level_nama: { required:true } },
            errorPlacement:function(error,element){ element.closest('.form-group').find('.text-danger, .invalid-feedback').remove(); element.closest('.form-group').append('<small class="form-text text-danger">'+error.text()+'</small>'); },
            submitHandler:function(form){ submitAjax(form); return false; }
        });
    } else {
        $form.on('submit', function(e){ e.preventDefault(); submitAjax(this); });
    }
});
</script>
@endpush

@endsection