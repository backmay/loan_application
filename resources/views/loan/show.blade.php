@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Loan Details</h1>
    <table class="table table-sm table-borderless col-md-4">
        <tbody>
            <tr>
                <td>ID:</td>
                <td>{{$data->id}}</td>
            </tr>
            <tr>
                <td>Loan Amount:</td>
                <td>{{ number_format($data->loan_amount, 2) }} ฿</td>
            </tr>
            <tr>
                <td>Loan Term:</td>
                <td>{{ number_format($data->loan_term, 0) }} Years</td>
            </tr>
            <tr>
                <td>Interest Rate:</td>
                <td>{{ number_format($data->interest_rate, 2) }} %</td>
            </tr>
            <tr>
                <td>Created at:</td>
                <td>{{ $data->created_at}}</td>
            </tr>
        </tbody>
    </table>
    <a href="/loan" class="btn btn-secondary my-2">Back</a>
    <br/><br/>
    <h1>Repayment Schedules</h1>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Payment No</th>
                <th scope="col">Date</th>
                <th scope="col">Payment Amount</th>
                <th scope="col">Principal</th>
                <th scope="col">Interest</th>
                <th scope="col">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment_schedule as $attribute => $value)
            <tr>
                <td>{{ $attribute + 1}}</td>
                <td>{{ $value['datetime'] }}</td>
                <td>{{ number_format($value['payment_amount'], 2) }}</td>
                <td>{{ number_format($value['principal'], 2) }}</td>
                <td>{{ number_format($value['interest'], 2) }}</td>
                <td>{{ number_format($value['outstanding_balance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="/loan" class="btn btn-secondary my-2">Back</a>
    <br/><br/>
</div>
@endsection