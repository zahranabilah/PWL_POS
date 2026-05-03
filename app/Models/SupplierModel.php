<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Identifier\Model; // Gunakan Model standar
use Illuminate\Database\Eloquent\Model as Eloquent;

class SupplierModel extends Eloquent
{
    use HasFactory;

    protected $table = 'm_supplier'; // Nama tabel di database
    protected $primaryKey = 'supplier_id'; // Nama primary key

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'supplier_kode',
        'supplier_nama',
        'supplier_alamat'
    ];
}