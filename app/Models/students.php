<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class students extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'roll_no',
        'first_name',
        'last_name',
        'father_name',
        'gender',
        'class',
        'dob',
        'email',
        'phone',
        'address',
    ];
}
