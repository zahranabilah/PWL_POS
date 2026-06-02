<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Transaksi Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" id="pembeli" class="form-control">
                    <small id="error-pembeli" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="datetime-local" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control">
                    <small id="error-penjualan_tanggal" class="text-danger"></small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </div>
    </div>
</form>

<script>
$(document).ready(function () {
    $("#form-tambah").validate({
        rules: {
            pembeli: {required:true, minlength:3},
            penjualan_tanggal: {required:true}
        },
        errorPlacement: function(error, element) {
            var errorId = '#error-' + element.attr('name');
            $(errorId).html(error.text());
        },
        submitHandler: function(form){
            submitAjax(form);
            return false;
        }
    });

    if (!$.isFunction($.fn.validate)) {
        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();
            submitAjax(this);
        });
    }

    function submitAjax(form) {
        $('[id^="error-"]').html('');

        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(response){
                if(response.status){
                    $('#myModal').modal('hide');

                    Swal.fire({
                        icon:'success',
                        title:'Berhasil',
                        text: response.message
                    });

                    location.reload();
                } else {
                    if(response.msgField){
                        $.each(response.msgField, function(key, value){
                            $('#error-' + key).html(value[0]);
                        });
                    }
                }
            },
            error: function(xhr){
                Swal.fire({
                    icon:'error',
                    title:'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data'
                });
            }
        });
    }
});
</script>