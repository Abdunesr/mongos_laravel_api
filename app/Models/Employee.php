<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'employees';
    
    protected $fillable = [
        'emp_name',
        'dob',
        'phone'
    ];
    
    protected $casts = [
        'dob' => 'date',
        'phone' => 'integer'
    ];
}
