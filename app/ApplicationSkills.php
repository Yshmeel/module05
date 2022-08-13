<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationSkills extends Model
{
    use HasFactory;

    public $fillable = [
        'application_id',
        'level_id',
        'competence_id'
    ];

    public $visible = [
        'application_id',
        'level_id',
        'competence_id',
        'level',
        'competence'
    ];

    public function application() {
        return $this->hasOne(Application::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }

    public function competence() {
        return $this->belongsTo(Competences::class);
    }
}
