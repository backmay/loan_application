<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Loan;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {
    return [
        'loan_amount' => $faker->numberBetween($min = 1000, $max = 100000000),
        'loan_term' => $faker->numberBetween($min = 1, $max = 30),
        'interest_rate' => $faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 36),
        'start_date' => $faker->dateTimeBetween($startDate = '2017-01-01', $endDate = '2050-12-31', $timezone = null),
    ];
});
