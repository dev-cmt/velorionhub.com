<x-backend-layout title="New Purchase">
    @push('css')
        <link href="{{ asset('backend/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/libs/select2/select2.min.css') }}" rel="stylesheet" />
    @endpush

    @php
        $suppliers = $data['suppliers'] ?? [];
        $products  = $data['products'] ?? [];
        $stores  = $data['stores'] ?? [];
    @endphp

    {{-- ── Page Header ── --}}
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">
            <i class="bx bx-cart-add me-2"></i> New Purchase
        </h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.index') }}">Purchases</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('purchase.store') }}" method="POST" id="purchaseForm">
        @csrf
        <div class="row">
            <div class="col-md-12">
                {{-- ── Product Items ── --}}
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bx bx-package me-1"></i> Product Items
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
                                    <i class="bx bx-search-alt-2 me-1"></i> Search &amp; Add Product
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
                                    <tr class="no_data">
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bx bx-shopping-bag fs-4 d-block mb-1"></i>
                                            No products added yet. Search above to add.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end p-2"><b>Sub Total:</b></th>
                                        <td colspan="2" class="p-2">
                                            <input type="number" readonly class="form-control text-end fw-bold"
                                                name="sub_total" id="sub_total" value="0.00" step="0.01">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end p-2"><b>Discount (−):</b></th>
                                        <td colspan="2" class="p-2">
                                            <input type="number" class="form-control text-end"
                                                name="discount" id="discount" value="0.00" step="0.01" min="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end p-2 text-success"><b>Grand Total:</b></th>
                                        <td colspan="2" class="p-2">
                                            <input type="number" readonly class="form-control text-end fw-bold text-success"
                                                name="balance" id="balance" value="0.00" step="0.01">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                {{-- ── Payment ── --}}
                <div class="card custom-card mb-3">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bx bx-info-circle me-1"></i> Purchase Information
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
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                {{-- Supplier balance info --}}
                                <div id="supplier-info-box" class="alert alert-success py-2 px-3 mt-2 mb-0 small" style="display:none;">
                                    <i class="bx bx-wallet me-1"></i>
                                    Current Balance: <strong id="supplier-balance-text">—</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="date">
                                    Purchase Date <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="date" class="form-control" id="date" required placeholder="DD-MM-YYYY">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="store_id">
                                    Stores <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2" name="store_id" id="store_id" required>
                                    @foreach ($stores as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="memo_number">Memo / Ref Number</label>
                                <input type="text" class="form-control" name="memo_number" id="memo_number" placeholder="e.g. INV-2024-001">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="1" placeholder="Optional notes about this purchase..."></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold" for="status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2" name="status" id="status" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="0">Ordered</option>
                                    <option value="1">Received</option>
                                </select>
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
                            <i class="bx bx-credit-card me-1"></i> Payment Details
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
                                        <option value="{{ $id }}" {{ $id == old('payment_mode') ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="paid_amount" class="form-label fw-semibold">
                                    Paid Amount
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white">৳</span>
                                    <input type="number" class="form-control" name="paid_amount"
                                        id="paid_amount" value="0.00" step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="due_amount" class="form-label fw-semibold text-danger">Due Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-danger text-white">৳</span>
                                    <input type="number" class="form-control" name="due_amount"
                                        id="due_amount" value="0.00" step="0.01" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="note" class="form-label fw-semibold">Payment Note</label>
                                <textarea class="form-control" name="note" id="note" rows="2"
                                    placeholder="Optional note for this payment..."></textarea>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success px-5" id="submitBtn">
                                <i class="bx bx-save me-2"></i> Save Purchase
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
                defaultDate: new Date(),
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

            // ── Full Paid Button ──
            $(document).on('click', '#full_paid_btn', function() {
                $('#paid_amount').val($('#balance').val());
                recalculate();
            });

            $(document).ready(function() {
                $('.select2').select2({ width: '100%' });

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

                // ── Supplier Balance AJAX ──
                $(document).on('change', '#supplier_id', function() {
                    var sid = $(this).val();
                    if (!sid) { $('#supplier-info-box').hide(); return; }

                    $.ajax({
                        url: "{{ route('supplier.ajax.get.balance') }}",
                        type: "GET",
                        data: { supplier_id: sid },
                        success: function(balance) {
                            var bal = parseFloat(balance) || 0;
                            var box = $('#supplier-info-box');
                            $('#supplier-balance-text').text(bal.toLocaleString('en-BD', { minimumFractionDigits: 2 }));
                            box.removeClass('alert-success alert-danger');
                            box.addClass(bal > 0 ? 'alert-danger' : 'alert-success');
                            box.slideDown(200);
                        }
                    });
                });

                // ── Submit guard ──
                $('#purchaseForm').on('submit', function() {
                    if ($('tr.product_row').length === 0) {
                        Swal.fire({ icon: 'warning', title: 'No Products', text: 'Please add at least one product before saving.', timer: 2500, showConfirmButton: false });
                        return false;
                    }
                    $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Saving...');
                });
            });
        </script>
    @endpush
</x-backend-layout>
