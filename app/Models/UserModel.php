<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    // Supaya Laravel memakai proses hashing standar untuk verifikasi password
    protected $casts = [
        'password' => 'hashed',
    ];


    protected $fillable = [
        'level_id',
        'username',
        'nama',
        'password',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password'
    ];

    public $timestamps = true;

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
    
    public function getrolename(): string
    {
        return $this->level->level_nama;
    }
    public function hasRole($role): bool
    {
        return $this->level->level_nama === $role;
    }
    /**
     * Pastikan Laravel menggunakan nama user/password sesuai tabel m_user.
     */
    public function getAuthIdentifierName(): string
    {
        return 'username';
    }

    /**
     * Karena primary key tabel adalah user_id, Identifiernya tetap username
     * (untuk kondisi Auth::attempt(['username'=>..., 'password'=>...])).
     */
    public function getAuthPassword(): string
    {
        return (string) $this->password;
    }

}