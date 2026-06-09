<x-backend-layout title="Products Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Products List</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products List</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="card-title">Product List</div>
            <a href="{{ route('products.create') }}" class="btn btn-primary-light btn-sm">
                <i class="bi bi-plus-lg"></i> Add New
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Variants</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $key }}</td>
                            <td>
                                @if($product->main_image)
                                    <span class="avatar avatar-xxl border" style="width: 50px; height: 35px;">
                                        <img src="{{ asset($product->main_image) }}" alt="photo" class="object-fit-cover">
                                    </span>
                                @else
                                    <span class="avatar avatar-md bg-light border" style="width: 50px; height: 35px;">
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="ri-image-line text-muted fs-16"></i>
                                        </div>
                                    </span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>
                                @if($product->has_variant && count($product->variant_summary))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($product->variant_summary as $variant)
                                            <span class="badge bg-info text-dark" title="{{ $variant['sku'] }}">
                                                {{ $variant['label'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-secondary">No Variant</span>
                                @endif
                            </td>
                            <td>{{ number_format($product->sale_price, 2) }}</td>
                            <td>
                                @if($product->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning-light btn-icon">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $products->links('backend.pagination.paginate') }}
                    {{-- {{ $products->links('backend.pagination.custom') }} --}}
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
