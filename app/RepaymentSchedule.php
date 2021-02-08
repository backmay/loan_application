<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepaymentSchedule extends Model
{
    protected $fillable = [
        'loan_amount', 'loan_term', 'interest_rate', 'start_date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
