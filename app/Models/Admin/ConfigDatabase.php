<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ConfigDatabase extends Model
{
    protected $table = 'config_databases';
    protected $fillable = [
        'db_connection',
        'host',
        'port',
        'database',
        'username',
        'password',
        'sid',
        'charset',
        'collation',
    ];
}
