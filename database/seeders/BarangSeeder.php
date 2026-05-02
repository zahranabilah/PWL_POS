<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder {
    public function run(): void {
        $data = [
            ['barang_id'=>1,'kategori_id'=>1,'barang_kode'=>'MKN01','barang_nama'=>'Roti','harga_beli'=>5000,'harga_jual'=>7000],
            ['barang_id'=>2,'kategori_id'=>1,'barang_kode'=>'MKN02','barang_nama'=>'Snack','harga_beli'=>2000,'harga_jual'=>3500],
            ['barang_id'=>3,'kategori_id'=>1,'barang_kode'=>'MKN03','barang_nama'=>'Mie Instan','harga_beli'=>2500,'harga_jual'=>3000],
            ['barang_id'=>4,'kategori_id'=>2,'barang_kode'=>'MNM01','barang_nama'=>'Air Mineral','harga_beli'=>3000,'harga_jual'=>4000],
            ['barang_id'=>5,'kategori_id'=>2,'barang_kode'=>'MNM02','barang_nama'=>'Teh Botol','harga_beli'=>4000,'harga_jual'=>6000],
            ['barang_id'=>6,'kategori_id'=>2,'barang_kode'=>'MNM03','barang_nama'=>'Kopi Kaleng','harga_beli'=>6000,'harga_jual'=>8500],
            ['barang_id'=>7,'kategori_id'=>3,'barang_kode'=>'KST01','barang_nama'=>'Paracetamol','harga_beli'=>5000,'harga_jual'=>7500],
            ['barang_id'=>8,'kategori_id'=>3,'barang_kode'=>'KST02','barang_nama'=>'Vitamin C','harga_beli'=>10000,'harga_jual'=>12500],
            ['barang_id'=>9,'kategori_id'=>3,'barang_kode'=>'KST03','barang_nama'=>'Masker','harga_beli'=>15000,'harga_jual'=>20000],
            ['barang_id'=>10,'kategori_id'=>4,'barang_kode'=>'PRW01','barang_nama'=>'Sabun','harga_beli'=>3000,'harga_jual'=>4500],
            ['barang_id'=>11,'kategori_id'=>4,'barang_kode'=>'PRW02','barang_nama'=>'Shampoo','harga_beli'=>15000,'harga_jual'=>18000],
            ['barang_id'=>12,'kategori_id'=>4,'barang_kode'=>'PRW03','barang_nama'=>'Pasta Gigi','harga_beli'=>8000,'harga_jual'=>11000],
            ['barang_id'=>13,'kategori_id'=>5,'barang_kode'=>'PKN01','barang_nama'=>'Kaos Polos','harga_beli'=>30000,'harga_jual'=>45000],
            ['barang_id'=>14,'kategori_id'=>5,'barang_kode'=>'PKN02','barang_nama'=>'Kemeja','harga_beli'=>80000,'harga_jual'=>110000],
            ['barang_id'=>15,'kategori_id'=>5,'barang_kode'=>'PKN03','barang_nama'=>'Celana Jean','harga_beli'=>120000,'harga_jual'=>150000],
        ];
        DB::table('m_barang')->insert($data);
    }
}