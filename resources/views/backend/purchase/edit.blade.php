<x-backend-layout title="Edit Purchase">
    @push('css')
        <link href="{{ asset('backend/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/libs/select2/select2.min.css') }}" rel="stylesheet" />
    @endpush

    @php
        $purchase  = $data['purchase'] ?? null;
        $suppliers = $data['suppliers'] ?? [];
        $products  = $data['products'] ?? [];
        $stores  = $data['stores'] ?? [];
    @endphp

    {{-- ── Page Header ── --}}
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">
            <i class="bx bx-edit-alt me-2"></i> Edit Purchase
        </h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.index') }}">Purchases</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit #{{ $purchase->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('purchase.update') }}" method="POST" id="purchaseForm">
        @csrf
        <input type="hidden" name="purchase_id" id="purchase_id" value="{{ $purchase->id }}">
        <div class="row">
            <div class="col-md-12">
                {{-- ── Product Items ── --}}
                <div class="card custom-card mb-3">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bx bx-package me-1"></i> Product Items
                            @php $itemCount = $purchase->purchase_items->count(); @endphp
                            <span class="badge bg-warning-transparent ms-2">{{ $itemCount }} item{{ $itemCount != 1 ? 's' : '' }}</span>
                        </div>

                        <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-danger">
                            <i class="ti ti-chevron-left"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        {{-- Product search --}}
                        <div class="row d-flex justify-content-center mb-3">
                            <div class="col-md-10">
                                <label class="form-label fw-semibold" for="select_product">
                                    <i class="bx bx-search-alt-2 me-1"></i> Add More Products
                                </label>
                                <div class="input-group">
                                    <select class="form-select select2" name="select_product" id="select_product">
                                        <option value="" selected>-- Select Product --</option>
                                        @foreach ($products as $key => $product)
                                            <option value="{{ $key }}">{{ $product }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Items table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap" id="itemTable">
                                <thead>
                                    <tr class="bg-primary text-white">
                                        <th width="42%">Product Name</th>
                                        <th width="12%">Qty</th>
                                        <th width="13%">Cost Price</th>
                                        <th width="13%">Sell Price</th>
                                        <th width="13%">Total</th>
                                        <th width="7%" class="text-center">Del</th>
                                    </tr>
                                </thead>

                                <tbody id="product-row-put">
                                    @if ($purchase->purchase_items->count() > 0)
                                        @foreach ($purchase->purchase_items->groupBy('product_id') as $prodId => $items)
                                            @php $firstItem = $items[0]; $hasVariant = optional($firstItem->get_product)->has_variant; @endphp

                                            @if ($hasVariant)
                                                {{-- Variant product row --}}
                                                <tr class="item-row-template product_row">
                                                    <td colspan="4">
                                                        <table class="table table-bordered mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%"></th>
                                                                    <th width="55%">Variant / SKU</th>
                                                                    <th width="13%">Qty</th>
                                                                    <th width="13%">Cost</th>
                                                                    <th width="14%">Sell Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <input type="hidden" name="product_id[]" value="{{ $prodId }}">
                                                                @foreach ($items as $k => $item)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <i class="remove_btn bi bi-x-circle" style="cursor:pointer; color:#ef4444; font-size:1.2rem;"></i>
                                                                        </td>
                                                                        <td class="p-1">
                                                                            <input type="hidden" name="sku[{{ $item->product_id }}][]" value="{{ $item->sku }}">
                                                                            <small class="text-info fw-semibold">#{{ $item->sku }}</small><br>
                                                                            <small class="text-muted">{{ optional($item->get_product)->name }}</small>
                                                                        </td>
                                                                        <td class="qty_section p-1">
                                                                            <input type="number"
                                                                                class="form-control form-control-sm quantity qty_{{ $item->product_id }}"
                                                                                name="quantity[{{ $item->product_id }}][]"
                                                                                data-id="{{ $item->product_id }}"
                                                                                id="quantity_{{ $k }}"
                                                                                value="{{ $item->purchase_qty }}" min="1" required>
                                                                        </td>
                                                                        <input type="hidden" class="sub_total_qty" name="sub_total_qty[{{ $item->product_id }}]">
                                                                        <td class="purchase_cost_section p-1">
                                                                            <input type="number"
                                                                                class="form-control form-control-sm purchase_cost prch_cost_{{ $item->product_id }}"
                                                                                name="purchase_cost[{{ $item->product_id }}][]"
                                                                                data-id="{{ $item->product_id }}"
                                                                                id="purchase_cost_{{ $k }}"
                                                                                value="{{ number_format($item->purchase_cost, 2, '.', '') }}"
                                                                                min="0" step="0.01" required>
                                                                            <input type="hidden" class="put_sub_toot" name="put_sub_toot[{{ $item->product_id }}][]">
                                                                        </td>
                                                                        <input type="hidden" class="sub_total_purchase_cost">
                                                                        <td class="sell_price_section p-1">
                                                                            <input type="number"
                                                                                class="form-control form-control-sm sell_price sell_prc_{{ $item->product_id }}"
                                                                                name="sell_price[{{ $item->product_id }}][]"
                                                                                data-id="{{ $item->product_id }}"
                                                                                id="sell_price_{{ $k }}"
                                                                                value="{{ number_format($item->sell_price, 2, '.', '') }}"
                                                                                min="0" step="0.01" required>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <input type="hidden" class="sub_total_sell_price">
                                                    <td>
                                                        <input type="number"
                                                            class="form-control form-control-sm total"
                                                            name="total[{{ $prodId }}]"
                                                            value="{{ number_format($items->sum('total'), 2, '.', '') }}"
                                                            step="0.01" required>
                                                    </td>
                                                    <td class="text-center">
                                                        <i class="remove_btn bi bi-x-circle" style="cursor:pointer; color:#ef4444; font-size:1.2rem;"></i>
                                                    </td>
                                                </tr>
                                            @else
                                                {{-- Simple product rows --}}
                                                @foreach ($items as $k => $item)
                                                    <tr class="item-row-template product_row">
                                                        <td>
                                                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                                            <input type="hidden" name="sku[{{ $item->product_id }}][]" value="{{ $item->sku }}">
                                                            <small class="text-info fw-semibold">#{{ $item->sku }}</small><br>
                                                            <span class="fw-semibold">{{ optional($item->get_product)->name }}</span>
                                                        </td>
                                                        <td class="qty_section">
                                                            <input type="number"
                                                                class="form-control form-control-sm quantity"
                                                                name="quantity[{{ $item->product_id }}][]"
                                                                value="{{ $item->purchase_qty }}" min="1" required>
                                                        </td>
                                                        <input type="hidden" class="sub_total_qty" name="sub_total_qty[{{ $item->product_id }}]">
                                                        <td class="purchase_cost_section">
                                                            <input type="number"
                                                                class="form-control form-control-sm purchase_cost"
                                                                name="purchase_cost[{{ $item->product_id }}][]"
                                                                value="{{ number_format($item->purchase_cost, 2, '.', '') }}"
                                                                min="0" step="0.01" required>
                                                            <input type="hidden" class="put_sub_toot" name="put_sub_toot[{{ $item->product_id }}][]">
                                                        </td>
                                                        <input type="hidden" class="sub_total_purchase_cost">
                                                        <td class="sell_price_section">
                                                            <input type="number"
                                                                class="form-control form-control-sm sell_price"
                                                                name="sell_price[{{ $item->product_id }}][]"
                                                                value="{{ number_format($item->sell_price, 2, '.', '') }}"
                                                                min="0" step="0.01" required>
                                                        </td>
                                                        <input type="hidden" class="sub_total_sell_price">
                                                        <td>
                                                            <input type="number"
                                                                class="form-control form-control-sm total"
                                                                name="total[{{ $item->product_id }}]"
                                                                value="{{ number_format($item->total, 2, '.', '') }}"
                                                                step="0.01" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <i class="remove_btn bi bi-x-circle" style="cursor:pointer; color:#ef4444; font-size:1.2rem;"></i>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @else
                                        <tr class="no_data">
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="bx bx-shopping-bag fs-4 d-block mb-1"></i>
                                                No products yet. Search above to add.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end p-2"><b>Sub Total:</b></th>
                                        <td colspan="2" class="p-2">
                                            <input type="number" readonly class="form-control text-end fw-bold"
                                                name="sub_total" id="sub_total"
                                                value="{{ number_format($purchase->sub_total, 2, '.', '') }}"
                                                step="0.01">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end p-2"><b>Discount (−):</b></th>
                                        <td colspan="2" class="p-2">
                                            <input type="number" class="form-control text-end"
                                                name="discount" id="discount"
                                                value="{{ number_format($purchase->discount, 2, '.', '') }}"
                                                step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end p-2 text-success"><b>Grand Total:</b></th>
                                        <td colspan="2" class="p-2">
                                            <input type="number" readonly class="form-control text-end fw-bold text-success"
                                                name="balance" id="balance"
                                                value="{{ number_format($purchase->total, 2, '.', '') }}"
                                                step="0.01">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                {{-- ── Purchase Info ── --}}
                <div class="card custom-card mb-3">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bx bx-info-circle me-1"></i> Purchase Information
                            <span class="badge bg-secondary ms-2">
                                #{{ $purchase->id }} &mdash; {{ date('d M Y', strtotime($purchase->date)) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="supplier_id">
                                    Supplier <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2" name="supplier_id" id="supplier_id" required>
                                    <option value="">-- Select Supplier --</option>
                                    @foreach ($suppliers as $id => $name)
                                        <option value="{{ $id }}" {{ $id == $purchase->supplier_id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="date">
                                    Purchase Date <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="date" class="form-control" id="date"
                                    value="{{ date('d-m-Y', strtotime($purchase->date)) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="store_id">
                                    Store <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2" name="store_id" id="store_id" required>
                                    <option value="">-- Select Store --</option>
                                    @foreach ($stores as $id => $name)
                                        <option value="{{ $id }}" {{ $id == $purchase->store_id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="memo_number">Memo / Ref Number</label>
                                <input type="text" class="form-control" name="memo_number" id="memo_number"
                                    value="{{ $purchase->memo_number }}" placeholder="e.g. INV-2024-001">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="1"
                                    placeholder="Optional notes...">{{ $purchase->remarks }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2" name="status" id="status" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="0" {{ $purchase->status == 0 ? 'selected' : '' }}>Ordered</option>
                                    <option value="1" {{ $purchase->status == 1 ? 'selected' : '' }}>Received</option>
                                </select>
                                <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small" id="statusWarning" style="display:none;">
                                    <i class="bx bx-error-circle me-1"></i>
                                    Changing to <strong>Received</strong> will update inventory stock.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                {{-- ── Payment ── --}}
                <div class="card custom-card mb-3">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bx bx-credit-card me-1"></i> Add Payment
                        </div>
                        <button type="button" id="full_paid_btn" class="btn btn-sm btn-info">
                            <i class="bx bx-check-circle me-1"></i> Full Paid?
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="payment_mode" class="form-label fw-semibold">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="payment_mode" id="payment_mode">
                                    <option value="">-- Select Payment Method --</option>
                                    @foreach ($data['payment_methods'] as $id => $name)
                                        <option value="{{ $id }}" {{ $id == $purchase->payment_mode ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="paid_amount" class="form-label fw-semibold">Paid Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">৳</span>
                                    <input type="number" class="form-control" name="paid_amount"
                                        id="paid_amount"
                                        value="{{ number_format($purchase->paid_amount, 2, '.', '') }}"
                                        step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="due_amount" class="form-label fw-semibold text-danger">Due Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white">৳</span>
                                    <input type="number" class="form-control" name="due_amount"
                                        id="due_amount"
                                        value="{{ number_format($purchase->due_amount, 2, '.', '') }}"
                                        step="0.01" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="note" class="form-label fw-semibold">Payment Note</label>
                                <textarea class="form-control" name="note" id="note" rows="2"
                                    placeholder="Optional note for this payment..."></textarea>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-warning px-5" id="submitBtn">
                                <i class="bx bx-save me-2"></i> Update Purchase
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    @push('js')
        <script src="{{ asset('backend/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('backend/libs/select2/select2.min.js') }}"></script>
        <script>
            // ── Date Picker ──
            flatpickr("#date", {
                dateFormat: "d-m-Y",
                allowInput: true
            });

            // ── Totals Calculator ──
            function recalculate() {
                $('.quantity').each(function(index) {
                    var qty  = parseFloat($(this).val()) || 0;
                    var cost = parseFloat($('.purchase_cost').eq(index).val()) || 0;
                    $('.put_sub_toot').eq(index).val((qty * cost).toFixed(2));
                });

                $('tr.product_row').each(function() {
                    var sum = 0;
                    $('.put_sub_toot', this).each(function() { sum += parseFloat($(this).val()) || 0; });
                    $('.total', this).val(sum.toFixed(2));
                });

                var subTotal = 0;
                $('.total').each(function() { subTotal += parseFloat($(this).val()) || 0; });
                $('#sub_total').val(subTotal.toFixed(2));

                var discount   = parseFloat($('#discount').val()) || 0;
                var grandTotal = Math.max(subTotal - discount, 0);
                $('#balance').val(grandTotal.toFixed(2));

                var paid = parseFloat($('#paid_amount').val()) || 0;
                var due  = Math.max(grandTotal - paid, 0);
                $('#due_amount').val(due.toFixed(2));
            }

            // ── Full Paid ──
            $(document).on('click', '#full_paid_btn', function() {
                $('#paid_amount').val($('#balance').val());
                recalculate();
            });

            $(document).ready(function() {
                $('.select2').select2({ width: '100%' });

                // Initial recalculate on page load
                recalculate();

                // Status change warning
                $('#status').on('change', function() {
                    if ($(this).val() === '1') {
                        $('#statusWarning').slideDown(200);
                    } else {
                        $('#statusWarning').slideUp(200);
                    }
                });

                // ── Add Product via AJAX ──
                $(document).on('change', '#select_product', function() {
                    var productId = $(this).val();
                    if (!productId) return;

                    var existing = [];
                    $('input[name="product_id[]"]').each(function() { existing.push($(this).val()); });
                    if (existing.includes(productId)) {
                        Swal.fire({ icon: 'warning', title: 'Already Added', text: 'This product is already in the list.', timer: 2000, showConfirmButton: false });
                        $(this).val(null).trigger('change');
                        return;
                    }

                    var self = this;
                    $.ajax({
                        url: '{{ route('admin.ajax.get.products') }}',
                        type: 'GET',
                        data: { id: productId, _token: '{{ csrf_token() }}' },
                        success: function(html) {
                            $('.no_data').hide();
                            $('#product-row-put').append(html);
                            recalculate();
                            $(self).val(null).trigger('change');
                        },
                        error: function() {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load product data.', timer: 2000, showConfirmButton: false });
                        }
                    });
                });

                // ── Remove Row ──
                $(document).on('click', '.remove_btn', function() {
                    $(this).closest('tr.product_row').remove();
                    if ($('tr.product_row').length === 0) { $('.no_data').show(); }
                    recalculate();
                });

                // ── Input Changes ──
                $(document).on('keyup change input', '.quantity, .purchase_cost, .sell_price, #discount, #paid_amount', function() {
                    recalculate();
                });

                // ── Submit Guard ──
                $('#purchaseForm').on('submit', function() {
                    if ($('tr.product_row').length === 0) {
                        Swal.fire({ icon: 'warning', title: 'No Products', text: 'Please add at least one product before updating.', timer: 2500, showConfirmButton: false });
                        return false;
                    }
                    $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Updating...');
                });
            });
        </script>
    @endpush
</x-backend-layout>
