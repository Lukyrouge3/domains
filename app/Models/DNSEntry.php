<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DNSEntry extends Model
{
    protected $fillable = [
        'host',
        'ip',
        'class',
        'type',
        'expires_at',
    ];

    protected $table = 'dns_entries';
}
