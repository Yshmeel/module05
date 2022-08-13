<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    public $visible = [
        'level',
        'factor'
    ];

    public $fillable = [
        'level',
        'factor'
    ];
}
