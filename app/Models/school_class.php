<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class school_class extends Model
{
    use HasFactory;
    protected $table = 'school_class';

    protected $fillable = [
        'title',
    ];
}
