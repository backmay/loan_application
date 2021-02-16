<?php

namespace App\Http\Controllers;

use App\RepaymentSchedule;
use Illuminate\Http\Request;
use \App\Http\Requests\StoreAndUpdateRequest;
use App\Loan;
use \Datetime;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Loan::all();
        return view('loan.index', compact(['data']));
        // return view('loan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('loan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAndUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAndUpdateRequest $request)
    {
        $loanAmount = $request->input('loan_amount');
        $loanTerm = $request->input('loan_term');
        $loanInterestRate = $request->input('interest_rate');
        $loanStartDate = new DateTime( $request->input('year') . '-' . $request->input('month') . '-01');
        $pmt = $loanAmount * ($loanInterestRate/100/12) / (1 - ((1 + ($loanInterestRate/100/12)) ** (-12 * $loanTerm)));

//      Create Loan
        $loan = new Loan;
        $loan->loan_amount = $loanAmount;
        $loan->loan_term = $loanTerm;
        $loan->interest_rate = $loanInterestRate;
        $loan->start_date = $loanStartDate;
        $loan->pmt = $pmt;
        $loan->save();

        $this->createRepaymentSchedule($loan);

        return redirect('/loan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Loan::find($id);
        $paymentSchedule = RepaymentSchedule::where('loan_id', $id)->get();
        return view('loan.show', compact(['data', 'paymentSchedule']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Loan::find($id);
        $dateTime = new DateTime($data->start_date);
        $data->year = $dateTime->format('Y');
        $data->month = $dateTime->format('n');

        return view('loan.edit', compact(['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreAndUpdateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAndUpdateRequest $request, $id)
    {
        $loanAmount = $request->input('loan_amount');
        $loanTerm = $request->input('loan_term');
        $loanInterestRate = $request->input('interest_rate');
        $loanStartDate = new DateTime( $request->input('year') . '-' . $request->input('month') . '-01');
        $pmt = $loanAmount * ($loanInterestRate/100/12) / (1 - ((1 + ($loanInterestRate/100/12)) ** (-12 * $loanTerm)));

        $loan = Loan::find($id);
        $loan->update([
            'loan_amount' => $loanAmount,
            'loan_term' => $loanTerm,
            'interest_rate' => $loanInterestRate,
            'start_date' => $loanStartDate,
            'pmt' => $pmt,
        ]);

        RepaymentSchedule::where('loan_id', $id)->delete();
        $this->createRepaymentSchedule($loan);

        return redirect('/loan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Loan::find($id)->delete();
        RepaymentSchedule::where('loan_id', $id)->delete();
        return redirect('/loan');
    }

    public function createRepaymentSchedule($loan)
    {
        //      Loop create RepaymentSchedule
        $outstandingBalance = $loan->loan_amount;
        $loanStartDate = $loan->start_date;
        $paymentNo = 1;
        while ($outstandingBalance > 0.1) {
            $interest = ($loan->interest_rate / 100 / 12) * $outstandingBalance;
            $outstandingBalance = $outstandingBalance - ($loan->pmt - $interest);
            $repaymentScheduler = new RepaymentSchedule;
            $repaymentScheduler->payment_no = $paymentNo;
            $repaymentScheduler->payment_date = $loanStartDate;
            $repaymentScheduler->balance = $outstandingBalance;
            $repaymentScheduler->principal = ($loan->pmt - $interest);
            $repaymentScheduler->interest = $interest;
            $repaymentScheduler->loan()->associate($loan);
            $repaymentScheduler->save();
            date_add($loanStartDate, date_interval_create_from_date_string('1 months'));
            $paymentNo = $paymentNo + 1;
        }
    }
}
