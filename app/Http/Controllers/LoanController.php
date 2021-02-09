<?php

namespace App\Http\Controllers;

use App\RepaymentSchedule;
use Illuminate\Http\Request;
use \App\Http\Requests\StoreAndUpdateRequest;
use App\Loan;
use \Datetime;
use MongoDB\BSON\Timestamp;

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
        $request->validated();

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

        echo(gettype($loanStartDate));

//      Loop create RepaymentSchedule
        $outstandingBalance = $loanAmount;
        $paymentNo = 1;
        while ($outstandingBalance > 0.1) {
            $interest = ($loanInterestRate / 100 / 12) * $outstandingBalance;
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
//        dd($paymentSchedule);
//        dd($paymentSchedule);

//        $LoanAmount = $data->loan_amount;
//        $loanTerm = $data->loan_term;
//        $interestRate = $data->interest_rate;
//        $PMT = $LoanAmount * ($interestRate / 12) / (1 - ((1 + ($interestRate / 12)) ** (-12 * $loanTerm)));
//
//        $outstandingBalance = $LoanAmount;
//        $datetime = new DateTime($data->start_date);
//        $paymentSchedule = array();
//        while ($outstandingBalance > 0.1) {
//            $interest = ($interestRate / 12) * $outstandingBalance;
//            $outstandingBalance = $outstandingBalance - ($PMT - $interest);
//            $payment = [
//                "datetime" => date_format($datetime, 'M Y'),
//                "outstanding_balance" => $outstandingBalance,
//                "payment_amount" => $PMT,
//                "interest" => $interest,
//                "principal" => ($PMT - $interest)
//
//            ];
//            array_push($paymentSchedule, $payment);
//            date_add($datetime, date_interval_create_from_date_string('1 months'));
//        }
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
        $validated = $request->validated();
        Loan::find($id)->update([
            'loan_amount' => $request->loan_amount,
            'loan_term' => $request->loan_term,
            'interest_rate' => $request->interest_rate,
            'start_date' => $request->year . '-' . $request->month . '-01',
        ]);
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
        return redirect('/loan');
    }
}
