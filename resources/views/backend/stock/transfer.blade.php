<x-backend-layout title="Stock Transfer">

<div class="d-md-flex d-block align-items-center justify-content-between my-4">
    <div>
        <h1 class="page-title fw-semibold fs-18 mb-1">Stock Transfer System</h1>
        <p class="text-muted mb-0">Move inventory between warehouses with multi-step approval.</p>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card custom-card shadow-sm border-0">
            <div class="card-header">
                <div class="card-title">New Transfer Request</div>
            </div>
            <div class="card-body">
                <form action="{{ route('stock-transfer.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fs-13">Select Product</label>
                        <select name="product_id" class="form-select border-0 bg-light" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} (Available: {{ $product->total_stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fs-13">From Store</label>
                            <select name="from_store_id" class="form-select border-0 bg-light" required>
                                @foreach($stores as $store) <option value="{{ $store->id }}">{{ $store->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-13">To Store</label>
                            <select name="to_store_id" class="form-select border-0 bg-light" required>
                                @foreach($stores as $store) <option value="{{ $store->id }}">{{ $store->name }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13">Transfer Quantity</label>
                        <input type="number" name="quantity" class="form-control border-0 bg-light" placeholder="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Initialize Transfer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card custom-card shadow-sm border-0 overflow-hidden">
            <div class="card-header bg-white py-3">
                <div class="card-title mb-0">Transfer History & Approvals</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Route</th>
                                <th class="text-center">Qty</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfers as $t)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $t->product->name }}</div>
                                    <small class="text-muted">ID: #TR-{{ $t->id }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center fs-12">
                                        <span class="text-muted">{{ $t->fromStore->name }}</span>
                                        <i class="ri-arrow-right-line mx-2 text-primary"></i>
                                        <span class="fw-bold">{{ $t->toStore->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $t->quantity }}</td>
                                <td>
                                    @if($t->status == 'pending')
                                        <span class="badge bg-warning-transparent px-2">Pending Approval</span>
                                    @elseif($t->status == 'approved')
                                        <span class="badge bg-success-transparent px-2">Approved</span>
                                    @else
                                        <span class="badge bg-danger-transparent px-2">Rejected</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($t->status == 'pending')
                                    <div class="btn-group">
                                        <button onclick="processTransfer({{ $t->id }}, 'approved')" class="btn btn-sm btn-success-light">
                                            <i class="ri-check-line"></i>
                                        </button>
                                        <button onclick="processTransfer({{ $t->id }}, 'rejected')" class="btn btn-sm btn-danger-light">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                    @else
                                        <small class="text-muted italic">{{ ucfirst($t->status) }} on {{ $t->updated_at->format('d M') }}</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function processTransfer(id, status) {
        if(!confirm(`Are you sure you want to ${status} this transfer?`)) return;

        try {
            const response = await fetch(`{{ url('/update-transfer') }}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            });

            const res = await response.json();
            alert(res.message);
            location.reload();
        } catch (error) {
            alert('Error processing request.');
        }
    }
</script>
</x-backend-layout>