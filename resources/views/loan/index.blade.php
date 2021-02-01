@extends('layouts.app')
@section('content')
<div class="container">
    <h2>All Loans</h2>
    <a href="/loan/create" class="btn btn-primary my-2">Add new loan</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Loan Amount</th>
                <th scope="col">Loan Term</th>
                <th scope="col">Interest rate</th>
                <th scope="col">Created at</th>
                <th scope="col">View</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->all() as $loan)
            <tr>
                <th scope="row">{{ $loan->id }}</th>
                <td>{{ number_format($loan->loan_amount, 2) }} ฿</td>
                <td>{{ number_format($loan->loan_term, 0) }} Years</td>
                <td>{{ number_format($loan->interest_rate, 2) }} %</td>
                <td>{{ $loan->created_at }}</td>
                <td><a href="{{route('loan.show',$loan->id)}}" class="btn btn-info">VIEW</a></td>
                <td><a href="{{route('loan.edit',$loan->id)}}" class="btn btn-success">EDIT</a></td>
                <td>
                    <form action="{{route('loan.destroy', $loan->id)}}" method="POST">
                        @csrf @method('DELETE')
                        <input type="submit" value="DELETE" data-name="{{$loan->name}}" class="btn btn-danger deleteForm">
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection