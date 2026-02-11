<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name', 'is_active', 'grade', 'school', 'program', 'parent_phone', 'parent_email'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
