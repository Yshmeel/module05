<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competences extends Model
{
    use HasFactory;

    public $fillable = [
        'id',
        'competence',
        'height',
        'job_id'
    ];

    public $visible = [
        'id',
        'competence',
        'height',
        'job_id'
    ];

    public $timestamps = false;
}
