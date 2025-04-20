<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ConfigMail extends Model
{
    protected $fillable = [
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_adress',
        'from_name',
        'signature',
    ];
}
