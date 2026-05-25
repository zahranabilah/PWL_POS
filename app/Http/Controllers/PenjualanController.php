<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Transaksi Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar transaksi penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $penjualans = DB::table('t_penjualan')
            ->select(
                't_penjualan.penjualan_id',
                't_penjualan.penjualan_kode',
                't_penjualan.penjualan_tanggal',
                DB::raw('(select count(*) from t_penjualan_detail where t_penjualan_detail.penjualan_id = t_penjualan.penjualan_id) as jumlah_item')
            );

        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function () {
                return '-';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
