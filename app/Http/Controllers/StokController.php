<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Stok Barang',
            'list' => ['Home', 'Stok Barang']
        ];

        $page = (object) [
            'title' => 'Laporan stok barang'
        ];

        $activeMenu = 'stok';

        return view('stok.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $stoks = DB::table('t_stok')
            ->join('m_barang', 't_stok.barang_id', '=', 'm_barang.barang_id')
            ->select(
                't_stok.stok_id',
                't_stok.stok_tanggal',
                't_stok.stok_jumlah',
                'm_barang.barang_kode',
                'm_barang.barang_nama'
            );

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function () {
                return '-';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
