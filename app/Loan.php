<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'loan_amount', 'loan_term', 'interest_rate', 'start_date', 'pmt',
    ];

    public function repaymentSchedule()
    {
        return $this->hasMany(RepaymentSchedule::class, loan_id, id);
    }
}
