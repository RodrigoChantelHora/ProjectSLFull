<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ReportQuery extends Model
{
    protected $fillable = [
        'query_name',
        'query',
        'query_token',
        'report_id'
    ];


}
