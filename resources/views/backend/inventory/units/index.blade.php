<x-backend-layout title="Units Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Units Management</h1>
            <p class="text-muted mb-0">Manage measurement units for your products and inventory.</p>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Units</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title">Measurement Units List</div>
                    <button type="button" class="btn btn-primary btn-sm btn-wave d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createUnitModal">
                        <i class='bx bx-plus-circle fs-18 me-1'></i> Add New Unit
                    </button>
                </div>

                <div class="card-body p-0"> {{-- Removed padding for edge-to-edge table --}}

                    {{-- Notifications --}}
                    <div class="px-4 pt-3">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li><i class='bx bx-error-circle me-1'></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center" role="alert">
                                <i class='bx bx-check-circle fs-4 me-2'></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                    </div>

                    {{-- Modern Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-4">SL</th>
                                    <th>Unit Name</th>
                                    <th>Short Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="text-center pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($units as $key => $unit)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $units->firstItem() + $key }}</td>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $unit->name }}</span>
                                        </td>
                                        <td>
                                            <code class="text-primary fw-bold">{{ $unit->short_name }}</code>
                                        </td>
                                        <td class="text-capitalize">
                                            <span class="badge bg-outline-info rounded-pill px-3">
                                                <i class='bx bx-category-alt me-1'></i>{{ $unit->unit_type }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($unit->status)
                                                <span class="badge bg-success-transparent rounded-1">
                                                    <i class='bx bxs-circle fs-8 me-1'></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger-transparent rounded-1">
                                                    <i class='bx bxs-circle fs-8 me-1'></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-list justify-content-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-icon btn-info-light edit-unit"
                                                    data-id="{{ $unit->id }}"
                                                    data-name="{{ $unit->name }}"
                                                    data-short_name="{{ $unit->short_name }}"
                                                    data-unit_type="{{ $unit->unit_type }}"
                                                    data-status="{{ $unit->status }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUnitModal">
                                                    <i class='bx bx-edit-alt'></i>
                                                </button>

                                                <form action="{{ route('units.destroy', $unit->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-icon btn-danger-light"
                                                        onclick="return confirm('Are you sure you want to delete this unit?')">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="No data" style="width: 60px; opacity: 0.3;">
                                            <p class="text-muted mt-3">No units found in the system.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer border-top-0 d-flex justify-content-center">
                        {{ $units->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-content { border-radius: 12px; border: none; }
        .modal-header { background-color: #f8f9fa; border-bottom: 1px solid #eee; border-radius: 12px 12px 0 0; }
        .form-label { font-weight: 500; color: #444; }
        .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; }
    </style>

    <div class="modal fade" id="createUnitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <form action="{{ route('units.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title d-flex align-items-center"><i class='bx bx-layer-plus fs-20 me-2 text-primary'></i> Create New Unit</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Unit Full Name</label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="e.g. Kilogram" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Short Name</label>
                                <input type="text" class="form-control" name="short_name" placeholder="e.g. kg" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unit Type</label>
                                <select class="form-select" name="unit_type" required>
                                    <option value="quantity">Quantity</option>
                                    <option value="weight">Weight</option>
                                    <option value="volume">Volume</option>
                                    <option value="length">Length</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4">Save Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUnitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <form action="{{ route('units.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h6 class="modal-title d-flex align-items-center"><i class='bx bx-edit fs-20 me-2 text-warning'></i> Update Unit</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small uppercase fw-bold">Unit Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small uppercase fw-bold">Short Name</label>
                                <input type="text" class="form-control" id="edit_short_name" name="short_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small uppercase fw-bold">Unit Type</label>
                                <select class="form-select" id="edit_unit_type" name="unit_type">
                                    <option value="quantity">Quantity</option>
                                    <option value="weight">Weight</option>
                                    <option value="volume">Volume</option>
                                    <option value="length">Length</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label text-muted small uppercase fw-bold">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Update Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).on('click', '.edit-unit', function () {
            $('#edit_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_short_name').val($(this).data('short_name'));
            $('#edit_unit_type').val($(this).data('unit_type'));
            $('#edit_status').val($(this).data('status'));
        });

        // Clear create modal inputs on hide
        $('#createUnitModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });
    </script>
    @endpush

</x-backend-layout>