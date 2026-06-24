<x-backend-layout title="Edit Order {{ $order->invoice_no ?? '' }}">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Order: {{ $order->invoice_no ?? ''}}</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('orders.update', $order->id ) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card custom-card">
                            <div class="card-header"><div class="card-title">Order Details</div></div>
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Invoice No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="invoice_no" value="{{ old('invoice_no', $order->invoice_no) }}" disabled>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Store <span class="text-danger">*</span></label>
                                        <select class="form-select" name="store_id" required>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" {{ old('store_id', $order->store_id) == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Courier</label>
                                        <select class="form-select" name="courier_id">
                                            <option value="">-- No Courier --</option>
                                            @foreach($couriers as $courier)
                                                <option value="{{ $courier->id }}" {{ old('courier_id', $order->courier_id) == $courier->id ? 'selected' : '' }}>{{ $courier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Existing Customer</label>
                                        <select class="form-select" name="customer_id" id="customer_select">
                                            <option value="">Search/Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Customer Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Source</label>
                                        <input type="text" class="form-control" name="source" value="{{ old('source', $order->source) }}" placeholder="e.g., Facebook, Website, Walk-in">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="notes" rows="1">{{ old('notes', $order->notes) }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="customer_address" rows="2">{{ old('customer_address', $order->customer_address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">Products Details</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap" id="order-items-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 35%;">Product</th>
                                                <th style="width: 20%;">Variant</th>
                                                <th style="width: 10%;">SKU</th>
                                                <th style="width: 8%;">Qty</th>
                                                <th style="width: 12%;">Sale Price</th>
                                                <th style="width: 10%;">Subtotal</th>
                                                <th style="width: 5%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-items-body">
                                            @foreach($order->items as $item)
                                                @php
                                                    $existingVariantLabel = '';
                                                    if (!empty($item->attributes) && is_array($item->attributes)) {
                                                        $existingVariantLabel = $item->attributes['variant_label'] ?? '';
                                                    }
                                                @endphp
                                                <tr data-index="existing-{{ $item->id }}">
                                                    <input type="hidden" name="items[existing-{{ $item->id }}][id]" value="{{ $item->id }}">
                                                    <input type="hidden" name="items[existing-{{ $item->id }}][purchase_price]" value="{{ old('items.existing-'.$item->id.'.purchase_price', $item->purchase_price) }}" class="item-purchase-price">
                                                    <input type="hidden" name="items[existing-{{ $item->id }}][attributes]" value='{{ json_encode(old('items.existing-'.$item->id.'.attributes', $item->attributes)) }}' class="item-attributes">
                                                    <td>
                                                        <select class="form-select product-select" name="items[existing-{{ $item->id }}][product_id]" required data-index="existing-{{ $item->id }}">
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $product)
                                                                <option
                                                                    value="{{ $product->id }}"
                                                                    data-price="{{ $product->sale_price }}"
                                                                    data-sku="{{ $product->sku }}"
                                                                    data-has-variant="{{ $product->has_variant ? '1' : '0' }}"
                                                                    {{ old('items.existing-'.$item->id.'.product_id', $item->product_id) == $product->id ? 'selected' : '' }}
                                                                >
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="item-variant-cell">
                                                        <span class="text-muted item-variant-label">{{ $existingVariantLabel }}</span>
                                                        <button type="button" class="btn btn-sm btn-light py-0 px-1 ms-1 edit-variant-btn {{ ($item->product && $item->product->has_variant) ? '' : 'd-none' }}" title="Change Variant">
                                                            <i class="ri-edit-line"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm item-sku" name="items[existing-{{ $item->id }}][sku]" value="{{ old('items.existing-'.$item->id.'.sku', $item->sku) }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm item-qty" name="items[existing-{{ $item->id }}][quantity]" value="{{ old('items.existing-'.$item->id.'.quantity', $item->quantity) }}" min="1" required data-index="existing-{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm item-price" name="items[existing-{{ $item->id }}][sale_price]" value="{{ old('items.existing-'.$item->id.'.sale_price', $item->sale_price) }}" step="0.01" min="0" required data-index="existing-{{ $item->id }}">
                                                    </td>
                                                    <td class="item-subtotal-display">{{ number_format($item->quantity * $item->sale_price, 2) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger-light remove-item-btn"><i class="ri-delete-bin-line"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- If validation fails, old items need to be handled by the controller/view logic to maintain integrity.
                                            The above loop assumes successful loads or relies on the JS to re-render if needed. --}}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-end">
                                                    <button type="button" class="btn btn-sm btn-info" id="add-item-btn">Add Product</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-none">
                                    {{-- Hidden inputs for purchase price and attributes. --}}
                                    <input type="hidden" name="items[new_item_placeholder][purchase_price]" value="0">
                                    <input type="hidden" name="items[new_item_placeholder][attributes]" value="[]">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card custom-card">
                            <div class="card-header"><div class="card-title">Summary & Status</div></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sub Total</label>
                                        <input type="number" class="form-control" name="sub_total" id="sub_total" value="{{ old('sub_total', $order->sub_total) }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Shipping Cost</label>
                                        <input type="number" class="form-control" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', $order->shipping_cost) }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Discount</label>
                                        <input type="number" class="form-control" name="discount" id="discount" value="{{ old('discount', $order->discount) }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Amount</label>
                                        <input type="number" class="form-control" name="total" id="total" value="{{ old('total', $order->total) }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="paid" id="paid" value="{{ old('paid', $order->paid) }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Due Amount</label>
                                        <input type="number" class="form-control" name="due" id="due" value="{{ old('due', $order->due) }}" readonly>
                                    </div>

                                    <hr> <!-- Separator -->

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select" name="payment_method" required>
                                            @foreach($paymentMethods as $key => $method)
                                                <option value="{{ $key }}" {{ old('payment_method', $order->payment_method) == $key ? 'selected' : '' }}>{{ $method }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="payment_status" required>
                                            @foreach($paymentStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ old('payment_status', $order->payment_status) == $key ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Order Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            @foreach($orderStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ old('status', $order->status) == $key ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Assigned To</label>
                                        <select class="form-select" name="assigned_to">
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $user)
                                                <option value="{{ $user->id }}" {{ old('assigned_to', $order->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Remarks</label>
                                        <textarea class="form-control" name="remarks" rows="2" placeholder="Internal remarks...">{{ old('remarks', $order->remarks) }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Reason for Edit / Status Update</label>
                                        <textarea class="form-control" name="edit_reason" rows="2" placeholder="Describe why this order is being updated (optional)..."></textarea>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Update Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Order History Timeline --}}
    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        <i class="bx bx-history me-1 text-primary fs-18"></i> Order Status & Edit History
                    </div>
                </div>
                <div class="card-body">
                    @if($order->histories->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bx bx-history fs-2 d-block mb-1"></i> No history records found for this order.
                        </div>
                    @else
                        <div class="timeline-container px-3">
                            <ul class="list-unstyled mb-0">
                                @foreach($order->histories as $history)
                                    @php
                                        $statusMap = [
                                            0 => ['label' => 'Pending',           'color' => 'secondary'],
                                            1 => ['label' => 'Confirmed',         'color' => 'info'],
                                            2 => ['label' => 'Hold',              'color' => 'warning text-dark'],
                                            3 => ['label' => 'Cancelled',         'color' => 'danger'],
                                            4 => ['label' => 'Stock Out',         'color' => 'danger'],
                                            5 => ['label' => 'Packaged',          'color' => 'secondary'],
                                            6 => ['label' => 'Courier Entry',     'color' => 'primary'],
                                            7 => ['label' => 'On Delivery',       'color' => 'info'],
                                            8 => ['label' => 'Delivered',         'color' => 'success'],
                                            9 => ['label' => 'Partial Delivered', 'color' => 'secondary'],
                                            10 => ['label' => 'Exchange',         'color' => 'warning text-dark'],
                                            11 => ['label' => 'Return',           'color' => 'danger'],
                                            12 => ['label' => 'Return Received',  'color' => 'success'],
                                        ];
                                    @endphp
                                    <li class="position-relative pb-4 ps-4 border-start border-2" style="border-color: #dee2e6 !important;">
                                        {{-- Icon indicator --}}
                                        <span class="position-absolute translate-middle-x bg-white border border-2 border-primary rounded-circle" 
                                              style="left: 0; top: 0; width: 14px; height: 14px;"></span>
                                        
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold text-dark">
                                                {{ ucfirst($history->action == 'status_changed' ? 'Status changed' : $history->action) }} 
                                                @if($history->user)
                                                    by <span class="text-primary">{{ $history->user->name }}</span>
                                                @else
                                                    by <span class="text-muted">System/Webhook</span>
                                                @endif
                                            </span>
                                            <span class="text-muted small">
                                                <i class="bx bx-calendar me-1"></i> {{ $history->created_at->format('d M Y, h:i A') }}
                                            </span>
                                        </div>

                                        {{-- Status badges if status changed --}}
                                        @if($history->old_status !== null && $history->old_status != $history->new_status)
                                            <div class="mb-2">
                                                <span class="badge bg-{{ $statusMap[$history->old_status]['color'] ?? 'light' }} px-2 py-1">
                                                    {{ $statusMap[$history->old_status]['label'] ?? $history->old_status }}
                                                </span>
                                                <i class="bx bx-right-arrow-alt mx-1 align-middle text-muted"></i>
                                                <span class="badge bg-{{ $statusMap[$history->new_status]['color'] ?? 'light' }} px-2 py-1">
                                                    {{ $statusMap[$history->new_status]['label'] ?? $history->new_status }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Reason text --}}
                                        @if($history->reason)
                                            <p class="text-muted mb-1 fs-13">
                                                <strong>Note/Reason:</strong> {{ $history->reason }}
                                            </p>
                                        @endif

                                        {{-- Details of changed fields --}}
                                        @if(!empty($history->changes))
                                            <div class="mt-2">
                                                <button class="btn btn-xs btn-outline-light border text-muted py-0 px-2 fs-11 collapsed" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#changes-{{ $history->id }}">
                                                    Show Changes
                                                </button>
                                                <div class="collapse mt-2" id="changes-{{ $history->id }}">
                                                    <div class="card card-body bg-light p-2 mb-0 border-0 fs-12">
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach($history->changes as $field => $val)
                                                                <li>
                                                                    <code class="text-dark">{{ ucwords(str_replace('_', ' ', $field)) }}</code>: 
                                                                    <span class="text-danger">{{ is_array($val['old']) ? json_encode($val['old']) : ($val['old'] ?? 'N/A') }}</span> 
                                                                    <i class="bx bx-right-arrow-alt align-middle"></i> 
                                                                    <span class="text-success">{{ is_array($val['new']) ? json_encode($val['new']) : ($val['new'] ?? 'N/A') }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- Variant Picker Modal --}}
    <div class="modal fade" id="variantPickerModal" tabindex="-1" aria-labelledby="variantPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="variantPickerModalLabel">Select Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="variant-product-name" class="fw-semibold mb-3"></p>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="variant-options-table">
                            <thead>
                                <tr>
                                    <th>Variant</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody id="variant-options-body"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        const productsData    = @json($products->keyBy('id'));
        const productsVariant = @json($productsVariantData ?? []);
        let itemIndex = 1000;
        let pendingVariantRow = null;

        function buildProductOptions(selectedId = '') {
            let html = '<option value="">Select Product</option>';
            @foreach($products as $p)
            html += `<option value="{{ $p->id }}"
                data-price="{{ $p->sale_price }}"
                data-sku="{{ $p->sku }}"
                data-has-variant="{{ $p->has_variant ? '1' : '0' }}"
                ${selectedId == '{{ $p->id }}' ? 'selected' : ''}>{{ $p->name }}</option>`;
            @endforeach
            return html;
        }

        function createItemRow(item = {}) {
            const index        = itemIndex++;
            const productId    = item.product_id || '';
            const variantLabel = item.variantLabel || '';
            const sku          = item.sku || '';
            const quantity     = item.quantity || 1;
            const salePrice    = item.sale_price || 0;
            const subtotal     = (quantity * salePrice).toFixed(2);
            const purchasePrice = item.purchase_price || 0;
            const attributes   = item.attributes ? JSON.stringify(item.attributes) : '{}';

            const product      = productsData[productId];
            const hasVariant   = product && (product.has_variant == '1' || product.has_variant == true);

            return `
                <tr data-index="${index}">
                    <input type="hidden" name="items[${index}][purchase_price]" value="${purchasePrice}" class="item-purchase-price">
                    <input type="hidden" name="items[${index}][attributes]" value='${attributes}' class="item-attributes">
                    <td>
                        <select class="form-select product-select" name="items[${index}][product_id]" required data-index="${index}">
                            ${buildProductOptions(productId)}
                        </select>
                    </td>
                    <td class="item-variant-cell">
                        <span class="text-muted item-variant-label">${variantLabel}</span>
                        <button type="button" class="btn btn-sm btn-light py-0 px-1 ms-1 edit-variant-btn ${hasVariant ? '' : 'd-none'}" title="Change Variant">
                            <i class="ri-edit-line"></i>
                        </button>
                    </td>
                    <td><input type="text" class="form-control form-control-sm item-sku" name="items[${index}][sku]" value="${sku}" readonly></td>
                    <td><input type="number" class="form-control form-control-sm item-qty" name="items[${index}][quantity]" value="${quantity}" min="1" required data-index="${index}"></td>
                    <td><input type="number" class="form-control form-control-sm item-price" name="items[${index}][sale_price]" value="${salePrice}" step="0.01" min="0" required data-index="${index}"></td>
                    <td class="item-subtotal-display">${subtotal}</td>
                    <td><button type="button" class="btn btn-sm btn-danger-light remove-item-btn"><i class="ri-delete-bin-line"></i></button></td>
                </tr>
            `;
        }

        function updateItemSubtotal(row) {
            const qty   = parseFloat(row.find('.item-qty').val()) || 0;
            const price = parseFloat(row.find('.item-price').val()) || 0;
            row.find('.item-subtotal-display').text((qty * price).toFixed(2));
            calculateOrderSummary(false);
        }

        function calculateOrderSummary(updatePaymentStatus = true) {
            let subTotal = 0;
            $('#order-items-body tr').each(function () {
                subTotal += (parseFloat($(this).find('.item-qty').val()) || 0)
                          * (parseFloat($(this).find('.item-price').val()) || 0);
            });
            const shipping = parseFloat($('#shipping_cost').val()) || 0;
            const discount = parseFloat($('#discount').val()) || 0;
            const paid     = parseFloat($('#paid').val()) || 0;
            const total    = Math.max(0, subTotal + shipping - discount);
            const due      = total - paid;
            $('#sub_total').val(subTotal.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due').val(due.toFixed(2));
            // Only auto-update payment_status when user actively changes financial fields
            if (updatePaymentStatus) {
                if (total <= 0 || due <= 0)    $('select[name="payment_status"]').val('2');  // Paid
                else if (due > 0 && paid > 0)  $('select[name="payment_status"]').val('1');  // Partial
                else                           $('select[name="payment_status"]').val('0');  // Pending
            }
        }

        function openVariantModal(row, productId, productName) {
            pendingVariantRow = row;
            const vdata = productsVariant[productId];
            $('#variant-product-name').text(productName);
            const tbody = $('#variant-options-body').empty();
            if (!vdata || !vdata.variants || !vdata.variants.length) {
                tbody.html('<tr><td colspan="5" class="text-center text-muted">No variants available.</td></tr>');
            } else {
                vdata.variants.forEach(function (v) {
                    tbody.append(`
                        <tr>
                            <td>${v.label}</td>
                            <td>${v.sku}</td>
                            <td>TK ${parseFloat(v.price).toFixed(2)}</td>
                            <td>${v.stock > 0 ? v.stock : '<span class="text-danger">Out</span>'}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary select-variant-btn"
                                    data-sku="${v.sku}"
                                    data-price="${v.price}"
                                    data-label="${v.label}"
                                    data-attrs='${JSON.stringify({variant_label: v.label, variant_sku: v.sku})}'>
                                    Select
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }
            new bootstrap.Modal(document.getElementById('variantPickerModal')).show();
        }

        $(document).ready(function () {
            $('#add-item-btn').on('click', function () {
                $('#order-items-body').append(createItemRow());
            });

            $('#order-items-body').on('click', '.remove-item-btn', function () {
                $(this).closest('tr').remove();
                calculateOrderSummary(false);
            });

            $('#order-items-body').on('change', '.product-select', function () {
                const select     = $(this);
                const row        = select.closest('tr');
                const opt        = select.find('option:selected');
                const productId  = opt.val();
                const price      = parseFloat(opt.data('price')) || 0;
                const sku        = opt.data('sku') || '';
                const hasVariant = opt.data('has-variant') == '1';

                row.find('.item-price').val(price.toFixed(2));
                row.find('.item-sku').val(sku);
                row.find('.item-qty').val(1);
                row.find('.item-variant-label').text('');
                row.find('.item-attributes').val('{}');

                if (hasVariant) {
                    row.find('.edit-variant-btn').removeClass('d-none');
                } else {
                    row.find('.edit-variant-btn').addClass('d-none');
                }

                updateItemSubtotal(row);

                if (hasVariant && productId) {
                    openVariantModal(row, productId, opt.text().trim());
                }
            });

            $('#order-items-body').on('click', '.edit-variant-btn', function () {
                const btn = $(this);
                const row = btn.closest('tr');
                const select = row.find('.product-select');
                const opt = select.find('option:selected');
                const productId = opt.val();
                if (productId) {
                    openVariantModal(row, productId, opt.text().trim());
                }
            });

            $(document).on('click', '#variant-options-body .select-variant-btn', function () {
                const btn   = $(this);
                if (pendingVariantRow) {
                    pendingVariantRow.find('.item-sku').val(btn.data('sku'));
                    pendingVariantRow.find('.item-price').val(parseFloat(btn.data('price')).toFixed(2));
                    pendingVariantRow.find('.item-variant-label').text(btn.data('label'));
                    pendingVariantRow.find('.item-attributes').val(btn.attr('data-attrs'));
                    updateItemSubtotal(pendingVariantRow);
                    pendingVariantRow = null;
                }
                bootstrap.Modal.getInstance(document.getElementById('variantPickerModal')).hide();
            });

            $('#order-items-body').on('input', '.item-qty, .item-price', function () {
                updateItemSubtotal($(this).closest('tr'));
            });

            $('#shipping_cost, #discount, #paid').on('input', function () {
                calculateOrderSummary(true);
            });

            $('#customer_select').on('change', function () {
                const opt = $(this).find('option:selected');
                const name = opt.data('name');
                $('input[name="customer_name"]').val(name || '');
                $('input[name="customer_phone"]').val(opt.data('phone') || '');
            });

            calculateOrderSummary(false);
        });
    </script>
    @endpush
</x-backend-layout>
