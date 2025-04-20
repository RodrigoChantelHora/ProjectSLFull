<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'report_description',
        'status',
        'group_id',
        'message',
        'scheduleType',
        'selectedTime',
        'selectedWeekday',
        'report_token',
        'selectedDay',
        'selectedMonth',
        'user_id'
    ];

    public function group():BelongsTo
    {
        return $this->belongsTo(ContactGroup::class, 'group_id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(ContactGroup::class, 'user_id');
    }
}
