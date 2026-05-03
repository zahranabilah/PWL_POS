<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'm_level';
    
    // Mendefinisikan primary key kustom
    protected $primaryKey = 'level_id';

    // Mendefinisikan kolom yang boleh diisi secara massal
    protected $fillable = [
        'level_kode',
        'level_nama'
    ];
}