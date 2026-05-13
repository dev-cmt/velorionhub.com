<x-backend-layout title="Warranty Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Warranty Management</h1>
            <p class="text-muted mb-0">Configure and manage product warranty periods and terms.</p>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Warranties</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Warranty Policies List
                    </div>
                    <button class="btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createWarrantyModal">
                        <i class='bx bx-plus-circle fs-18 me-1'></i> Add New Warranty
                    </button>
                </div>
                <div class="card-body">
                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4" role="alert">
                            <i class='bx bx-check-shield fs-4 me-2'></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" width="50">#</th>
                                    <th>Warranty Name</th>
                                    <th>Duration</th>
                                    <th>Description</th>
                                    <th width="150">Status</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warranties as $key => $warranty)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $warranties->firstItem() + $key }}</td>
                                        <td>
                                            <div class="fw-semibold text-primary">{{ $warranty->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">
                                                <i class='bx bx-time-five me-1'></i>{{ $warranty->full_duration }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted small text-truncate d-inline-block" style="max-width: 250px;">
                                                {{ $warranty->description ?? 'No description provided' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($warranty->status)
                                                <span class="badge bg-success-transparent d-flex align-items-center w-fit-content">
                                                    <i class='bx bxs-circle fs-8 me-1'></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger-transparent d-flex align-items-center w-fit-content">
                                                    <i class='bx bxs-circle fs-8 me-1'></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-list">
                                                <button class="btn btn-sm btn-icon btn-info-light edit-warranty"
                                                    data-id="{{ $warranty->id }}"
                                                    data-name="{{ $warranty->name }}"
                                                    data-duration="{{ $warranty->duration }}"
                                                    data-period="{{ $warranty->period }}"
                                                    data-description="{{ $warranty->description }}"
                                                    data-status="{{ $warranty->status }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editWarrantyModal"
                                                    title="Edit">
                                                    <i class='bx bx-edit-alt'></i>
                                                </button>

                                                <form action="{{ route('warranties.destroy', $warranty->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon btn-danger-light"
                                                        onclick="return confirm('Are you sure you want to delete this policy?')"
                                                        title="Delete">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class='bx bx-shield-x fs-1 text-muted op-3 d-block mb-2'></i>
                                            <span class="text-muted">No warranties policies found.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $warranties->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Universal Modal Styling Helper --}}
    <style>
        .w-fit-content { width: fit-content; }
        .modal-header { border-bottom: 1px solid #f3f3f3; }
        .modal-footer { border-top: 1px solid #f3f3f3; }
    </style>

    {{-- Create Warranty Modal --}}
    <div class="modal fade" id="createWarrantyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow-lg" method="POST" action="{{ route('warranties.store') }}">
                @csrf
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-semibold"><i class='bx bx-shield-plus me-1 align-middle'></i>Create New Warranty</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Warranty Name</label>
                        <input class="form-control border-2" name="name" placeholder="e.g. 1 Year Replacement Warranty" required>
                    </div>
                    <div class="row mb-3 gy-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Duration Value</label>
                            <input class="form-control" name="duration" type="number" min="1" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Time Period</label>
                            <select class="form-select" name="period">
                                <option value="day">Day(s)</option>
                                <option value="month" selected>Month(s)</option>
                                <option value="year">Year(s)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Terms & Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Describe what this warranty covers..."></textarea>
                    </div>
                    <div>
                        <label class="form-label fw-medium">Policy Status</label>
                        <select class="form-select" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary shadow-sm px-4">Save Policy</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Warranty Modal --}}
    <div class="modal fade" id="editWarrantyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow-lg" method="POST" action="{{ route('warranties.update') }}">
                @csrf
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header bg-light text-dark">
                    <h6 class="modal-title fw-semibold"><i class='bx bx-edit-alt me-1 align-middle'></i>Modify Warranty Policy</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Warranty Name</label>
                        <input class="form-control border-2 shadow-none" id="edit_name" name="name" required>
                    </div>
                    <div class="row mb-3 gy-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Duration</label>
                            <input class="form-control shadow-none" id="edit_duration" name="duration" type="number" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-muted">Period</label>
                            <select class="form-select shadow-none" id="edit_period" name="period">
                                <option value="day">Day(s)</option>
                                <option value="month">Month(s)</option>
                                <option value="year">Year(s)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted">Description</label>
                        <textarea class="form-control shadow-none" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-medium text-muted">Status</label>
                        <select class="form-select shadow-none" id="edit_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer bg-light-transparent">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Update Policy</button>
                </div>
            </form>
        </div>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            $('.edit-warranty').on('click', function () {
                $('#edit_id').val($(this).data('id'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_duration').val($(this).data('duration'));
                $('#edit_period').val($(this).data('period'));
                $('#edit_description').val($(this).data('description'));
                $('#edit_status').val($(this).data('status'));
            });
        });
    </script>
    @endpush

</x-backend-layout>