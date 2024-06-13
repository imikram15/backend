<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'status_id',
        'class_id',
        'student_id',
        'date',
    ];


    public function attendance_status()
    {
        return $this->belongsTo(AttendanceStatus::class, 'status_id');
    }

    public function classes()
    {
        return $this->belongsTo(classes::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(students::class, 'student_id');
    }
}
