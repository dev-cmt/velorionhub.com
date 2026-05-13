<x-backend-layout title="Brands Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Brands Management</h1>
            <p class="text-muted mb-0 small">Create, edit, and organize product brands for your store.</p>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Brands</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card border-0 shadow-sm">
                <div class="card-header justify-content-between align-items-center border-bottom-0 pb-0">
                    <div class="card-title d-flex align-items-center">
                        <i class="ri-shield-star-line me-2 text-primary fs-20"></i>
                        <span>Brands Inventory</span>
                    </div>
                    <button type="button" class="btn btn-primary-light btn-wave" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                        <i class="ri-add-circle-line me-1 align-middle"></i>Add New Brand
                    </button>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible shadow-sm fade show mb-4">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success-transparent alert-dismissible shadow-sm fade show mb-4 d-flex align-items-center">
                            <i class="ri-checkbox-circle-line fs-18 me-2"></i>
                            <div class="small fw-semibold">{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive border rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-3" style="width: 80px;">SL</th>
                                    <th>Identity</th>
                                    <th>Brand Name</th>
                                    <th>Display Order</th>
                                    <th>Status</th>
                                    <th class="text-center pe-3" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $key => $brand)
                                    <tr>
                                        <td class="ps-3 text-muted fw-medium">{{ $brands->firstItem() + $key }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md bg-light border p-1 rounded-2 overflow-hidden shadow-sm" style="width: 55px; height: 35px;">
                                                    @if($brand->logo)
                                                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-100 h-100 object-fit-contain">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center h-100">
                                                            <i class="ri-image-line text-muted fs-16"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="fw-semibold text-dark">{{ $brand->name }}</span></td>
                                        <td>
                                            <span class="badge bg-outline-light text-muted border px-2 py-1">
                                                <i class="ri-sort-asc me-1"></i>{{ $brand->sort_order }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($brand->status)
                                                <span class="badge bg-success-transparent rounded-pill px-3">
                                                    <i class="ri-checkbox-blank-circle-fill fs-8 me-1 align-middle"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger-transparent rounded-pill px-3">
                                                    <i class="ri-checkbox-blank-circle-fill fs-8 me-1 align-middle"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="btn-list justify-content-center">
                                                <button type="button" class="btn btn-sm btn-icon btn-info-light edit-brand"
                                                    data-id="{{ $brand->id }}"
                                                    data-name="{{ $brand->name }}"
                                                    data-sort_order="{{ $brand->sort_order }}"
                                                    data-status="{{ $brand->status }}"
                                                    data-logo="{{ $brand->logo }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBrandModal">
                                                    <i class="ri-edit-2-line"></i>
                                                </button>
                                                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-danger-light" onclick="return confirm('Permanent delete this brand?')">
                                                        <i class="ri-delete-bin-5-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ri-archive-line fs-40 op-2 d-block mb-2"></i>
                                                No brands available in the database.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        {{ $brands->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title text-white">Create New Brand</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Brand Name</label>
                            <input type="text" class="form-control form-control-lg fs-14" name="name" placeholder="Enter brand name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Brand Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*" required>
                            <div class="form-text small">Recommended: Square or 4:3 aspect ratio (PNG/JPG).</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Sort Order</label>
                                <input type="number" class="form-control" name="sort_order" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-white">
                    <h6 class="modal-title text-white">Edit Brand Details</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('brands.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Brand Name</label>
                            <input type="text" class="form-control fs-14" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Change Logo</label>
                            <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                            <div id="current-logo" class="mt-3 p-2 bg-light border rounded text-center"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Sort Order</label>
                                <input type="number" class="form-control" id="edit_sort_order" name="sort_order">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold small text-muted">Status</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning px-4">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).on('click', '.edit-brand', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const sort_order = $(this).data('sort_order');
            const status = $(this).data('status');
            const logo = $(this).data('logo');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_sort_order').val(sort_order);
            $('#edit_status').val(status);

            if (logo) {
                $('#current-logo').html(`
                    <span class="d-block small text-muted mb-1 text-uppercase fw-bold" style="font-size: 10px;">Current Image</span>
                    <img src="{{ asset('/') }}${logo}" class="rounded shadow-sm" style="max-height:60px; max-width: 100px;">
                `);
            } else {
                $('#current-logo').html('<span class="text-muted small italic">No logo currently set</span>');
            }
        });

        $('#createBrandModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });
    </script>
    @endpush
</x-backend-layout>