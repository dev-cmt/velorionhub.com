<x-backend-layout title="Service Tickets">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Service Tickets</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service Tickets</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Service Tickets List</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>New Ticket
                    </button>
                </div>
                <div class="card-body">

                    {{-- Filters --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="requested">Requested</option>
                                <option value="assigned">Assigned</option>
                                <option value="inspected">Inspected</option>
                                <option value="approved">Approved</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary" id="filterBtn">Filter</button>
                            <button type="button" class="btn btn-light ms-2" id="resetFilter">Reset</button>
                        </div>
                    </div>

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any() || session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') ?? 'Please check the form for errors.' }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap" id="serviceTicketsTable">
                            <thead>
                                <tr>
                                    <th>SR No</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>SR-{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $ticket->customer_name }}</td>
                                        <td>{{ $ticket->customer_phone }}</td>
                                        <td>{{ $ticket->assignedEmployee->name ?? 'Not Assigned' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'requested' => 'warning', 'assigned' => 'info',
                                                    'inspected' => 'primary', 'approved' => 'success',
                                                    'completed' => 'dark', 'rejected' => 'danger', 'cancelled' => 'secondary',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'light' }}-transparent">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <button type="button" class="btn btn-sm btn-info-light btn-icon view-ticket" 
                                                        data-id="{{ $ticket->id }}" title="View Details">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                
                                                @if($ticket->status == 'requested')
                                                    <button type="button" class="btn btn-sm btn-warning-light btn-icon edit-ticket" 
                                                            data-ticket="{{ json_encode($ticket) }}" title="Edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary-light btn-icon assign-ticket" 
                                                            data-id="{{ $ticket->id }}" title="Assign">
                                                        <i class="ri-user-add-line"></i>
                                                    </button>
                                                @endif

                                                @if(in_array($ticket->status, ['assigned', 'inspected']))
                                                    <button type="button" class="btn btn-sm btn-info-light btn-icon report-ticket" 
                                                            data-id="{{ $ticket->id }}" 
                                                            data-notes="{{ $ticket->technician_notes }}"
                                                            data-products="{{ json_encode($ticket->products) }}"
                                                            title="Report">
                                                        <i class="ri-file-text-line"></i>
                                                    </button>
                                                @endif

                                                @if($ticket->status == 'inspected')
                                                    <button type="button" class="btn btn-sm btn-success-light btn-icon approve-ticket" 
                                                            data-id="{{ $ticket->id }}" title="Approve">
                                                        <i class="ri-check-double-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">No service tickets found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $tickets->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createTicketModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('service-tickets.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" name="customer_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="customer_phone" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address *</label>
                                <textarea name="customer_address" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="customer_notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editTicketModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editTicketForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" name="customer_name" id="edit_customer_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="customer_phone" id="edit_customer_phone" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address *</label>
                                <textarea name="customer_address" id="edit_customer_address" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="customer_notes" id="edit_customer_notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ASSIGN MODAL --}}
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="assignForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Inspection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Technician *</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Technician</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Visit Date *</label>
                            <input type="date" name="visit_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- REPORT MODAL --}}
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="reportForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Inspection Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Technician Notes *</label>
                            <textarea name="technician_notes" id="report_notes" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between">
                                Products Required
                                <button type="button" class="btn btn-xs btn-primary" onclick="addProductRow()">Add Product</button>
                            </label>
                            <div id="modalProductsContainer"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- APPROVAL MODAL --}}
    <div class="modal fade" id="approvalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="approvalForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Admin Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Decision *</label>
                            <select name="action" class="form-select" required>
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit Decision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DETAILS OFF-CANVAS --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="ticketDetailsCanvas" style="width: 450px;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="canvasTitle">Ticket Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" id="canvasBody">
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        let productIndex = 0;
        const productsList = @json($products);

        $(document).ready(function() {
            // Auto-open create modal if requested
            @if(session('open_create_modal'))
                $('#createTicketModal').modal('show');
            @endif

            // Edit Ticket
            $('.edit-ticket').click(function() {
                const ticket = $(this).data('ticket');
                $('#editTicketForm').attr('action', "{{ route('service-tickets.update', ':id') }}".replace(':id', ticket.id));
                $('#edit_customer_name').val(ticket.customer_name);
                $('#edit_customer_phone').val(ticket.customer_phone);
                $('#edit_customer_address').val(ticket.customer_address);
                $('#edit_customer_notes').val(ticket.customer_notes);
                $('#editTicketModal').modal('show');
            });

            // Assign Ticket
            $('.assign-ticket').click(function() {
                const id = $(this).data('id');
                $('#assignForm').attr('action', "{{ route('service-tickets.assign-inspection.store', ':id') }}".replace(':id', id));
                $('#assignModal').modal('show');
            });

            // Report Ticket
            $('.report-ticket').click(function() {
                const id = $(this).data('id');
                const notes = $(this).data('notes');
                const existingProducts = $(this).data('products');
                
                $('#reportForm').attr('action', "{{ route('service-tickets.inspection-report.store', ':id') }}".replace(':id', id));
                $('#report_notes').val(notes);
                $('#modalProductsContainer').empty();
                productIndex = 0;

                if(existingProducts && existingProducts.length > 0) {
                    existingProducts.forEach(p => addProductRow(p.product_id, p.quantity));
                } else {
                    addProductRow();
                }
                $('#reportModal').modal('show');
            });

            // Approve Ticket
            $('.approve-ticket').click(function() {
                const id = $(this).data('id');
                $('#approvalForm').attr('action', "{{ route('service-tickets.approval.process', ':id') }}".replace(':id', id));
                $('#approvalModal').modal('show');
            });

            // View Details (Offcanvas)
            $('.view-ticket').click(function() {
                const id = $(this).data('id');
                $('#ticketDetailsCanvas').offcanvas('show');
                $('#canvasBody').html('<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>');
                
                $.get("{{ route('service-tickets.show', ':id') }}".replace(':id', id), function(data) {
                    let productsHtml = '';
                    if(data.products && data.products.length > 0) {
                        productsHtml = `
                            <div class="mt-3">
                                <h6 class="fw-semibold fs-13">Required Products</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr><th>Product</th><th>Qty</th></tr>
                                        </thead>
                                        <tbody>
                                            ${data.products.map(p => `<tr><td>${p.product.name}</td><td>${p.quantity}</td></tr>`).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    let html = `
                        <div class="p-3">
                            <div class="mb-4 d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">SR-${String(data.id).padStart(6, '0')}</h5>
                                    <span class="badge bg-primary-transparent">${data.status.toUpperCase()}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Requested On</small>
                                    <span class="fw-semibold">${new Date(data.created_at).toLocaleDateString()}</span>
                                </div>
                            </div>

                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body p-3">
                                    <h6 class="fw-semibold fs-13 mb-2 text-primary">CUSTOMER INFO</h6>
                                    <p class="mb-1"><strong>Name:</strong> ${data.customer_name}</p>
                                    <p class="mb-1"><strong>Phone:</strong> ${data.customer_phone}</p>
                                    <p class="mb-0"><strong>Address:</strong> ${data.customer_address}</p>
                                </div>
                            </div>

                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body p-3">
                                    <h6 class="fw-semibold fs-13 mb-2 text-primary">INSPECTION INFO</h6>
                                    <p class="mb-1"><strong>Technician:</strong> ${data.assigned_employee ? data.assigned_employee.name : 'N/A'}</p>
                                    <p class="mb-1"><strong>Visit Date:</strong> ${data.visit_date ? new Date(data.visit_date).toLocaleDateString() : 'N/A'}</p>
                                    <p class="mb-0"><strong>Priority:</strong> <span class="text-capitalize">${data.priority || 'N/A'}</span></p>
                                </div>
                            </div>

                            ${data.technician_notes ? `
                                <div class="mb-3">
                                    <h6 class="fw-semibold fs-13 text-primary">TECHNICIAN NOTES</h6>
                                    <div class="p-2 border rounded bg-white fs-12">${data.technician_notes}</div>
                                </div>
                            ` : ''}

                            ${productsHtml}

                            ${data.admin_notes ? `
                                <div class="mt-3">
                                    <h6 class="fw-semibold fs-13 text-success">ADMIN NOTES</h6>
                                    <div class="p-2 border rounded bg-white fs-12">${data.admin_notes}</div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    $('#canvasBody').html(html);
                }).fail(function() {
                    $('#canvasBody').html('<div class="alert alert-danger">Failed to load details.</div>');
                });
            });

            // Filters
            $('#filterBtn').click(function() {
                const status = $('#statusFilter').val();
                window.location.href = `{{ route('service-tickets.index') }}?status=${status}`;
            });
            $('#resetFilter').click(function() {
                window.location.href = `{{ route('service-tickets.index') }}`;
            });
        });

        function addProductRow(productId = '', quantity = 1) {
            let options = '<option value="">Select Product</option>';
            productsList.forEach(p => {
                options += `<option value="${p.id}" ${p.id == productId ? 'selected' : ''}>${p.name}</option>`;
            });

            const row = `
                <div class="row g-2 mb-2 product-row">
                    <div class="col-8">
                        <select name="products[${productIndex}][product_id]" class="form-select form-select-sm" required>
                            ${options}
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control form-control-sm" value="${quantity}" min="1" required>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-sm btn-danger-light" onclick="$(this).closest('.row').remove()"><i class="ri-delete-bin-line"></i></button>
                    </div>
                </div>
            `;
            $('#modalProductsContainer').append(row);
            productIndex++;
        }
    </script>
    @endpush
</x-backend-layout>