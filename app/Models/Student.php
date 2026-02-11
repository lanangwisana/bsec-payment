<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name', 'is_active', 'grade', 'school', 'program', 
        'parent_name', 'parent_phone', 'parent_email', 
        'registration_date', 'monthly_fee', 'address', 'birth_place_date'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'monthly_fee' => 'float',
        'is_active' => 'boolean',
    ];

    public function setNameAttribute($value) { $this->attributes['name'] = strtoupper($value); }
    public function setSchoolAttribute($value) { $this->attributes['school'] = strtoupper($value); }
    public function setParentNameAttribute($value) { $this->attributes['parent_name'] = strtoupper($value); }
    public function setBirthPlaceDateAttribute($value) { $this->attributes['birth_place_date'] = strtoupper($value); }
    public function setAddressAttribute($value) { $this->attributes['address'] = strtoupper($value); }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
