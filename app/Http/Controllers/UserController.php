<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar User yang Terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all();
        
        return view('user.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    } // Penutup fungsi index yang benar di sini

    public function list(Request $request)
    { 
        // Pastikan nama kolom 'user_id' sesuai dengan database kamu (biasanya user_id, bukan users_id)
        $user = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        if ($request->level_id) {
            $user->where('level_id', $request->level_id);
        }

        return DataTables::of($user)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
               /* $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a>';
                $btn .= ' <a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= ' <form class="d-inline-block" method="POST" action="'.url('/user/' . $user->user_id).'">';
                $btn .= csrf_field();
                $btn .= method_field("DELETE");
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\');">Hapus</button>';
                $btn .= '</form>';*/
                $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create()
{
    $breadcrumb = (object) [
        'title' => 'Tambah User',
        'list' => ['Home', 'User', 'Tambah']
    ];

    $page = (object) [
        'title' => 'Tambah user baru'
    ];

    $level = LevelModel::all(); // ambil data level untuk dropdown
    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('user.create', [
        'breadcrumb' => $breadcrumb, 
        'page' => $page, 
        'level' => $level, 
        'activeMenu' => $activeMenu
    ]);
}
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);

        // Simpan data user baru
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // Pastikan untuk mengenkripsi password
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data User berhasil ditambahkan');
    }
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User'
        ];

        $activeMenu = 'user';
        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'user' => $user
        ]);
    }
    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list'  => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama'     => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5',          // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'required|integer'         // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) { 
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
            }
        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/user')->with('error', 'Data user Gagal dihapus: karena masih terdapat tabel lain yang terkait dengan data ini'
            );
        }
    }
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.create_ajax', ['level' => $level]);
    }
    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {

        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username',
            'nama' => 'required|max:100',
            'password' => 'required|min:6|max:20'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        UserModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    return redirect('/user')->with('error', 'Permintaan harus melalui AJAX');
}

    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', [
            'user' => $user,
            'level' => $level
        ]);
    }

    public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {

        $rules = [
            'level_id' => 'required|integer',
            'username' => 'required|max:20|unique:m_user,username,'.$id.',user_id',
            'nama' => 'required|max:100',
            'password' => 'nullable|min:6|max:20'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = UserModel::find($id);

        if ($check) {
            if(!$request->filled('password')){
                $request->request->remove('password');
            }

            $check->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}

}
    /*public function tambah()
    {
        return view('user_tambah');
    }
    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id,
        ]);
        return redirect('/user');
    }
    public function ubah(string $id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan(Request $request, string $id)
    {
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->level_id = $request->level_id;

        $user->save();
        return redirect('/user');
    }
    public function hapus(string $id)
    {
        $user = UserModel::find($id);
        $user->delete();
        return redirect('/user');
    }*/