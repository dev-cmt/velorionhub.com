<x-backend-layout title="Supplier Management">

    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Supplier Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
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
                                @foreach ($supplier->transactions as $tx)
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

        </div>
    </div>
</x-backend-layout>
