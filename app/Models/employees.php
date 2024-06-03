<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'member');
    }
    
    // public function member()
    // {
    //     return $this->morphTo('memberable');
    // }
}
