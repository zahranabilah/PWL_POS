<form action="{{ url('/stok/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Stok Barang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        <option value="">- Pilih Supplier -</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->supplier_id }}">
                                {{ $s->supplier_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-supplier_id" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">- Pilih Barang -</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->barang_id }}">
                                {{ $b->barang_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-barang_id" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Stok</label>
                    <input type="datetime-local" name="stok_tanggal" id="stok_tanggal" class="form-control">
                    <small id="error-stok_tanggal" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control" min="0">
                    <small id="error-stok_jumlah" class="text-danger"></small>
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
            supplier_id: {required:true},
            barang_id: {required:true},
            stok_tanggal: {required:true},
            stok_jumlah: {required:true, number:true, min:0}
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