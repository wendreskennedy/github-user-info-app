<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'method',
        'endpoint',
        'payload',
        'status_code',
    ];

    protected $table = 'api_logs';

    protected $primaryKey = 'id';

    public $timestamps = true;
}
