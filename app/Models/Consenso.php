<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consenso extends Model
{
    protected $table = 'consensi'; // 👈 FIX

    protected $fillable = ['tipo', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

}