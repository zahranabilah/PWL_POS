<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Level Pengguna</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">- Pilih Level -</option>
                        @foreach($level as $l)
                            <option value="{{ $l->level_id }}">
                                {{ $l->level_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control">
                    <small id="error-username" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control">
                    <small id="error-nama" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small id="error-password" class="text-danger"></small>
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
            level_id: {required:true},
            username: {required:true,minlength:3},
            nama: {required:true,minlength:3},
            password: {required:true,minlength:6}
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