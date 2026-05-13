<x-backend-layout title="Tags Management">
    @push('css')
        <link rel="stylesheet" href="{{asset('backend/libs/summernote/summernote-lite.min.css')}}"/>
    @endpush
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Product</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column: Basic Info + SEO + Discounts -->
            <div class="col-md-8">

                <!-- Basic Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Basic Information</div>
                    </div>
                    <div class="card-body">
                        <!-- Product Name -->
                        <div class="mb-2">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control summernote" rows="4">
                                {!! old('description', $product->description ?? '') !!}
                            </textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>


                <!-- Others Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Others Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-2">
                                <nav class="nav nav-tabs flex-column nav-style-4" role="tablist">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#inventory-link" aria-selected="false">
                                        <i class="ri-home-smile-line me-2 align-middle d-inline-block"></i> Inventory
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#discounts-link" aria-selected="true">
                                        <i class="ri-coupon-line me-2 align-middle d-inline-block"></i> Discounts
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#shipping-link" aria-selected="false">
                                        <i class="ri-ship-line me-2 align-middle d-inline-block"></i> Shipping
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#production-link" aria-selected="false">
                                        <i class="ri-building-3-line me-2 align-middle d-inline-block"></i> Production
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#specifications-link" aria-selected="false">
                                        <i class="ri-list-check-2 me-2 align-middle d-inline-block"></i> Specifications
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#seo-link" aria-selected="false">
                                        <i class="ri-search-eye-line me-2 align-middle d-inline-block"></i> SEO Info.
                                    </a>
                                </nav>
                            </div>
                            <div class="col-xl-10">
                                <div class="tab-content">
                                    <div class="tab-pane show active text-muted" id="inventory-link" role="tabpanel">
                                        <!--Inventory-->
                                        <div class="row">
                                            <div class="col-md-4 mb-1">
                                                <label class="form-label">SKU Prefix <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" id="sku" name="sku"
                                                    value="{{ old('sku', $product->sku ?? 'SKU') }}" required>
                                                @error('sku') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="stock_status" class="form-label">Stock Management <span class="text-danger">*</span></label>
                                                <select name="stock_status" id="stock_status" class="form-select">
                                                    <option value="quantity" {{ old('stock_status', $product->stock_status ?? '') == 'quantity' ? 'selected' : '' }}>Quantity</option>
                                                    <option value="in_stock" {{ old('stock_status', $product->stock_status ?? '') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                    <option value="out_of_stock" {{ old('stock_status', $product->stock_status ?? '') == 'out_of_stock' ? 'selected' : '' }}>Out Of Stock</option>
                                                    <option value="upcoming" {{ old('stock_status', $product->stock_status ?? '') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                </select>
                                                @error('stock_status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="units" class="form-label">Units</label>
                                                <select class="form-select" name="unit_id">
                                                    <option value="">Select Unit</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                                            {{ $unit->name }} ({{ $unit->short_name }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('unit_id')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label class="form-label">Sale Price <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" id="sale_price" name="sale_price"
                                                    value="{{ old('sale_price', $product->sale_price ?? '0.00') }}" min="0" step="0.01">
                                                @error('sale_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="purchase_price" class="form-label">Regular Price</label>
                                                <input type="number" class="form-control form-control-sm" id="regular_price" name="regular_price"
                                                    value="{{ old('regular_price', $product->regular_price ?? '0.00') }}">
                                                @error('regular_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="purchase_price" class="form-label">Purchase Price</label>
                                                <input type="number" class="form-control form-control-sm" id="purchase_price" name="purchase_price"
                                                    value="{{ old('purchase_price', $product->purchase_price ?? '0.00') }}">
                                                @error('purchase_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="total_stock" class="form-label">Total Stock <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" id="total_stock" name="total_stock"
                                                    value="{{ old('total_stock', $product->total_stock ?? 0) }}">
                                                @error('total_stock') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="stock_out" class="form-label">Stock Out</label>
                                                <input type="number" class="form-control form-control-sm" id="stock_out" name="stock_out"
                                                    value="{{ old('stock_out', $product->stock_out ?? 1) }}">
                                                @error('stock_out') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-4 mb-1">
                                                <label for="alert_quantity" class="form-label">Alert Quantity</label>
                                                <input type="number" class="form-control form-control-sm" id="alert_quantity" name="alert_quantity"
                                                    value="{{ old('alert_quantity', $product->alert_quantity ?? 0) }}">
                                                @error('alert_quantity') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane text-muted" id="discounts-link" role="tabpanel">
                                        <!-- Discount -->
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label for="discount_type" class="form-label">Discount Type</label>
                                                <select name="discount_type" id="discount_type" class="form-select">
                                                    <option value="">Select Type</option>
                                                    @foreach(['percentage', 'flat'] as $type)
                                                        <option value="{{ $type }}" {{ old('discount_type', optional($product->discount)->discount_type) === $type ? 'selected' : '' }}>
                                                            {{ ucfirst($type) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control form-control-sm" id="amount" name="amount"
                                                    value="{{ old('amount', optional($product->discount)->amount) }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date"
                                                    value="{{ old('start_date', optional($product->discount)->start_date?->format('Y-m-d')) }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date"
                                                    value="{{ old('end_date', optional($product->discount)->end_date?->format('Y-m-d')) }}">
                                            </div>

                                            <!-- Discount Status -->
                                            <div class="border-top pt-3">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="discount_status" value="0">
                                                    <input class="form-check-input"
                                                        type="checkbox" name="discount_status" id="discountStatusToggle" value="1"
                                                        {{ old('discount_status', optional($product->discount)->status ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="discountStatusToggle">Enable Discount</label>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane text-muted" id="shipping-link" role="tabpanel">
                                        <!-- Weight -->
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <label for="weight" class="form-label">Weight (kg)</label>
                                                <div class="form-check form-switch mt-2">
                                                    <input type="hidden" name="free_shipping" value="0">
                                                    <input class="form-check-input" type="checkbox" id="freeShippingToggle" name="free_shipping" value="1"
                                                        {{ old('free_shipping', optional($product->shipping)->free_shipping ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-success" for="freeShippingToggle">Free Shipping</label>
                                                </div>


                                            </div>
                                            <input type="number" class="form-control" id="weight" name="weight" placeholder="0.00"
                                                value="{{ old('weight', optional($product->shipping)->weight) }}" step="0.01" min="0">
                                            @error('weight') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <!-- Dimensions -->
                                        <div class="border p-2 mb-2 bg-light">
                                            <label class="form-label mb-2 d-block">Dimensions (cm)</label>
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="length" name="length" placeholder="Length"
                                                        value="{{ old('length', optional($product->shipping)->length) }}" step="0.01" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="width" name="width" placeholder="Width"
                                                        value="{{ old('width', optional($product->shipping)->width) }}" step="0.01" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="height" name="height" placeholder="Height"
                                                        value="{{ old('height', optional($product->shipping)->height) }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                            @if($errors->has('length') || $errors->has('width') || $errors->has('height'))
                                                <div class="text-danger mt-1">Please check all dimension fields.</div>
                                            @endif
                                        </div>

                                        <!-- Shipping Class & Rates -->
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Shipping Class</label>
                                                <select id="shipping_class_id" name="shipping_class_id" class="form-select">
                                                    @foreach($shippingClasses ?? [] as $class)
                                                        <option value="{{ $class->id }}"
                                                            {{ old('shipping_class_id', optional($product->shipping)->shipping_class_id) == $class->id ? 'selected' : '' }}
                                                            data-inside="{{ $class->inside_rate }}"
                                                            data-outside="{{ $class->outside_rate }}">
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Inside City Rate</label>
                                                <div class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="text" name="inside_city_rate" id="inside_rate_display" class="form-control form-control-sm" readonly
                                                        value="{{ old('inside_city_rate', optional($product->shipping)->inside_city_rate ?? '0') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Outside City Rate</label>
                                                <div class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="text" name="outside_city_rate" id="outside_rate_display" class="form-control form-control-sm" readonly
                                                        value="{{ old('outside_city_rate', optional($product->shipping)->outside_city_rate ?? '0') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            const shippingSelect = document.getElementById('shipping_class_id');
                                            const insideRate = document.getElementById('inside_rate_display');
                                            const outsideRate = document.getElementById('outside_rate_display');

                                            function updateRates() {
                                                const selected = shippingSelect.selectedOptions[0];
                                                // Only update if old() is not set
                                                if (!insideRate.dataset.initial) insideRate.value = selected ? selected.dataset.inside : 0;
                                                if (!outsideRate.dataset.initial) outsideRate.value = selected ? selected.dataset.outside : 0;
                                            }

                                            shippingSelect.addEventListener('change', updateRates);
                                            updateRates(); // initialize on page load
                                        </script>

                                    </div>
                                    <div class="tab-pane text-muted" id="production-link" role="tabpanel">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Manufacturer</label>
                                                <input type="text" class="form-control" name="manufacturer"
                                                    value="{{ old('manufacturer', $product->manufacturer ?? '') }}">
                                                @error('manufacturer')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Manufactured Date</label>
                                                <input type="date" class="form-control" name="manufacturer_date"
                                                    value="{{ old('manufacturer_date', optional($product->manufacturer_date)->format('Y-m-d')) }}">
                                                @error('manufacturer_date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Warranty</label>
                                                <select class="form-select" name="warranty_id">
                                                    <option value="">Select Warranty</option>
                                                    @if(isset($warranties))
                                                        @foreach($warranties as $warranty)
                                                            <option value="{{ $warranty->id }}"
                                                                {{ (old('warranty_id', $product->warranty_id ?? '') == $warranty->id) ? 'selected' : '' }}>
                                                                {{ $warranty->name }} ({{ $warranty->full_duration }})
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('warranty_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Expiry Date</label>
                                                <input type="date" class="form-control" name="expire_date"
                                                    value="{{ old('expire_date', optional($product->expire_date)->format('Y-m-d')) }}">
                                                @error('expire_date')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane text-muted" id="specifications-link" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm" id="specifications_table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Specification Name (e.g., Brand)</th>
                                                        <th>Specification Value (e.g., Apple)</th>
                                                        <th class="text-center" style="width: 50px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(is_array($product->specification) && count($product->specification) > 0)
                                                        @foreach($product->specification as $key => $value)
                                                            <tr>
                                                                <td><input type="text" name="spec_keys[]" class="form-control form-control-sm" placeholder="Name" value="{{ $key }}"></td>
                                                                <td><input type="text" name="spec_values[]" class="form-control form-control-sm" placeholder="Value" value="{{ $value }}"></td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-danger remove-spec-row"><i class="ri-delete-bin-line"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td><input type="text" name="spec_keys[]" class="form-control form-control-sm" placeholder="Name"></td>
                                                            <td><input type="text" name="spec_values[]" class="form-control form-control-sm" placeholder="Value"></td>
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-danger remove-spec-row"><i class="ri-delete-bin-line"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-primary mt-2" id="add_spec_row"><i class="ri-add-line"></i> Add Row</button>
                                        </div>
                                    </div>

                                    <div class="tab-pane text-muted" id="seo-link" role="tabpanel">
                                        <div class="mb-1">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                                value="{{ old('meta_title', optional($product->seo)->meta_title) }}">
                                            @error('meta_title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-1">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', optional($product->seo)->meta_description) }}</textarea>
                                            @error('meta_description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                                    value="{{ old('meta_keywords', optional($product->seo)->meta_keywords) }}"
                                                    placeholder="Separate keywords with commas">
                                                @error('meta_keywords') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-{{optional($product->seo)->og_image ? '8': '12'}}">
                                                        <label for="meta_image" class="form-label">Meta Image</label>
                                                        <input type="file" class="form-control" id="meta_image" name="meta_image">
                                                    </div>

                                                    @if(optional($product->seo)->og_image)
                                                        <div class="col-md-4 d-flex justify-content-between align-items-center">
                                                            <div class="form-check mt-4">
                                                                <input type="checkbox" class="form-check-input" id="delete_meta_image" name="delete_meta_image" value="1">
                                                                <label class="form-check-label text-danger" for="delete_meta_image">Delete Meta Image</label>
                                                            </div>
                                                            <img src="{{ asset(optional($product->seo)->og_image) }}" alt="Meta Image" style="width: 68px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Product Variants Preview</div>
                        <div class="custom-toggle-switch d-flex align-items-center">
                            <input type="hidden" name="has_variant" value="0">
                            <input id="hasVariantToggle" name="has_variant" type="checkbox" value="1"
                                {{ old('has_variant', $product->has_variant ?? false) ? 'checked' : '' }}>
                            <label for="hasVariantToggle" class="label-primary"></label>
                        </div>

                    </div>
                    <div class="card-body" id="variant_card_body" style="{{ old('has_variant', $product->has_variant ?? false) ? '' : 'display:none;' }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Attributes</label>
                                <select name="attribute_id[]" id="attribute_id" class="form-select searchable" multiple>
                                    @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}"
                                            {{ $product->variants->pluck('variantItems.*.attribute_id')->flatten()->unique()->contains($attribute->id) ? 'selected' : '' }}>
                                            {{ $attribute->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="attribute_items_container" class="row"></div>
                        <div id="variant_combinations_container"></div>
                    </div>
                </div>

                @push('js')
                <script>
                    $('#hasVariantToggle').on('change', function() { $('#variant_card_body').toggle(this.checked); });
                </script>

                <script>
                    $(function() {
                        // Initialize Choices.js for searchable selects
                        function initChoices() {
                            $('.attribute-item').each(function() {
                                if (!$(this).data('choices-initialized')) {
                                    new Choices(this, {
                                        removeItemButton: true,
                                        searchEnabled: true,
                                        placeholderValue: 'Select Items'
                                    });
                                    $(this).data('choices-initialized', true);
                                }
                            });
                        }

                        // Load attribute items (e.g. Color, Size options)
                        function loadAttributeItems(loadExisting = true) {
                            let selected = $('#attribute_id').val();
                            if (!selected || !selected.length) {
                                $('#attribute_items_container').html('');
                                $('#variant_combinations_container').html('');
                                return;
                            }

                            $.get("{{ route('attributes.getItems') }}", {
                                attribute_ids: selected,
                                product_id: "{{ $product->id ?? '' }}"
                            }, function(html) {
                                $('#attribute_items_container').html(html);
                                initChoices();

                                if (loadExisting) {
                                    setTimeout(() => {
                                        syncImageUploadFields();
                                        loadVariantCombinations();
                                    }, 300);
                                }
                            });
                        }

                        // Load variant combinations (SKU generation)
                        function loadVariantCombinations() {
                            let attrs = [];
                            $('.attribute-item').each(function() {
                                let id = $(this).data('id');
                                let items = $(this).val();
                                if (items && items.length) attrs.push({ id, items });
                            });
                            if (!attrs.length) return $('#variant_combinations_container').html('');

                            $.get('{{ route("products.getItemsCombo") }}', {
                                sku: $('#sku').val(),
                                sale_price: $('#sale_price').val(),
                                purchase_price: $('#purchase_price').val(),
                                total_stock: $('#total_stock').val(),
                                attributes: attrs,
                                product_id: '{{ $product->id ?? '' }}'
                            }, function(html) {
                                $('#variant_combinations_container').html(html);
                            });
                        }

                        // Sync image upload fields with selected options
                        function syncImageUploadFields() {
                            $('.attribute-item').each(function() {
                                if (!$(this).data('has-image')) return;

                                let attrId = $(this).data('id');
                                let container = $(`.image-upload-container[data-attr-id="${attrId}"] .image-upload-fields`);
                                let selectedIds = $(this).val() || [];

                                // Handle dynamically added fields only
                                container.find('.single-upload-field').each(function() {
                                    let fieldItemId = $(this).data('item-id');
                                    // Only target fields that were dynamically added (no existing image)
                                    if (!$(this).data('existing')) {
                                        if (!selectedIds.includes(String(fieldItemId))) {
                                            $(this).remove(); // remove newly added field if unselected
                                        }
                                    }
                                });

                                // Add new fields for newly selected items that don't exist yet
                                selectedIds.forEach(function(itemId) {
                                    if (container.find(`[data-item-id="${itemId}"]`).length) return; // already exists
                                    let itemName = $(`.attribute-item[data-id="${attrId}"] option[value="${itemId}"]`).text();
                                    container.append(`
                                        <div class="d-flex align-items-center mb-2 single-upload-field" data-item-id="${itemId}">
                                            <span class="me-2 fw-semibold text-secondary attribute-image-label">${itemName}</span>
                                            <input type="file" name="attribute_images[${attrId}][${itemId}]" class="form-control form-control-sm attribute-image-input" accept="image/*">
                                            <img src="" alt="${itemName}" class="attribute-image-preview ms-2 d-none">
                                        </div>
                                    `);
                                });
                            });
                        }

                        // Show instant thumbnail preview for attribute images (e.g., Color)
                        function setAttributeImagePreview(fileInput) {
                            const $field = $(fileInput).closest('.single-upload-field');
                            let $img = $field.find('.attribute-image-preview');

                            if (!$img.length) {
                                $img = $('<img class="attribute-image-preview ms-2 d-none" alt="Preview">');
                                $field.append($img);
                            }

                            const file = fileInput.files && fileInput.files[0];
                            const previousUrl = $img.data('object-url');
                            if (previousUrl) {
                                URL.revokeObjectURL(previousUrl);
                                $img.removeData('object-url');
                            }

                            if (!file) {
                                $img.attr('src', '').addClass('d-none');
                                return;
                            }

                            const url = URL.createObjectURL(file);
                            $img.attr('src', url).removeClass('d-none');
                            $img.data('object-url', url);
                        }

                        // Attribute select change (main attribute selection)
                        $('#attribute_id').on('change', function() {
                            loadAttributeItems(true);
                            $('#variant_combinations_container').html('');
                        });

                        // Individual attribute items select change
                        $(document).on('change', '.attribute-item', function() {
                            syncImageUploadFields();
                            loadVariantCombinations();
                        });

                        // When user picks an image for a color/attribute item
                        $(document).on('change', '.attribute-image-input', function() {
                            setAttributeImagePreview(this);
                        });

                        // Update variant combinations when prices or SKU change
                        $(document).on('keyup change', '#sku, #sale_price, #purchase_price, #total_stock', loadVariantCombinations);

                        // Remove variant row
                        $(document).on('click', '.remove-variant', function() {
                            $(this).closest('tr').remove();
                        });

                        // Add/Remove Specification row
                        $('#add_spec_row').on('click', function() {
                            let row = `<tr>
                                <td><input type="text" name="spec_keys[]" class="form-control form-control-sm" placeholder="Name"></td>
                                <td><input type="text" name="spec_values[]" class="form-control form-control-sm" placeholder="Value"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-spec-row"><i class="ri-delete-bin-line"></i></button>
                                </td>
                            </tr>`;
                            $('#specifications_table tbody').append(row);
                        });
                        $(document).on('click', '.remove-spec-row', function() {
                            $(this).closest('tr').remove();
                        });

                        // Initialize Choices.js for main attributes select
                        new Choices('#attribute_id', { removeItemButton: true, searchEnabled: true });

                        // Initial load on page ready
                        loadAttributeItems(true);

                    });

                </script>
                @endpush






            </div>

            <!-- Right Column: Categories + Brands + Tags + Settings -->
            <div class="col-md-4">
                <!-- Product Images -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Images</div>
                    </div>
                    <div class="card-body pt-1">
                        <div class="d-flex gap-3 align-items-start">
                            <!-- Main & Hover Group -->
                            <div class="d-flex gap-2">
                                <div class="text-center">
                                    <span class="fw-bold text-muted d-block mb-1" style="font-size: 9px; letter-spacing: 0.5px;">MAIN</span>
                                    <div class="image-preview-box shadow-sm" id="main_image_container">
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" onclick="{{ $product->main_image ? '' : "document.getElementById('main_image').click()" }}" id="main_image_clicker">
                                            <i class="ri-image-add-line {{ $product->main_image ? 'd-none' : '' }}" id="main_image_icon"></i>
                                            <img id="main_image_preview" src="{{ $product->main_image ? asset($product->main_image) : '' }}" class="{{ $product->main_image ? '' : 'd-none' }}">
                                        </div>
                                        <div class="delete-overlay" id="main_image_delete" style="{{ $product->main_image ? 'display: flex;' : 'display: none;' }}">
                                            <button type="button" class="btn-delete-small" style="width:24px; height:24px; font-size:12px;" onclick="clearSingleImage('main_image', true)">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" name="main_image" id="main_image" class="d-none" accept="image/*" onchange="previewImage(this, 'main_image_preview', 'main_image_icon')">
                                    <input type="hidden" name="delete_main_image" id="delete_main_image" value="0">
                                    @error('main_image') <div class="text-danger mt-1" style="font-size: 8px;">{{ $message }}</div> @enderror
                                </div>

                                <div class="text-center">
                                    <span class="fw-bold text-muted d-block mb-1" style="font-size: 9px; letter-spacing: 0.5px;">HOVER</span>
                                    <div class="image-preview-box shadow-sm" id="hover_image_container">
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center" onclick="{{ $product->hover_image ? '' : "document.getElementById('hover_image').click()" }}" id="hover_image_clicker">
                                            <i class="ri-image-add-line {{ $product->hover_image ? 'd-none' : '' }}" id="hover_image_icon"></i>
                                            <img id="hover_image_preview" src="{{ $product->hover_image ? asset($product->hover_image) : '' }}" class="{{ $product->hover_image ? '' : 'd-none' }}">
                                        </div>
                                        <div class="delete-overlay" id="hover_image_delete" style="{{ $product->hover_image ? 'display: flex;' : 'display: none;' }}">
                                            <button type="button" class="btn-delete-small" style="width:24px; height:24px; font-size:12px;" onclick="clearSingleImage('hover_image', true)">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" name="hover_image" id="hover_image" class="d-none" accept="image/*" onchange="previewImage(this, 'hover_image_preview', 'hover_image_icon')">
                                    <input type="hidden" name="delete_hover_image" id="delete_hover_image" value="0">
                                    @error('hover_image') <div class="text-danger mt-1" style="font-size: 8px;">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Gallery Group -->
                            <div class="flex-grow-1" style="min-width: 0;">
                                <span class="fw-bold text-muted d-block mb-1" style="font-size: 9px; letter-spacing: 0.5px;">GALLERY IMAGES</span>
                                <div class="gallery-grid mt-0" id="gallery_preview">
                                    <div class="gallery-upload-card shadow-sm" onclick="document.getElementById('gallery_images').click()">
                                        <i class="ri-add-line"></i>
                                        <span style="font-size: 8px;">ADD</span>
                                    </div>
                                    @if($product->media)
                                        @foreach($product->media as $media)
                                            @if($media->type === 'image')
                                                <div class="gallery-item" id="media_item_{{ $media->id }}">
                                                    <img src="{{ asset($media->path) }}">
                                                    <div class="delete-overlay">
                                                        <button type="button" class="btn-delete-small" style="width:24px; height:24px; font-size:12px;" onclick="deleteMedia({{ $media->id }}, '{{ $media->path }}')">
                                                            <i class="ri-close-line"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <input type="file" name="gallery_images[]" id="gallery_images" class="d-none" accept="image/*" multiple onchange="previewGallery(this, 'gallery_preview')">
                                @error('gallery_images') <div class="text-danger mt-1" style="font-size: 8px;">{{ $message }}</div> @enderror
                                <div id="deleted_media_container"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Product Categories -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Categories</div>
                        <a href="#" class="btn btn-primary-light btn-sm"><i class="bi bi-plus-lg"></i> Add New</a>
                    </div>
                    <div class="card-body pt-1">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select select2" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Product Brand -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Brands</div>
                        <a href="#" class="btn btn-primary-light btn-sm"><i class="bi bi-plus-lg"></i> Add New</a>
                    </div>
                    <div class="card-body pt-1">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-select searchable" data-placeholder="Select Brand">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Product Tag -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Tags</div>
                        <a href="#" class="btn btn-primary-light btn-sm"><i class="bi bi-plus-lg"></i> Add New</a>
                    </div>
                    <div class="card-body pt-1">
                        <label for="tag_id" class="form-label">Tag</label>
                        <select name="tag_id" id="tag_id" class="form-select searchable" multiple data-placeholder="Select Tags">
                            <option value="">Select Tag</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    {{ old('tag_id', $product->tag_id ?? '') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tag_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label for="product_type" class="form-label">Product Type</label>
                            <select class="form-select" id="product_type" name="product_type">
                                @php $ptype = old('product_type', $product->product_type ?? 'sale'); @endphp
                                <option value="sale" {{ $ptype == 'sale' ? 'selected' : '' }}>Sale</option>
                                <option value="hot" {{ $ptype == 'hot' ? 'selected' : '' }}>Hot</option>
                                <option value="regular" {{ $ptype == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="trending" {{ $ptype == 'trending' ? 'selected' : '' }}>Trending</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                @php $vis = old('visibility', $product->visibility ?? 'public'); @endphp
                                <option value="public" {{ $vis == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ $vis == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="schedule" {{ $vis == 'schedule' ? 'selected' : '' }}>Schedule</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                @php $status = old('status', $product->status ?? 1); @endphp
                                <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>


                <!-- Actions -->
                <div class="card custom-card mt-3">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">Update Product</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </form>


    @push('js')
    <script src="{{asset('backend/libs/summernote/summernote-lite.min.js')}}"></script>

    <script>
        $('.summernote').summernote({
            height: 100,
        });

        function previewImage(input, previewId, iconId) {
            const preview = document.getElementById(previewId);
            const icon = document.getElementById(iconId);
            const deleteBtn = document.getElementById(input.id + '_delete');
            const clicker = document.getElementById(input.id + '_clicker');
            const deleteFlag = document.getElementById('delete_' + input.id);

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    icon.classList.add('d-none');
                    if (deleteBtn) deleteBtn.style.display = 'flex';
                    if (clicker) clicker.onclick = null;
                    if (deleteFlag) deleteFlag.value = "0"; // Cancel pending deletion
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function clearSingleImage(inputId, isEdit = false) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(inputId + '_preview');
            const icon = document.getElementById(inputId + '_icon');
            const deleteBtn = document.getElementById(inputId + '_delete');
            const clicker = document.getElementById(inputId + '_clicker');
            const deleteFlag = document.getElementById('delete_' + inputId);

            input.value = ''; // Clear file input
            preview.src = '';
            preview.classList.add('d-none');
            icon.classList.remove('d-none');
            if (deleteBtn) deleteBtn.style.display = 'none';
            if (clicker) clicker.onclick = function() { input.click(); };

            if (isEdit && deleteFlag) {
                deleteFlag.value = "1"; // Mark for deletion on server
            }
        }

        function previewGallery(input, previewContainerId) {
            const container = $('#' + previewContainerId);
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        const html = `
                            <div class="gallery-item">
                                <img src="${e.target.result}">
                                <div class="progress-container">
                                    <div class="progress-fill"></div>
                                </div>
                                <div class="success-badge">
                                    <i class="ri-check-line"></i>
                                </div>
                                <div class="delete-overlay">
                                    <button type="button" class="btn-delete-small" onclick="$(this).closest('.gallery-item').remove()">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        const $item = $(html);
                        container.append($item);

                        // Trigger animation
                        setTimeout(() => {
                            $item.addClass('loaded');
                        }, 50);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function deleteMedia(id, path) {
            if (confirm('Are you sure you want to delete this image?')) {
                $('#media_item_' + id).remove();
                $('#deleted_media_container').append(`<input type="hidden" name="deleted_media[]" value="${id}">`);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Choices.js Initialization
            document.querySelectorAll('select.searchable').forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    shouldSort: false,
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: select.dataset.placeholder || 'Select an option'
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>
