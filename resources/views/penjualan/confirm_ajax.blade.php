@empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus penjualan dengan kode <strong>{{ $penjualan->penjualan_kode }}</strong> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function () {
    $("#form-delete").validate({
        submitHandler: function(form){
            submitAjax(form);
            return false;
        }
    });

    function submitAjax(form) {
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(response){
                if(response.status){
                    $('#myModal').modal('hide');
                    Swal.fire({icon:'success',title:'Berhasil',text: response.message});
                    if (window.dataPenjualan) window.dataPenjualan.ajax.reload();
                } else {
                    Swal.fire({icon:'error',title:'Gagal',text: response.message});
                }
            },
            error: function(){
                Swal.fire({icon:'error',title:'Gagal',text:'Terjadi kesalahan saat menghapus data'});
            }
        });
    }
});
</script>
@endempty