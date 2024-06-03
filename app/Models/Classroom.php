<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'id',
    ];

    public function classroutine()
    {
        return $this->hasMany(classRoutine::class);
    }
}
