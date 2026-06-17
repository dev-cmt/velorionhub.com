<x-backend-layout title="Customers">

    @push('css')
        <link rel="stylesheet" href="{{ asset('backend/libs/sweetalert2/sweetalert2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/libs/select2/select2.min.css') }}">
    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">
                <i class="bx bx-user me-2 align-middle"></i>Customers
            </h1>
        </div>
        <div class="ms-md-1 ms-0 mt-2 mt-md-0">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Customer List -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="card-title d-flex align-items-center gap-2">
                        <span>Customer List</span>
                        <span class="badge bg-primary rounded-pill fs-12">{{ $customers->total() }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_modal">
                            <i class="bx bx-plus me-1"></i>Add Customer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">
                            <i class="bx bx-refresh me-1"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Contact</th>
                                    <th>Stores</th>
                                    <th class="text-end">Balance</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customer)
                                    <tr>
                                        <td>{{ $customers->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 32px; height: 32px; min-width: 32px;">
                                                    <span class="fw-bold text-uppercase fs-6">{{ substr($customer->name, 0, 2) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $customer->name }}</div>
                                                    @if($customer->email)
                                                        <small class="text-muted d-block">{{ $customer->email }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                                <i class="bx bx-phone me-1 text-muted"></i>{{ $customer->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($customer->get_store && $customer->get_store->count() > 0)
                                                @foreach($customer->get_store as $store)
                                                    <span class="badge bg-info bg-opacity-10 text-info me-1" style="font-weight:500;">
                                                        <i class="bx bx-store me-1"></i>{{ $store->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold {{ $customer->balance >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($customer->balance, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @if($customer->status == 1)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                    <i class="bx bx-check-circle me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">
                                                    <i class="bx bx-x-circle me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">
                                                <button class="btn btn-sm btn-info edit-btn"
                                                        data-bs-toggle="tooltip" title="Edit"
                                                        data-id="{{ $customer->id }}"
                                                        data-name="{{ $customer->name }}"
                                                        data-email="{{ $customer->email }}"
                                                        data-phone="{{ $customer->phone }}"
                                                        data-address="{{ $customer->address }}"
                                                        data-balance="{{ $customer->balance }}"
                                                        data-status="{{ $customer->status }}"
                                                        data-stores="{{ json_encode($customer->get_store ? $customer->get_store->pluck('id')->toArray() : []) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                        data-bs-toggle="tooltip" title="Delete"
                                                        onclick="deleteCustomer({{ $customer->id }}, '{{ addslashes($customer->name) }}')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="text-center py-5">
                                                <i class="bx bx-user-x text-muted" style="font-size: 3rem;"></i>
                                                <h5 class="mt-3">No Customers Found</h5>
                                                <p class="text-muted mb-0">Click "Add Customer" to create one.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($customers->hasPages())
                    <div class="card-footer border-top-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <small class="text-muted">Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }}</small>
                            {{ $customers->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="add_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bx bx-user-plus me-2"></i>Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="add_form">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                                    <label>Name <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg name_error" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                    <label>Email</label>
                                    <span class="text-danger error-msg email_error" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="phone" placeholder="Phone" required>
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg phone_error" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" class="form-control" name="balance" placeholder="Balance" value="0" required>
                                    <label>Balance <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg balance_error" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="address" placeholder="Address" style="height:80px;"></textarea>
                                    <label>Address</label>
                                    <span class="text-danger error-msg address_error" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted"><i class="bx bx-store me-1"></i>Assigned Stores</label>
                                <select class="form-control select2-modal" name="store[]" multiple>
                                    @foreach($stores as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-msg store_error" style="font-size:.8rem;"></span>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <label>Status <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg status_error" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="bx bx-x me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bx bx-edit me-2"></i>Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="edit_form">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="name" id="edit_name" placeholder="Name" required>
                                    <label>Name <span class="text-danger">*</span></label>
                                    <span class="text-danger uerror-msg name_uerror" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" name="email" id="edit_email" placeholder="Email">
                                    <label>Email</label>
                                    <span class="text-danger uerror-msg email_uerror" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="phone" id="edit_phone" placeholder="Phone" required>
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <span class="text-danger uerror-msg phone_uerror" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01" class="form-control" name="balance" id="edit_balance" placeholder="Balance" required>
                                    <label>Balance <span class="text-danger">*</span></label>
                                    <span class="text-danger uerror-msg balance_uerror" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" name="address" id="edit_address" placeholder="Address" style="height:80px;"></textarea>
                                    <label>Address</label>
                                    <span class="text-danger uerror-msg address_uerror" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-muted"><i class="bx bx-store me-1"></i>Assigned Stores</label>
                                <select class="form-control select2-modal" name="store[]" id="edit_stores" multiple>
                                    @foreach($stores as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger uerror-msg store_uerror" style="font-size:.8rem;"></span>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" name="status" id="edit_status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <label>Status <span class="text-danger">*</span></label>
                                    <span class="text-danger uerror-msg status_uerror" style="font-size:.8rem;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="bx bx-x me-1"></i>Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script src="{{ asset('backend/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('backend/libs/select2/select2.min.js') }}"></script>

        <script>
        $(document).ready(function() {
            // Init Select2
            $('.select2-modal').each(function() {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal'),
                    placeholder: '-- Select Stores --',
                    allowClear: true,
                    closeOnSelect: false,
                    width: '100%'
                });
            });

            // Tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Add Customer
            $("#add_form").on('submit', function(e) {
                e.preventDefault();
                $('.error-msg').text('');
                $('.form-control').removeClass('is-invalid');

                $.ajax({
                    url: "{{ route('customers.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    beforeSend: () => $('#add_submit_btn').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Saving...'),
                    success: function(data) {
                        if (data.res_status == 0) {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                                $('[name="' + prefix + '"]').addClass('is-invalid');
                            });
                        } else {
                            $('#add_modal').modal('hide');
                            $('#add_form')[0].reset();
                            $('#add_stores').val(null).trigger('change');
                            Swal.fire({ icon: 'success', title: 'Added!', timer: 1500, showConfirmButton: false })
                                .then(() => location.reload());
                        }
                    },
                    complete: () => $('#add_submit_btn').prop('disabled', false).html('<i class="bx bx-save me-1"></i>Save')
                });
            });

            // Edit - Load Data
            $(document).on('click', '.edit-btn', function() {
                let d = $(this).data();
                $('#edit_id').val(d.id);
                $('#edit_name').val(d.name);
                $('#edit_email').val(d.email);
                $('#edit_phone').val(d.phone);
                $('#edit_address').val(d.address);
                $('#edit_balance').val(d.balance);
                $('#edit_status').val(d.status);
                $('#edit_stores').val(d.stores).trigger('change');
                $('.uerror-msg').text('');
                $('.form-control').removeClass('is-invalid');
                $('#edit_modal').modal('show');
            });

            // Update Customer
            $("#edit_form").on('submit', function(e) {
                e.preventDefault();
                $('.uerror-msg').text('');
                $('.form-control').removeClass('is-invalid');

                $.ajax({
                    url: "{{ route('customers.update') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    beforeSend: () => $('#edit_submit_btn').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-1"></i>Updating...'),
                    success: function(data) {
                        if (data.res_status == 0) {
                            $.each(data.error, function(prefix, val) {
                                $('span.' + prefix + '_uerror').text(val[0]);
                                $('[name="' + prefix + '"]').addClass('is-invalid');
                            });
                        } else {
                            $('#edit_modal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Updated!', timer: 1500, showConfirmButton: false })
                                .then(() => location.reload());
                        }
                    },
                    complete: () => $('#edit_submit_btn').prop('disabled', false).html('<i class="bx bx-save me-1"></i>Update')
                });
            });

            // Clear errors on input
            $('.form-control, .form-select').on('input change', function() {
                $(this).removeClass('is-invalid');
                let name = $(this).attr('name');
                if (name) {
                    $(`span.${name}_error, span.${name}_uerror`).text('');
                }
            });

            // Reset modals on hide
            $('#add_modal').on('hidden.bs.modal', function() {
                $('#add_form')[0].reset();
                $('#add_stores').val(null).trigger('change');
                $('.error-msg').text('');
                $('.form-control').removeClass('is-invalid');
            });

            $('#edit_modal').on('hidden.bs.modal', function() {
                $('.uerror-msg').text('');
                $('.form-control').removeClass('is-invalid');
            });
        });

        // Delete Customer
        function deleteCustomer(id, name) {
            Swal.fire({
                title: 'Delete Customer?',
                html: `Are you sure you want to delete <strong>"${name}"</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bx bx-trash me-1"></i>Delete',
                cancelButtonText: '<i class="bx bx-x me-1"></i>Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    $.ajax({
                        url: "{{ route('customers.destroy', ':id') }}".replace(':id', id),
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(data) {
                            if (data.res_status == 1) {
                                Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false })
                                    .then(() => location.reload());
                            } else {
                                Swal.fire({ icon: 'error', title: 'Error!', text: data.message || 'Failed to delete' });
                            }
                        },
                        error: function() {
                            Swal.fire({ icon: 'error', title: 'Error!', text: 'Something went wrong' });
                        }
                    });
                }
            });
        }
        </script>
    @endpush

</x-backend-layout>
