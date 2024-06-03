<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class students extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'roll_no',
        'first_name',
        'last_name',
        'b_form',
        'father_name',
        'father_cnic',
        'gender',
        'class_id',
        'dob',
        'blood_group',
        'email',
        'password',
        'phone',
        'address',
        'image',
    ];

    public function classes()
    {
        return $this->HasOne(classes::class,'id','class_id');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,'student_id','id');
    }
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'member');
    }
   
}
