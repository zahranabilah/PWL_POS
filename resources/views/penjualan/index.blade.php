@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="window.location.href='{{ url('penjualan/export_pdf') }}'">Export PDF</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Penjualan</th>
                    <th>Tanggal</th>
                    <th>Jumlah Item</th>
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
        window.dataPenjualan = $('#table_penjualan').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('penjualan/list') }}",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false},
                {data: "penjualan_kode", className: "" , orderable: true, searchable: true},
                {data: "penjualan_tanggal", className: "" , orderable: true, searchable: false},
                {data: "jumlah_item", className: "text-center", orderable: false, searchable: false},
                {data: "aksi", className: "", orderable: false, searchable: false}
            ]
        });
    });

    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }
</script>
@endpush

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
