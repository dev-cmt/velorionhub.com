<x-backend-layout title="Create Sale Requisition">
    <style>
        #file-upload-input {
            display: none; 
        }
        #image-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px 0px 0px 10px;
        }

        .image-preview-btn {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 4px; 
            background-color: #f8f9fa; 
            display: block;
        }

        .input-group .btn-light {
            border-radius: 0;
            border-right: 0;
        }

        
    </style>
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Create Sale Requisition</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-requisitions.index') }}">Sale Requisition</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('sale-requisitions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-xl-8 mx-auto">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <section class="bg-primary py-4">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-xxl-9 col-xl-10 col-lg-12">
                                        
                                        <div class="input-group input-group-lg shadow-sm rounded">
                                            
                                            <label id="image-upload-label" for="file-upload-input" 
                                                class="btn btn-light" type="button" 
                                                data-bs-toggle="tooltip" title="Upload Image to Search">
                                                
                                                <input type="file" id="file-upload-input" accept="image/*">
                                                
                                                <img id="upload-image-preview" 
                                                    class="image-preview-btn"
                                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-image' viewBox='0 0 16 16'%3E%3Cpath d='M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0'%3E%3C/path%3E%3Cpath d='M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-2.66a.5.5 0 0 0-.61-.077L1.5 12V3a1 1 0 0 1 1-1z'%3E%3C/path%3E%3C/svg%3E" 
                                                    alt="Image Upload Icon">
                                                
                                            </label>

                                            <button class="btn btn-light" type="button" data-bs-toggle="tooltip" title="Voice Search">
                                                <i class="bi bi-mic-fill"></i>
                                            </button>
                                            
                                            <input type="text" id="phoneInput" class="form-control" placeholder="Enter product name..."  aria-label="Search term" value="">
                                            
                                            <button class="btn btn-dark" type="button">
                                                <i class="bi bi-search me-2"></i> Search
                                            </button>

                                            <button class="btn btn-dark dropdown-toggle dropdown-toggle-split" 
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false" 
                                                    data-bs-auto-close="outside" title="Search Options">
                                                <span class="visually-hidden">Search Options</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#">Filter by Product</a></li>
                                                <li><a class="dropdown-item" href="#">Filter by Order Number</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#">Advanced Search</a></li>
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const fileInput = document.getElementById('file-upload-input');
                                const imagePreview = document.getElementById('upload-image-preview');

                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl)
                                })

                                fileInput.addEventListener('change', (event) => {
                                    const file = event.target.files[0];
                                    
                                    if (file) {
                                        if (file.type.startsWith('image/')) {
                                            const reader = new FileReader();
                                            
                                            reader.onload = function(e) {
                                                imagePreview.src = e.target.result;
                                                
                                                const tooltip = bootstrap.Tooltip.getInstance(document.getElementById('image-upload-label'));
                                                if (tooltip) {
                                                    tooltip.dispose();
                                                }
                                                new bootstrap.Tooltip(document.getElementById('image-upload-label'), {
                                                    title: 'Image Ready for Search',
                                                    placement: 'bottom'
                                                });
                                            };
                                            
                                            reader.readAsDataURL(file);

                                            console.log(`Image selected for search: ${file.name}`);
                                        } else {
                                            alert("Please select a valid image file.");
                                            fileInput.value = '';
                                        }
                                    }
                                });
                            });
                        </script>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap" id="order-items-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%;">Product</th>
                                        <th style="width: 15%;">SKU</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 15%;">Sale Price</th>
                                        <th style="width: 15%;">Subtotal</th>
                                        <th style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="order-items-body">
                                    {{-- Dynamic rows will be added here --}}
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Search and add products above.</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end">
                                            <button type="button" class="btn btn-lg btn-info shadow-sm" id="add-item-btn">
                                                <i class="bi bi-plus-circle me-1"></i> Add Product Manually
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-none">
                            {{-- Hidden inputs for purchase price and attributes. --}}
                            <input type="hidden" name="items[0][purchase_price]" value="0">
                            <input type="hidden" name="items[0][attributes]" value="[]">
                        </div>

                    </div>

                    <div class="col-xl-4">
                        <div class="card custom-card">
                            <div class="card-header"><div class="card-title">Summary & Status</div></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Invoice No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="invoice_no" value="{{ old('invoice_no', 'REQ-' . time()) }}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Store <span class="text-danger">*</span></label>
                                        <select class="form-select" name="store_id" required>
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Source</label>
                                        <select class="form-select" name="source">
                                            @foreach(['Facebook', 'Website', 'Walk-in', 'Referral', 'Other'] as $source)
                                                <option value="{{ $source }}" {{ old('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Existing Customer</label>
                                        <select class="form-select" name="customer_id" id="customer_select">
                                            <option value="">Search/Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Customer Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="customer_address" rows="2">{{ old('customer_address') }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Sub Total</label>
                                        <input type="number" class="form-control" name="sub_total" id="sub_total" value="{{ old('sub_total', 0) }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Shipping Cost</label>
                                        <input type="number" class="form-control" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Discount</label>
                                        <input type="number" class="form-control" name="discount" id="discount" value="{{ old('discount', 0) }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Total Amount</label>
                                        <input type="number" class="form-control" name="total" id="total" value="{{ old('total', 0) }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="paid" id="paid" value="{{ old('paid', 0) }}" step="0.01" min="0" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Due Amount</label>
                                        <input type="number" class="form-control" name="due" id="due" value="{{ old('due', 0) }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select" name="payment_method" required>
                                            @foreach($paymentMethods as $key => $method)
                                                <option value="{{ $key }}" {{ (int) old('payment_method', 0) === $key ? 'selected' : '' }}>
                                                    {{ $method }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="payment_status" required>
                                            @foreach($paymentStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ (int) old('payment_status', 0) === $key ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Order Status <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status" required>
                                            @foreach($orderStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ (int) old('status', 0) === $key ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label">Assigned To</label>
                                        <select class="form-select" name="assigned_to">
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $user)
                                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="notes" rows="2">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
    <script>
        const productsData = @json($products->keyBy('id'));
        let itemIndex = 0;

        // --- Core Functions ---

        // Function to create an item row HTML
        function createItemRow(item = {}) {
            const index = item.id ? 'existing-' + item.id : itemIndex++;
            const productId = item.product_id || '';
            const productName = item.product ? item.product.name : 'Select Product';
            const sku = item.sku || '';
            const quantity = item.quantity || 1;
            const salePrice = item.sale_price || 0;
            const subtotal = (quantity * salePrice).toFixed(2);
            
            // Hidden fields for what's not in the table view
            const purchasePrice = item.purchase_price || 0; 
            const attributes = item.attributes ? JSON.stringify(item.attributes) : '[]';

            let itemInputId = item.id ? `items[${index}][id]` : '';

            return `
                <tr data-index="${index}">
                    ${item.id ? `<input type="hidden" name="items[${index}][id]" value="${item.id}">` : ''}
                    <input type="hidden" name="items[${index}][purchase_price]" value="${purchasePrice}" class="item-purchase-price">
                    <input type="hidden" name="items[${index}][attributes]" value='${attributes}'>
                    
                    <td>
                        <select class="form-select product-select" name="items[${index}][product_id]" required data-index="${index}">
                            <option value="">${productName}</option>
                            @foreach($products as $product)
                                <option 
                                    value="{{ $product->id }}" 
                                    data-price="{{ $product->sale_price }}" 
                                    data-sku="{{ $product->sku }}" >
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-sku" name="items[${index}][sku]" value="${sku}" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-qty" name="items[${index}][quantity]" value="${quantity}" min="1" required data-index="${index}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-price" name="items[${index}][sale_price]" value="${salePrice}" step="0.01" min="0" required data-index="${index}">
                    </td>
                    <td class="item-subtotal-display">${subtotal}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger-light remove-item-btn"><i class="ri-delete-bin-line"></i></button>
                    </td>
                    
                </tr>
            `;
        }

        // Function to update the subtotal for an item row
        function updateItemSubtotal(row) {
            const qty = parseFloat(row.find('.item-qty').val()) || 0;
            const price = parseFloat(row.find('.item-price').val()) || 0;
            const subtotal = (qty * price).toFixed(2);
            row.find('.item-subtotal-display').text(subtotal);
            calculateOrderSummary();
        }

        // Function to calculate and update the order summary
        function calculateOrderSummary() {
            let subTotal = 0;
            $('#order-items-body tr').each(function() {
                const row = $(this);
                const qty = parseFloat(row.find('.item-qty').val()) || 0;
                const price = parseFloat(row.find('.item-price').val()) || 0;
                subTotal += (qty * price);
            });

            const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
            const discount = parseFloat($('#discount').val()) || 0;
            const paid = parseFloat($('#paid').val()) || 0;

            const total = (subTotal + shippingCost - discount);
            const due = (total - paid);

            $('#sub_total').val(subTotal.toFixed(2));
            $('#total').val(Math.max(0, total).toFixed(2)); // Ensure total is not negative
            $('#due').val(due.toFixed(2));
            
            // Update payment status based on due amount
            if (total <= 0 || due <= 0) {
                $('select[name="payment_status"]').val('2'); // Paid
            } else if (paid > 0 && due > 0) {
                $('select[name="payment_status"]').val('1'); // Partial
            } else {
                $('select[name="payment_status"]').val('0'); // Pending
            }


        }

        // --- Event Listeners ---

        $(document).ready(function() {
            // Initial load: add one empty item row for new order
            if ($('#order-items-body tr').length === 0) {
                 $('#order-items-body').append(createItemRow());
            }

            // Add Item Button
            $('#add-item-btn').on('click', function() {
                $('#order-items-body').append(createItemRow());
            });

            // Remove Item Button
            $('#order-items-body').on('click', '.remove-item-btn', function() {
                $(this).closest('tr').remove();
                calculateOrderSummary();
            });

            // Product Selection Change
            $('#order-items-body').on('change', '.product-select', function() {
                const select = $(this);
                const row = select.closest('tr');
                const selectedOption = select.find('option:selected');
                const price = parseFloat(selectedOption.data('price')) || 0;
                const sku = selectedOption.data('sku') || '';

                row.find('.item-price').val(price.toFixed(2));
                row.find('.item-sku').val(sku);
                row.find('.item-qty').val(1); // Reset quantity on product change
                
                updateItemSubtotal(row);
            });

            // Quantity or Price Change
            $('#order-items-body').on('input', '.item-qty, .item-price', function() {
                updateItemSubtotal($(this).closest('tr'));
            });

            // Summary Field Change
            $('#shipping_cost, #discount, #paid').on('input', function() {
                calculateOrderSummary();
            });
            
            // Customer Select Change (Pre-fill name and phone)
            $('#customer_select').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const name = selectedOption.data('name');
                const phone = selectedOption.data('phone');
                
                if (name) {
                    $('input[name="customer_name"]').val(name);
                    $('input[name="customer_phone"]').val(phone);
                } else {
                    // Clear fields if 'Select Customer' is chosen
                    $('input[name="customer_name"]').val('');
                    $('input[name="customer_phone"]').val('');
                }
            });

            // Initial calculation on page load (in case of old input errors)
            calculateOrderSummary();
        });
    </script>
    @endpush
</x-backend-layout>