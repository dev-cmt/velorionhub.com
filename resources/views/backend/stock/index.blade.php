<x-backend-layout title="Stock Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Stock Inventory Management</h1>
            <p class="text-muted mb-0">Overview of all product inventory, pricing, and stock status.</p>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Stock</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Product Inventory List</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('stock-adjustment.index') }}" class="btn btn-warning btn-sm">
                            <i class="ri-equalizer-line me-1 align-middle"></i>Stock Adjustment
                        </a>
                        <a href="{{ route('stock-transfer.index') }}" class="btn btn-info btn-sm">
                            <i class="ri-transfer-line me-1 align-middle"></i>Stock Transfer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Purchase Price</th>
                                    <th>Sale Price</th>
                                    <th>Alert Qty</th>
                                    <th>Total Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    @if($product->main_image)
                                                        <img src="{{ asset($product->main_image) }}" alt="" width="30" height="30" class="rounded">
                                                    @else
                                                        <div class="bg-light rounded p-1 text-center" style="width: 30px; height: 30px;">
                                                            <i class="ri-image-line text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-muted border">{{ $product->sku }}</span></td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($product->purchase_price, 2) }}</td>
                                        <td>{{ number_format($product->sale_price, 2) }}</td>
                                        <td>{{ $product->alert_quantity }}</td>
                                        <td>
                                            <span class="fw-bold {{ $product->total_stock <= $product->alert_quantity ? 'text-danger' : 'text-success' }}">
                                                {{ $product->total_stock }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($product->total_stock <= 0)
                                                <span class="badge bg-danger-transparent">Out of Stock</span>
                                            @elseif($product->total_stock <= $product->alert_quantity)
                                                <span class="badge bg-warning-transparent">Low Stock</span>
                                            @else
                                                <span class="badge bg-success-transparent">Healthy</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-info-light btn-icon" title="Edit Product">
                                                <i class="ri-pencil-line"></i>
                                            </a>
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
</x-backend-layout>
