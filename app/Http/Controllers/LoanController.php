<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $data=Loan::all();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'loan_amount'=>'required|integer',
            'loan_term'=>'required|integer|between:1,50',
            'interest_rate'=> ['required', 'regex:/^([1-9]|3[0-5]|[12]\d{1,2}|36\.00){1}(\.[0-9]{1,2})?$/'],
            'month'=>'required|integer|between:1,12',
            'year'=>'required|integer|between:2017,2050'
        ]);
        Loan::create([
            'loan_amount'=>$request->loan_amount,
            'loan_term'=>$request->loan_term,
            'interest_rate'=>$request->interest_rate,
            'start_date'=>$request->year.'-'.$request->month.'-01',
        ]
        );
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
        $data=Loan::find($id);

        $loan_amount=$data->loan_amount;
        $loan_term=$data->loan_term;
        $interest_rate=$data->interest_rate/100;
        $PMT= $loan_amount*($interest_rate/12) / (1-((1+($interest_rate/12))**(-12*$loan_term)));

        $outstanding_balance=$loan_amount;
        $datetime=new DateTime($data->start_date);
        $payment_schedule = array();
        while($outstanding_balance>0.1){
            $interest=($interest_rate/12)*$outstanding_balance;
            $outstanding_balance = $outstanding_balance - ($PMT-$interest);
            $payment = [
                "datetime"=>date_format($datetime, 'M Y'),
                "outstanding_balance"=>$outstanding_balance,
                "payment_amount"=>$PMT,
                "interest"=>$interest,
                "principal"=>($PMT-$interest)

            ];
            array_push($payment_schedule, $payment);
            date_add($datetime, date_interval_create_from_date_string('1 months'));
        }
        return view('loan.show', compact(['data', 'payment_schedule']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Loan::find($id);
        $datetime=new DateTime($data->start_date);
        $data->year=$datetime->format('Y');
        $data->month=$datetime->format('n');

        return view('loan.edit', compact(['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'loan_amount'=>'required|integer',
            'loan_term'=>'required|integer|between:1,50',
            'interest_rate'=> ['required', 'regex:/^([1-9]|3[0-5]|[12]\d{1,2}|36\.00){1}(\.[0-9]{1,2})?$/'],
            'month'=>'required|integer|between:1,12',
            'year'=>'required|integer|between:2017,2050'
        ]);
        Loan::find($id)->update([
            'loan_amount'=>$request->loan_amount,
            'loan_term'=>$request->loan_term,
            'interest_rate'=>$request->interest_rate,
            'start_date'=>$request->year.'-'.$request->month.'-01',
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
