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
    <h1>Create Loan</h1>
    {!! Form::open(['action' => 'LoanController@store', 'method'=>'POST']) !!}
        <div class="col-md-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('loan_amount', 'Loan Amount : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('loan_amount', null,["class"=>"form-control"]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('loan_term', 'Loan Term : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('loan_term', null,["class"=>"form-control"]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        {!! Form::label('interest_rate', 'Interest Rate : ', ['class' => 'float-right']) !!}
                    </div>
                    <div class="col-md-9">
                        {!! Form::text('interest_rate', null,["class"=>"form-control"]) !!}
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
                                {!! Form::selectMonth('month', null,["class"=>"form-control"]); !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::selectRange('year', 2017, 2050, 2020,["class"=>"form-control"]); !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="submit" value="Create" class="btn btn-primary">
            <a href="/loan" class="btn btn-secondary">Back</a>
        </div>
    {!! Form::close() !!}
    </div>
@endsection