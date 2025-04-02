<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Carbon;

class Employee extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'employees';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'emp_name',
        'dob',
        'phone'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date:Y-m-d',
        'phone' => 'integer'
    ];
    
    /**
     * Custom accessor for formatted date of birth
     */
    public function getFormattedDobAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->format('Y-m-d');
    }
    
    /**
     * Custom mutator for date of birth
     */
    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = Carbon::parse($value)->format('Y-m-d');
    }
    
    /**
     * Custom mutator for phone number
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = (int) preg_replace('/[^0-9]/', '', $value);
    }
}