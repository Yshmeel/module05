<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public $fillable = [
        'email',
        'name',
        'phone',
        'job_id'
    ];

    public $visible = [
        'email',
        'name',
        'phone',
        'job_id',
        'job',
        'skills',
        'competitor'
    ];

    public function job() {
        return $this->hasOne(Job::class);
    }

    public function skills() {
        return $this->hasMany(ApplicationSkills::class);
    }

    public function competitor() {
        return $this->belongsTo(Competitor::class);
    }
}
