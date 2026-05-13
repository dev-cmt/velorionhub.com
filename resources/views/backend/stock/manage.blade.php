<x-backend-layout title="Stock Adjustment">
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h1 class="page-title fw-semibold fs-18 mb-0">Stock Adjustment</h1>
        <p class="text-muted mb-0">Manually adjust inventory levels for corrections, damages, or returns.</p>
    </div>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('stock-manage.index') }}" class="text-primary">Manage Stock</a></li>
                <li class="breadcrumb-item active" aria-current="page">Adjustment</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card custom-card">
    <div class="card-header justify-content-between align-items-md-center">
        <div class="card-title">Inventory List</div>
        <div class="search-box">
            <input type="text" id="stockSearch" class="form-control form-control-sm border-0 bg-light px-3" 
                    placeholder="Search SKU or Product..." style="width: 250px;">
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="stockTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Product Name</th>
                        <th>SKU</th>
                        <th class="text-center">Current Stock</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Stock Adjustment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="stock-row">
                        <td class="ps-4">
                            <div class="fw-semibold text-dark fs-13">{{ $product->name }}</div>
                            <small class="text-muted">Min Alert: {{ $product->alert_quantity }}</small>
                        </td>
                        <td><span class="badge bg-light text-muted border">{{ $product->sku }}</span></td>
                        <td class="text-center">
                            <span class="fw-bold fs-15 stock-display" data-id="{{ $product->id }}">
                                {{ $product->total_stock }}
                            </span>
                        </td>
                        <td>
                            @if($product->total_stock <= 0)
                                <span class="badge bg-danger-transparent text-danger">Out of Stock</span>
                            @elseif($product->total_stock <= $product->alert_quantity)
                                <span class="badge bg-warning-transparent text-warning">Low Stock</span>
                            @else
                                <span class="badge bg-success-transparent text-success">Healthy</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <form onsubmit="updateStock(event, {{ $product->id }})" class="d-flex justify-content-end gap-1">
                                <select name="adjustment_type" class="form-select form-select-sm border-0 bg-light w-auto fs-12">
                                    <option value="add">Add</option>
                                    <option value="subtract">Sub</option>
                                </select>
                                <input type="number" name="quantity" class="form-control form-control-sm border-0 bg-light fs-12" 
                                       placeholder="Qty" style="width: 70px;" required>
                                <button type="submit" class="btn btn-primary btn-sm shadow-none">
                                    <i class="ri-check-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // 1. Instant Search Logic (Vanilla JS)
    document.getElementById('stockSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        document.querySelectorAll('.stock-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });

    // 2. AJAX Stock Update Logic
    async function updateStock(event, id) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        try {
            const response = await fetch(`{{ url('/update-stock') }}/${id}`, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            const result = await response.json();

            if (response.ok) {
                // Update the UI without reloading
                document.querySelector(`.stock-display[data-id="${id}"]`).innerText = result.new_stock;
                form.reset();
                // Optional: Trigger a success toast
                alert(result.message);
            } else {
                alert(result.message || 'Error updating stock');
            }
        } catch (error) {
            console.error(error);
            alert('Something went wrong.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ri-check-line"></i>';
        }
    }
</script>
</x-backend-layout>