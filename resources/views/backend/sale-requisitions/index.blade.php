<x-backend-layout title="Sale Requisitions Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Sale Requisitions Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sale Requisitions</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Sale Requisitions List</div>
                    <a href="{{ route('sale-requisitions.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Create New
                    </a>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Store</th>
                                    <th>Total</th>
                                    <th>Paid/Due</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($saleRequisitions as $key => $items)
                                    <tr>
                                        <td>{{ $saleRequisitions->firstItem() + $key }}</td>
                                        <td><a href="{{ route('sale-requisitions.edit', $items->id) }}" class="fw-bold text-primary">{{ $items->invoice_no }}</a></td>
                                        <td>
                                            <strong>{{ $items->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $items->customer_phone }}</small>
                                        </td>
                                        <td>{{ $items->store->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($items->total, 2) }}</td>
                                        <td>
                                            <span class="badge bg-success-transparent">Paid: ${{ number_format($items->paid, 2) }}</span><br>
                                            <span class="badge bg-danger-transparent">Due: ${{ number_format($items->due, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    0 => 'bg-warning-transparent',
                                                    1 => 'bg-success-transparent',
                                                    2 => 'bg-info-transparent',
                                                    3 => 'bg-danger-transparent',
                                                    4 => 'bg-primary-transparent',
                                                ][$items->status] ?? 'bg-secondary-transparent';
                                                
                                                $statusText = [
                                                    0 => 'Pending',
                                                    1 => 'Confirmed',
                                                    2 => 'Hold',
                                                    3 => 'Cancelled',
                                                    4 => 'Delivered',
                                                ][$items->status] ?? 'Unknown';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="{{ route('sale-requisitions.edit', $items) }}" class="btn btn-sm btn-warning-light btn-icon" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form action="{{ route('sale-requisitions.destroy', $items->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this sale requisitions and all its items?')" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No sale requisitions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $saleRequisitions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>