<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    use HasFactory;
    protected $table = 'attendance_status';
    protected $fillable = [
        'title'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'status_id');
    }
}
