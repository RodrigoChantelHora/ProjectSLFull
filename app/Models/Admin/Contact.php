<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'description',
        'token',
        'status',
        'group_id',
        'created_user',
    ];

    public function group():BelongsTo
    {
        return $this->belongsTo(ContactGroup::class, 'group_id');
    }
}
