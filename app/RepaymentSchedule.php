<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepaymentSchedule extends Model
{
    protected $fillable = [
        'id', 'payment_no', 'payment_date', 'principal', 'interest', 'balance', 'loan_id'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
