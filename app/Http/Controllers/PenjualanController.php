<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->addColumn('aksi', function ($row) {
                $btn  = '<button type="button" class="btn btn-info btn-sm" onclick="modalAction(\''.url('/penjualan/' . $row->penjualan_id . '/show_ajax').'\')">Detail</button> ';
                $btn .= '<button type="button" class="btn btn-warning btn-sm" onclick="modalAction(\''.url('/penjualan/' . $row->penjualan_id . '/edit_ajax').'\')">Edit</button> ';
                $btn .= '<button type="button" class="btn btn-danger btn-sm" onclick="modalAction(\''.url('/penjualan/' . $row->penjualan_id . '/delete_ajax').'\')">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('penjualan.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'pembeli' => 'required|string|max:50',
                'penjualan_tanggal' => 'required|date'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $data = [
                'user_id' => auth()->id() ?? 1,
                'pembeli' => $request->pembeli,
                'penjualan_kode' => 'PJ' . date('YmdHis'),
                'penjualan_tanggal' => date('Y-m-d H:i:s', strtotime($request->penjualan_tanggal)),
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('t_penjualan')->insert($data);

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }

        return redirect('/penjualan')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function show_ajax($id)
    {
        $penjualan = DB::table('t_penjualan')->where('penjualan_id', $id)->first();
        return view('penjualan.show_ajax', compact('penjualan'));
    }

    public function edit_ajax($id)
    {
        $penjualan = DB::table('t_penjualan')->where('penjualan_id', $id)->first();
        return view('penjualan.edit_ajax', compact('penjualan'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'pembeli' => 'required|string|max:50',
                'penjualan_tanggal' => 'required|date'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $penjualan = DB::table('t_penjualan')->where('penjualan_id', $id)->first();

            if ($penjualan) {
                $data = [
                    'pembeli' => $request->pembeli,
                    'penjualan_tanggal' => date('Y-m-d H:i:s', strtotime($request->penjualan_tanggal)),
                    'updated_at' => now()
                ];

                DB::table('t_penjualan')->where('penjualan_id', $id)->update($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/penjualan');
    }

    public function confirm_ajax($id)
    {
        $penjualan = DB::table('t_penjualan')->where('penjualan_id', $id)->first();
        return view('penjualan.confirm_ajax', compact('penjualan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $penjualan = DB::table('t_penjualan')->where('penjualan_id', $id)->first();

            if ($penjualan) {
                DB::table('t_penjualan')->where('penjualan_id', $id)->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/penjualan');
    }

    public function export_pdf()
    {
        $penjualans = DB::table('t_penjualan')
            ->select(
                'penjualan_id',
                'penjualan_kode',
                'penjualan_tanggal',
                DB::raw('(select count(*) from t_penjualan_detail where t_penjualan_detail.penjualan_id = t_penjualan.penjualan_id) as jumlah_item')
            )
            ->orderBy('penjualan_tanggal', 'desc')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', compact('penjualans'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Data Penjualan '.date('Y-m-d_H-i-s').'.pdf');
    }
}
