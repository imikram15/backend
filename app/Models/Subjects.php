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
        return $this->hasMany(classes::class,'id','class_id');
    }
}
