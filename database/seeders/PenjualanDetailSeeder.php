<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
{
    $data = [];
    $id = 1;
    for ($i = 1; $i <= 10; $i++) { // 10 Transaksi
        for ($j = 1; $j <= 3; $j++) { // 3 Barang per transaksi
            $data[] = [
                'detail_id'    => $id,
                'penjualan_id' => $i,
                // Menggunakan pola agar barang_id selalu unik di setiap transaksi
                'barang_id'    => (($i + $j) % 15) + 1, 
                'harga'        => 10000,
                'jumlah'       => 1,
            ];
            $id++;
        }
    }
    DB::table('t_penjualan_detail')->insert($data);
}
}