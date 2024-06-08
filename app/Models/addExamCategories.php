<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addExamCategories extends Model
{
    use HasFactory;
    protected $table ='add_exam_categories';
    protected $fillable =[
        'title',
        'updated_at',
        'created_at',
    ];

}
