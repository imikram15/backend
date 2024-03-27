<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
        'department_id',
        'designation_id',
        'category_id',
        'name',
        'father_name',
        'gender',
        'dob',
        'email',
        'phone',
        'joining_date',
        'address',
        'image',
        'created_at',
    ];
}
