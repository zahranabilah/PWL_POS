<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level';

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request) {
    $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');
    return DataTables::of($levels)
        ->addIndexColumn()
        ->addColumn('aksi', function ($level) {
            $btn = '<button type="button" class="btn btn-info btn-sm" onclick="modalAction(\''.url('/level/' . $level->level_id . '/show_ajax').'\')">Detail</button> ';
            $btn .= '<button type="button" class="btn btn-warning btn-sm" onclick="modalAction(\''.url('/level/' . $level->level_id . '/edit_ajax').'\')">Edit</button> ';
            $btn .= '<button type="button" class="btn btn-danger btn-sm" onclick="modalAction(\''.url('/level/' . $level->level_id . '/delete_ajax').'\')">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
}
public function create() {
    $breadcrumb = (object) [
        'title' => 'Tambah Level',
        'list' => ['Home', 'Level', 'Tambah']
    ];
    $page = (object) [
        'title' => 'Tambah level baru'
    ];
    $activeMenu = 'level';
    return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
}

public function store(Request $request) {
    $request->validate([
        'level_kode' => 'required|string|unique:m_level,level_kode',
        'level_nama' => 'required|string|max:100',
    ]);

    LevelModel::create([
        'level_kode' => $request->level_kode,
        'level_nama' => $request->level_nama,
    ]);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['status' => true, 'message' => 'Data level berhasil disimpan']);
    }

    return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi gagal', 'msgField' => $validator->errors()]);
            }

            LevelModel::create($request->only(['level_kode', 'level_nama']));
            return response()->json(['status' => true, 'message' => 'Data level berhasil disimpan']);
        }

        return redirect('/level')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function show_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.show_ajax', ['level' => $level]);
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Validasi gagal', 'msgField' => $validator->errors()]);
            }

            $level = LevelModel::find($id);
            if (!$level) {
                return response()->json(['status' => false, 'message' => 'Data level tidak ditemukan']);
            }

            $level->update($request->only(['level_kode', 'level_nama']));
            return response()->json(['status' => true, 'message' => 'Data level berhasil diubah']);
        }

        return redirect('/level')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json(['status' => true, 'message' => 'Data level berhasil dihapus']);
            }
            return response()->json(['status' => false, 'message' => 'Data level tidak ditemukan']);
        }

        return redirect('/level')->with('error', 'Permintaan harus melalui AJAX');
    }

    public function show(string $id)
{
    $level = LevelModel::find($id);

    $breadcrumb = (object) [
        'title' => 'Detail Level',
        'list' => ['Home', 'Level', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail Level'
    ];

    $activeMenu = 'level';
    return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
}

public function edit(string $id)
{
    $level = LevelModel::find($id);

    $breadcrumb = (object) [
        'title' => 'Edit Level',
        'list' => ['Home', 'Level', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit Level'
    ];

    $activeMenu = 'level';

    return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
}

public function update(Request $request, string $id)
{
    $request->validate([
        'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
        'level_nama' => 'required|string|max:100',
    ]);

    $level = LevelModel::find($id);
    if (!$level) {
        return redirect('/level')->with('error', 'Data level tidak ditemukan');
    }

    $level->update([
        'level_kode' => $request->level_kode,
        'level_nama' => $request->level_nama,
    ]);

    return redirect('/level')->with('success', 'Data level berhasil diubah');
}
public function destroy(string $id) {
    $check = LevelModel::find($id);
    if (!$check) {
        return redirect('/level')->with('error', 'Data level tidak ditemukan');
    }

    try {
        LevelModel::destroy($id);
        return redirect('/level')->with('success', 'Data level berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terkait dengan data lain');
    }
}
}
