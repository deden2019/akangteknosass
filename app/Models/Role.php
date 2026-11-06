<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'm_role';

    protected $primaryKey = 'role_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;
}
