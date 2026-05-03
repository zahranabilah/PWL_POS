<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'm_kategori';
    
    // Mendefinisikan primary key kustom
    protected $primaryKey = 'kategori_id';

    // Mendefinisikan kolom yang boleh diisi secara massal
    protected $fillable = [
        'kategori_kode',
        'kategori_nama'
    ];
}
