<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;
    protected $table = 'sections';

    protected $fillable = [
        'title',
    ];

    // public function classes()
    // {
    //     return $this->hasMany(classes::class,'section_id','id');
    // }
    public function classes()
    {
        return $this->belongsToMany(classes::class,'classes_and_sections','section_id','class_id');
    }
}
