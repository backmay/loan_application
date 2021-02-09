<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoanIndex()
    {
        $response = $this->get('/loan');
        $response->assertStatus(200);
    }

    public function testLoanCreate()
    {
        $response = $this->get('/loan/create');
        $response->assertStatus(200);
    }

//    public function testLoanStore()
//    {
//        $formData = [
//            'loan_amount' => 1000000,
//            'loan_term' => 1,
//            'interest_rate' => 10.00,
//            'year' => '2020',
//            'month' => '1'
//        ];
//        $response = $this->post(route('loan.store'), $formData);
//        $this->assertDatabaseHas('loans', [
//            'loan_amount' => 1000000
//        ]);
//    }

}
