<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exams extends Model
{
    use HasFactory;
    protected $fillable = [
        'examCategory_id',
        'class_id',
        'subject_id',
        'classroom_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'total_marks',
    ];

    public function examCategory()
    {
        return $this->belongsTo(addExamCategories::class,'examCategory_id');
    }

    public function class()
    {
        return $this->belongsTo(classes::class); 
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
