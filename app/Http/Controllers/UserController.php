<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;

class UserController extends Controller
{
    public function index()
    {

        /*$data = [
            'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345'),
        ];*/

        /*$data = [
            'username' => 'customer 1',
            'nama' => 'Pelanggan',
            'password' => Hash::make('12345'),
            'level_id' => 4,
        ];
        UserModel::insert($data);

        $user = UserModel::all();
        return view('user', ['data' => $user]);*/
        /*$data = [
            'nama' => 'Pelanggan Pertama',
        ];
        UserModel::where('username', 'customer 1')->update($data);*/
    
        //UserModel::create($data);

       /* $user = UserModel::findOr(20, ['username', 'nama'], function () {
            abort(404);
        });*/

        $user = UserModel::where('username', 'manager9')->firstOrFail();
        return view('user', ['data' => $user]);
            

    }
}