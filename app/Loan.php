<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'loan_amount', 'loan_term', 'interest_rate', 'start_date',
    ];
}
