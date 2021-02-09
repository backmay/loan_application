<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RepaymentSchedule;
use Faker\Generator as Faker;

$factory->define(RepaymentSchedule::class, function (Faker $faker) {
    return [
        'payment_no' => $faker->numberBetween($min = 1, $max = 100),
        'payment_date' => $faker->dateTimeBetween($startDate = '2017-01-01', $endDate = 'now', $timezone = null),
        'principal' => $faker->numberBetween($min = 1, $max = 100),
        'interest' => $faker->numberBetween($min = 1, $max = 100),
        'balance' => $faker->numberBetween($min = 1, $max = 100),
        'loan_id' => $faker->numberBetween($min = 1, $max = 100),
    ];
});
