<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;
    protected $table ='syllabuses';
    protected $fillable = ['title', 'class_id', 'subject_id', 'syllabus_file'];

    public function classes()
    {
        return $this->belongsTo(classes::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }
}
