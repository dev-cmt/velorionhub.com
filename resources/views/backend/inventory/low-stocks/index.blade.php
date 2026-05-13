<x-backend-layout title="Low Stock Management">

<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h1 class="page-title fw-semibold fs-18 mb-1">Low Stock Management</h1>
        <p class="text-muted mb-0">Monitor and manage products that are below critical stock levels.</p>
    </div>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Low Stocks</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <div class="card-title mb-0">
                    <i class='bx bx-error-alt me-1 align-middle text-warning fs-20'></i>
                    Inventory Alerts <span class="badge bg-warning-transparent ms-2 rounded-pill">{{ $lowStocks->count() }}</span>
                </div>
                <div class="btn-list">
                    <button id="notifySelected" class="btn btn-primary-light btn-sm btn-wave shadow-none">
                        <i class="ri-notification-3-line me-1"></i> Notify Selected
                    </button>
                    <button class="btn btn-outline-light btn-wave btn-sm shadow-none text-dark border">
                        <i class="ri-download-2-line"></i> Export
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div id="alertMsg" class="px-3 mt-3"></div>

                @if($lowStocks->isEmpty())
                    <div class="p-5 text-center">
                        <div class="avatar avatar-xl bg-success-transparent mb-3">
                            <i class="ri-checkbox-circle-line fs-40 text-success"></i>
                        </div>
                        <h5 class="fw-semibold">Inventory is Healthy!</h5>
                        <p class="text-muted mx-auto" style="max-width: 400px;">All products currently exceed their minimum alert quantities. No action is required at this time.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="lowStockTable" class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="40" class="ps-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </div>
                                    </th>
                                    <th>Product Information</th>
                                    <th>SKU</th>
                                    <th class="text-center">Current Stock</th>
                                    <th class="text-center">Min. Threshold</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStocks as $product)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="form-check">
                                                <input type="checkbox" class="productCheckbox form-check-input" value="{{ $product->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($product->main_image)
                                                        <img src="{{ asset('storage/' . $product->main_image) }}" 
                                                             class="rounded border shadow-sm p-1 bg-white" 
                                                             style="width: 42px; height: 42px; object-fit: cover;">
                                                    @else
                                                        <div class="avatar avatar-md bg-light text-muted border border-dashed rounded">
                                                            <i class="ri-image-line"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="fw-semibold mb-0 fs-14 text-dark">{{ $product->name }}</h6>
                                                    <small class="text-muted">Updated: {{ $product->updated_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-muted border fw-medium">{{ $product->sku }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold fs-15 {{ $product->total_stock == 0 ? 'text-danger' : 'text-dark' }}">
                                                {{ $product->total_stock }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-muted fw-medium">{{ $product->alert_quantity }}</span>
                                        </td>
                                        <td>
                                            @if($product->total_stock == 0)
                                                <span class="badge bg-danger-transparent border border-danger text-danger px-2 py-1">
                                                    <i class="ri-close-circle-fill me-1 align-middle"></i>Out of Stock
                                                </span>
                                            @else
                                                <span class="badge bg-warning-transparent border border-warning text-warning px-2 py-1">
                                                    <i class="ri-error-warning-fill me-1 align-middle"></i>Low Stock
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white border-top-0 py-3">
                <p class="text-muted mb-0 fs-12 italic">* Min. Threshold is based on the 'Alert Quantity' set in the product settings.</p>
            </div>
        </div>
    </div>
</div>

{{-- Pass Routes to JS --}}
<script>
    window.routes = {
        notifyLowStock: "{{ route('low-stocks.notify') }}"
    };
</script>

@push('js')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with better UI defaults
    if ($('#lowStockTable').length) {
        $('#lowStockTable').DataTable({
            pageLength: 20,
            ordering: true,
            language: {
                searchPlaceholder: 'Search products...',
                sSearch: '',
            },
            columnDefs: [
                { orderable: false, targets: [0, 1, 5] } // Disable sorting for checkbox, image, and status
            ]
        });
    }

    // Select all checkboxes logic
    $('#selectAll').on('change', function() {
        $('.productCheckbox').prop('checked', $(this).is(':checked'));
    });

    // Handle individual checkbox change
    $(document).on('change', '.productCheckbox', function() {
        const total = $('.productCheckbox').length;
        const checked = $('.productCheckbox:checked').length;
        $('#selectAll').prop('checked', total === checked);
    });

    // Notify selected products via AJAX
    $('#notifySelected').on('click', function() {
        const selected = $('.productCheckbox:checked').map(function() { return $(this).val(); }).get();

        if(selected.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Selection',
                text: 'Please select at least one product to notify.',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Sending...');

        $.post(window.routes.notifyLowStock, {
            _token: "{{ csrf_token() }}",
            products: selected
        })
        .done(res => {
            Swal.fire('Success', res.message || 'Notifications sent successfully!', 'success');
        })
        .fail(err => {
            Swal.fire('Error', err.responseJSON?.message || 'Something went wrong', 'error');
        })
        .always(() => {
            $btn.prop('disabled', false).html('<i class="ri-notification-3-line me-1"></i> Notify Selected');
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

</x-backend-layout>