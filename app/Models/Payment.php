<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'paid_at', 'method', 'amount', 'status', 'proof_path', 'notes'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
