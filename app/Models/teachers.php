<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
        'phone',
        'joining_date',
        'address',
        'image',
        'created_at',
    ];

    public function routines()
    {
        return $this->hasMany(classRoutine::class);
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'member');
    }

    public function department(){
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
