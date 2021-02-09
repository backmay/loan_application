<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Loan;
use App\RepaymentSchedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class ExampleTest extends TestCase

{
    /**
     * A basic test example.
     *
     * @return void
     */
//    use DatabaseTransactions;
    public function testEmptyDatabase()
    {
        $this->artisan('migrate:fresh');
        $this->assertDatabaseCount('loans', 0);
    }

    public function testCreateLoan()
    {
        $loan = factory(Loan::class)->make();
        $this->assertInstanceOf(Loan::class, $loan);
    }

    public function testCreateRepaymentSchedule()
    {
        $loan = factory(RepaymentSchedule::class)->make();
        $this->assertInstanceOf(RepaymentSchedule::class, $loan);
    }

    public function testLoanIndexView()
    {
        $response = $this->get('/loan');
        $response->assertStatus(200);
        $response->assertViewIs('loan.index');
        $response->assertViewHas(['data'], Loan::class);
    }

    public function testLoanCreateView()
    {
        $response = $this->get('/loan/create');
        $response->assertStatus(200);
        $response->assertViewIs('loan.create');
    }

    public function testLoanCreate()
    {
        $formData = [
            'loan_amount' => 12345678,
            'loan_term' => 9,
            'interest_rate' => 10.11,
            'year' => '2020',
            'month' => '1'
        ];
        $response = $this->post(route('loan.store'), $formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('loans', [
            'loan_amount' => 12345678,
            'loan_term' => 9,
            'interest_rate' => 10.11,
            'start_date' => '2020-01-01',
            'pmt' => 174547.159343,
        ]);
    }

    public function testLoanView()
    {
        $response = $this->get('/loan/1');
        $response->assertStatus(200);
        $response->assertViewIs('loan.show');
        $response->assertViewHas(['data'], Loan::class);
        $response->assertViewHas(['paymentSchedule'], RepaymentSchedule::class);
    }

    public function testLoanEditView()
    {
        $response = $this->get('/loan/1/edit');
        $response->assertStatus(200);
        $response->assertViewIs('loan.edit');
    }

    public function testLoanUpdate()
    {
        $formData = [
            'loan_amount' => 987654321,
            'loan_term' => 6,
            'interest_rate' => 11.10,
            'year' => '2021',
            'month' => '1'
        ];
        $response = $this->put('/loan/1', $formData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('loans', [
            'loan_amount' => 987654321,
            'loan_term' => 6,
            'interest_rate' => 11.10,
            'start_date' => '2021-01-01',
        ]);
    }

    public function testLoanDelete()
    {
        $response = $this->delete('/loan/1');
        $response->assertStatus(302);
        $this->assertDatabaseCount('loans', 0);
    }
}
