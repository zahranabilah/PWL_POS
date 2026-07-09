@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-success mt-1" onclick="window.location.href='{{ url('barang/export_excel') }}'">Export Excel</button>
            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="window.location.href='{{ url('barang/export_pdf') }}'">Export PDF</button>
            <button type="button" class="btn btn-sm btn-info mt-1" onclick="modalAction('{{ url('barang/import') }}')">Import</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">- Semua Kategori -</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Barang</small>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        window.dataBarang = $('#table_barang').DataTable({
            serverSide: true, 
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                {data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false},
                {data: "barang_kode", className: "", orderable: true, searchable: true},
                {data: "barang_nama", className: "", orderable: true, searchable: true},
                {data: "kategori_nama", className: "", orderable: false, searchable: false},
                {data: "harga_beli", className: "text-right", orderable: true, searchable: false},
                {data: "harga_jual", className: "text-right", orderable: true, searchable: false},
                {data: "aksi", className: "", orderable: false, searchable: false}
            ]
        });

        $('#kategori_id').on('change', function() {
            window.dataBarang.ajax.reload();
        });
    });

    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }
</script>
@endpush
