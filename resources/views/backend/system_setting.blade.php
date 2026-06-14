<x-backend-layout title="System Settings">
    @push('css')

    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">System Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">System Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         COURIER MANAGEMENT SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="row mt-4">
        <div class="col-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title mb-0">Courier Management</div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCourierModal">
                        <i class="ri-add-line me-1"></i> Add Courier
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($couriers ?? [] as $courier)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $courier->name }}</td>
                                    <td><code>{{ $courier->slug }}</code></td>
                                    <td class="text-center">
                                        <form action="{{ route('couriers.toggle', $courier) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $courier->status ? 'btn-success' : 'btn-secondary' }}"
                                                title="{{ $courier->status ? 'Active – click to disable' : 'Inactive – click to enable' }}">
                                                {{ $courier->status ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-warning me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCourierModal{{ $courier->id }}">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <form action="{{ route('couriers.destroy', $courier) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Delete this courier?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Edit Modal for each courier --}}
                                <div class="modal fade" id="editCourierModal{{ $courier->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('couriers.update', $courier) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Courier</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Courier Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ $courier->name }}" required>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="status"
                                                            id="editStatus{{ $courier->id }}"
                                                            {{ $courier->status ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="editStatus{{ $courier->id }}">Active</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No couriers added yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Courier Modal --}}
    <div class="modal fade" id="addCourierModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('couriers.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Courier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Courier Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Pathao, Steadfast" required>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="addStatus" checked>
                            <label class="form-check-label" for="addStatus">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Courier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    @if(session('success'))
    <script>
        // Auto-open edit modal if validation failed after edit
    </script>
    @endif
    @endpush
</x-backend-layout>
