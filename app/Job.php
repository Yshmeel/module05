<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public $visible = [
        'id',
        'job',
        'competences',
        'applications'
    ];

    public $fillable = [
        'job'
    ];

    public $timestamps = false;

    public function competences() {
        return $this->hasMany(Competences::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }
}
