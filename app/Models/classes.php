<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class classes extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [
        'title',
        'id',
    ];

    public function sections()
    {
        return $this->belongsToMany(sections::class,'classes_and_sections','class_id','section_id');
    }

    public function students()
    {
        return $this->hasMany(students::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subjects::class);
    }
}
