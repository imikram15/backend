<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class marks extends Model
{
    use HasFactory;
    protected $fillable = [
        'examCategory_id',
        'class_id',
        'subject_id',
        'student_id',
        'mark',
        'comment',
    ];

    public function examCategory()
    {
        return $this->belongsTo(addExamCategories::class,'examCategory_id');
    }

    public function class()
    {
        return $this->belongsTo(classes::class, 'class_id'); 
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function student()
    {
        return $this->belongsTo(students::class, 'student_id');
    }
}
