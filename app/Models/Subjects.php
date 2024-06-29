<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'id',
        'class_id'
    ];

    public function classes()
    {
        return $this->hasMany(classes::class, 'id', 'class_id');
    }

    public function classroutine()
    {
        return $this->hasMany(classRoutine::class);
    }

    public function students()
    {
        return $this->hasMany(students::class, 'class_id', 'id');
    }
    public function syllabuses()
    {
        return $this->hasMany(Syllabus::class, 'subject_id');
    }
}
