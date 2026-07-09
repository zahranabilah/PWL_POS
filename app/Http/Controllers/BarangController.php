<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif
        
        $kategori = KategoriModel::all();

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Ambil data barang dalam bentuk json untuk datatables 


    // Ambil data barang dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $barangs = BarangModel::with('kategori')->select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');

        if ($request->kategori_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangs)
            ->addIndexColumn() 
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori->kategori_nama ?? '-';
            })
            ->addColumn('aksi', function ($barang) { 
                $btn  = '<button type="button" class="btn btn-info btn-sm" onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/show_ajax').'\')">Detail</button> ';
                $btn .= '<button type="button" class="btn btn-warning btn-sm" onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/edit_ajax').'\')">Edit</button> ';
                $btn .= '<button type="button" class="btn btn-danger btn-sm" onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/delete_ajax').'\')">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) 
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $activeMenu = 'barang';
        $kategori = KategoriModel::all();

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:100000'
        ]);

        BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => true, 'message' => 'Data barang berhasil ditambahkan']);
        }

        return redirect('/barang')->with('success', 'Data barang berhasil ditambahkan');
    }

    public function create_ajax()
    {
        $kategori = KategoriModel::all();
        return view('barang.create_ajax', ['kategori' => $kategori]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'barang_kode' => 'required|string|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string',
                'harga_beli' => 'required|integer|min:0',
                'harga_jual' => 'required|integer|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi gagal', 'msgField' => $validator->errors()]);
            }

            BarangModel::create($request->only(['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual']));
            return response()->json(['status' => true, 'message' => 'Data barang berhasil disimpan']);
        }

        return redirect('/barang')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function show_ajax($id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        return view('barang.show_ajax', ['barang' => $barang]);
    }

    public function edit_ajax($id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();
        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'barang_kode' => 'required|string|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string',
                'harga_beli' => 'required|integer|min:0',
                'harga_jual' => 'required|integer|min:0',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi gagal', 'msgField' => $validator->errors()]);
            }

            $barang = BarangModel::find($id);
            if (!$barang) {
                return response()->json(['status' => false, 'message' => 'Data barang tidak ditemukan']);
            }

            $barang->update($request->only(['kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual']));
            return response()->json(['status' => true, 'message' => 'Data barang berhasil diubah']);
        }

        return redirect('/barang')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function confirm_ajax($id)
    {
        $barang = BarangModel::with('kategori')->find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->delete();
                return response()->json(['status' => true, 'message' => 'Data barang berhasil dihapus']);
            }
            return response()->json(['status' => false, 'message' => 'Data barang tidak ditemukan']);
        }

        return redirect('/barang')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function import()
{
    return view('barang.import');
}

public function import_ajax(Request $request)
{
    // selalu balas JSON untuk route ini (karena UI pakai $.ajax)
    if(true){


        $rules = [
            'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_barang');

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($file->getRealPath());

        $sheet = $spreadsheet->getActiveSheet();

        $data = $sheet->toArray(null, false, true, true);

        $insert = [];

        if(count($data) > 1){

            $processed = 0;
            $skipped = 0;
            $failed = 0;
            $duplicate = 0;

            foreach($data as $baris => $value){
                if($baris <= 1) continue;

                $processed++;

                $kategoriId = $value['A'] ?? null;
                $barangKode = $value['B'] ?? null;
                $barangNama = $value['C'] ?? null;
                $hargaBeli = $value['D'] ?? null;
                $hargaJual = $value['E'] ?? null;

                // basic empty check
                if($kategoriId === null || $barangKode === null || $barangNama === null || $hargaBeli === null || $hargaJual === null){
                    $skipped++;
                    continue;
                }

                // normalize numeric columns (xlsx sometimes returns numeric)
                $kategoriId = (int)$kategoriId;
                $hargaBeli = (int)$hargaBeli;
                $hargaJual = (int)$hargaJual;
                $barangKode = trim((string)$barangKode);
                $barangNama = trim((string)$barangNama);

                $insert[] = [
                    'kategori_id' => $kategoriId,
                    'barang_kode' => $barangKode,
                    'barang_nama' => $barangNama,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'created_at' => now(),
                ];
            }

            if(count($insert) > 0){
                // insert per-row so we can tell duplicates/failed
                foreach($insert as $row){
                    try{
                        BarangModel::create([
                            'kategori_id' => $row['kategori_id'],
                            'barang_kode' => $row['barang_kode'],
                            'barang_nama' => $row['barang_nama'],
                            'harga_beli' => $row['harga_beli'],
                            'harga_jual' => $row['harga_jual'],
                        ]);
                    }catch(\Illuminate\Database\QueryException $e){
                        $msg = $e->getMessage();
                        if(stripos($msg, 'unique') !== false || stripos($msg, 'duplicate') !== false){
                            $duplicate++;
                        }else{
                            $failed++;
                            Log::error('Barang import failed row', ['error'=>$msg, 'row'=>$row]);
                        }
                    }
                }
            }

            return response()->json([
                'status' => ($failed === 0),
                'message' => ($failed === 0)
                    ? 'Data berhasil diimport'
                    : 'Import selesai, tapi ada beberapa baris gagal disimpan',
                'stats' => [
                    'processed' => $processed,
                    'inserted' => count($insert) - $duplicate - $failed,
                    'duplicate' => $duplicate,
                    'failed' => $failed,
                    'skipped' => $skipped,
                ]
            ]);

        }

        return response()->json([
            'status' => false,
            'message' => 'Tidak ada data yang diimport'
        ]);
    }

    return redirect('/');
}
    public function show($id)
    {
        $barang = BarangModel::with('kategori')->findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang ' . $barang->barang_nama
        ];

        $activeMenu = 'barang';

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $barang = BarangModel::findOrFail($id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit barang ' . $barang->barang_nama
        ];

        $activeMenu = 'barang';

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0'
        ]);

        $barang = BarangModel::findOrFail($id);
        $barang->update([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function export_excel()
    {
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
        ->orderBy('kategori_id')
        ->with('kategori')
        ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();   // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);   // bold header

        $no = 1;        // nomor data dimulai dari 1
        $baris = 2;     // baris data dimulai dari baris ke 2
        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->barang_kode);
            $sheet->setCellValue('C'.$baris, $value->barang_nama);
            $sheet->setCellValue('D'.$baris, $value->harga_beli);
            $sheet->setCellValue('E'.$baris, $value->harga_jual);
            $sheet->setCellValue('F'.$baris, $value->kategori->kategori_nama); // ambil nama kategori
            $baris++;
            $no++;
        }

        foreach(range('A','F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Barang'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Barang '.date('Y-m-d_H-i-s').'.xlsx';

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'max-age=0',
            'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
            'Pragma' => 'public',
        ];

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, $headers);
        } // end function export_excel

    public function export_pdf()
    {
        $barang = BarangModel::with('kategori')
            ->select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->get();

        $pdf = Pdf::loadView('barang.export_pdf', compact('barang'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Data Barang '.date('Y-m-d_H-i-s').'.pdf');
    }



    public function destroy($id)
    {
        $barang = BarangModel::findOrFail($id);
        $barang->delete();

        return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
    }
}
