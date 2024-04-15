<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teachers extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'department_id',
        'designation_id',
        'category_id',
        'name',
        'father_name',
        'blood_group',
        'gender',
        'dob',
        'email',
        'password',
        'phone',
        'joining_date',
        'address',
        'image',
        'created_at',
    ];
}
