<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'm_pengguna';

    protected $primaryKey = 'pengguna_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;
}
