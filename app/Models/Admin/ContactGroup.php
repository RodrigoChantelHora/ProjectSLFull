<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactGroup extends Model
{
    protected $fillable = [
        'name',
        'status',
        'token',
        'created_user',
    ];

    public function contact():HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'group_id');
    }
}
