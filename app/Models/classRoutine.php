<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class classRoutine extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_id','day', 'startinghours', 'startingminute', 'endinghours', 'endingminute', 'classroom_id', 'teacher_id', 'subject_id'
    ];

    
    public function classes()
    {
        return $this->belongsTo(classes::class, 'class_id'); 
    }

    public function classrooms()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function teachers()
    {
        return $this->belongsTo(teachers::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }
}
