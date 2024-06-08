<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
    'class_id',
    'student_id',
    'invoiceTitle',
    'totalAmount',
    'paidAmount',
    'status',
    'paymentMethod',
    ];

    public function class(){
        return $this->belongsTo(classes::class, 'class_id');
    }

    public function student(){
        return $this->belongsTo(students::class, 'student_id');
    }
}
