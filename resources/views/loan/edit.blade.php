@extends('layouts.app')
@section('content')
    <div class="container">
    @if ($errors->all())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>
                {{$error}}
            </li>
        @endforeach
    </ul>
    @endif
    <h1>Edit Loan</h1>
    {!! Form::open(['action' => ['LoanController@update', $data->id], 'method'=>'PUT']) !!}
        <div class="col-md-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('loan_amount', 'Loan Amount : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('loan_amount', number_format($data->loan_amount, 0, '', ''), ["class"=>"form-control"]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('loan_term', 'Loan Term : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('loan_term', number_format($data->loan_term, 0), ["class"=>"form-control"]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('interest_rate', 'Interest Rate : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('interest_rate', number_format($data->interest_rate, 2), ["class"=>"form-control"]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('start_date', 'Start Date : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::selectMonth('month', $data->month, ["class"=>"form-control"]); !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::selectRange('year', 2017, 2050, $data->year, ["class"=>"form-control"]); !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" value="Update" class="btn btn-primary">
            <a href="/loan" class="btn btn-secondary">Back</a>
        </div>
    {!! Form::close() !!}
    </div>
@endsection
