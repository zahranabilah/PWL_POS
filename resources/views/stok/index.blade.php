@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Stok</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#table_stok').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('stok/list') }}",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false},
                {data: "barang_kode", className: "" , orderable: true, searchable: true},
                {data: "barang_nama", className: "" , orderable: true, searchable: true},
                {data: "stok_tanggal", className: "" , orderable: true, searchable: false},
                {data: "stok_jumlah", className: "text-right", orderable: true, searchable: false},
                {data: "aksi", className: "", orderable: false, searchable: false}
            ]
        });
    });
</script>
@endpush
