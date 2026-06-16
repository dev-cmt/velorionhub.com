@extends('backEnd.layouts.master')
@section('title') Suppliers @endsection

@section('css')
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{asset('backEnd/assets/libs/sweetalert2/sweetalert2.min.css')}}">

    <link href="{{asset('backEnd/assets/libs/select2/select2.min.css')}}" rel="stylesheet"/>
@endsection

@section('body')
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Supplier Report - {{ $supplier->name }}
            </div>
        </div>
        <div class="card-body">
            <p>Email: {{ $supplier->email ?? 'N/A' }}</p>
            <p>Phone: {{ $supplier->phone }}</p>
            <p>Current Balance: <strong>{{ number_format($supplier->balance, 2) }}</strong></p>
            <p>Total Purchases: <strong>{{ number_format($totalPurchases, 2) }}</strong></p>
            <p>Total Payments: <strong>{{ number_format($totalPayments, 2) }}</strong></p>

            <div class="table-responsive">
                <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Previous Balance</th>
                        <th>New Balance</th>
                        <th>Notes</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->transactions as $tx)
                            <tr>
                                <td>{{ $tx->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ ucfirst($tx->type) }}</td>
                                <td>{{ number_format($tx->amount, 2) }}</td>
                                <td>{{ number_format($tx->previous_balance, 2) }}</td>
                                <td>{{ number_format($tx->new_balance, 2) }}</td>
                                <td>{{ $tx->notes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

