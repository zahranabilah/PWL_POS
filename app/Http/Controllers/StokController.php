<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->addColumn('aksi', function ($row) {
                $btn  = '<button type="button" class="btn btn-info btn-sm" onclick="modalAction(\''.url('/stok/' . $row->stok_id . '/show_ajax').'\')">Detail</button> ';
                $btn .= '<button type="button" class="btn btn-warning btn-sm" onclick="modalAction(\''.url('/stok/' . $row->stok_id . '/edit_ajax').'\')">Edit</button> ';
                $btn .= '<button type="button" class="btn btn-danger btn-sm" onclick="modalAction(\''.url('/stok/' . $row->stok_id . '/delete_ajax').'\')">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $suppliers = DB::table('m_supplier')->get();
        $barangs = DB::table('m_barang')->get();
        return view('stok.create_ajax', compact('suppliers', 'barangs'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer'
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
                'supplier_id' => $request->supplier_id,
                'barang_id' => $request->barang_id,
                'user_id' => auth()->id() ?? 1,
                'stok_tanggal' => date('Y-m-d H:i:s', strtotime($request->stok_tanggal)),
                'stok_jumlah' => $request->stok_jumlah,
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('t_stok')->insert($data);

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }

        return redirect('/stok')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function show_ajax($id)
    {
        $stok = DB::table('t_stok')->where('stok_id', $id)->first();
        return view('stok.show_ajax', compact('stok'));
    }

    public function edit_ajax($id)
    {
        $stok = DB::table('t_stok')->where('stok_id', $id)->first();
        $suppliers = DB::table('m_supplier')->get();
        $barangs = DB::table('m_barang')->get();
        return view('stok.edit_ajax', compact('stok', 'suppliers', 'barangs'));
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $stok = DB::table('t_stok')->where('stok_id', $id)->first();

            if ($stok) {
                $data = [
                    'supplier_id' => $request->supplier_id,
                    'barang_id' => $request->barang_id,
                    'stok_tanggal' => date('Y-m-d H:i:s', strtotime($request->stok_tanggal)),
                    'stok_jumlah' => $request->stok_jumlah,
                    'updated_at' => now()
                ];

                DB::table('t_stok')->where('stok_id', $id)->update($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/stok');
    }

    public function confirm_ajax($id)
    {
        $stok = DB::table('t_stok')->where('stok_id', $id)->first();
        return view('stok.confirm_ajax', compact('stok'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = DB::table('t_stok')->where('stok_id', $id)->first();

            if ($stok) {
                DB::table('t_stok')->where('stok_id', $id)->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/stok');
    }

    public function export_pdf()
    {
        $stoks = DB::table('t_stok')
            ->join('m_barang', 't_stok.barang_id', '=', 'm_barang.barang_id')
            ->select(
                't_stok.stok_id',
                't_stok.stok_tanggal',
                't_stok.stok_jumlah',
                'm_barang.barang_kode',
                'm_barang.barang_nama'
            )
            ->orderBy('t_stok.stok_tanggal', 'desc')
            ->get();

        $pdf = Pdf::loadView('stok.export_pdf', compact('stoks'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Data Stok '.date('Y-m-d_H-i-s').'.pdf');
    }
}
