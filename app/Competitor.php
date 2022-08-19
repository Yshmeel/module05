<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'email',
        'phone'
    ];

    public $visible = [
        'id',
        'name',
        'email',
        'phone'
    ];
}
