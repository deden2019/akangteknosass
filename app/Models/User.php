<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Menentukan nama tabel kustom di database
    protected $table = 'public.m_pengguna';

    // Menentukan Primary Key kustom
    protected $primaryKey = 'pengguna_id';

    // Karena tipe data pengguna_id adalah string (UUID), nonaktifkan auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // Menonaktifkan timestamp (created_at & updated_at) jika tabel tidak memilikinya
    public $timestamps = false;

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'pengguna_id',
        'role_id',
        'nama_pengguna',
        'pass_pengguna',
        'is_active',
        'status_user',
        'email',
        'cabang_id',
    ];

    // Kolom yang disembunyikan saat serialisasi (misal: JSON)
    protected $hidden = [
        'pass_pengguna',
    ];

    /**
     * Mengubah nama kolom password default Laravel ('password') 
     * menjadi kolom kustom di database ('pass_pengguna').
     */
    public function getAuthPasswordName()
    {
        return 'pass_pengguna';
    }

    /**
     * Relasi ke tabel m_role (opsional, untuk mengecek nama_role dengan mudah).
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'pass_pengguna' => 'hashed', // Sesuaikan jika menggunakan custom MD5 salt, atau biarkan hashed jika pakai Laravel Hash
        ];
    }
}