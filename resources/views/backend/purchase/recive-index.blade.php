<x-backend-layout title="Purchase Receive List">

    @push('css')
    <style>
        .status-badge { font-size: 0.75rem; letter-spacing: .4px; }
        .progress-thin { height: 4px; border-radius: 2px; }
        .tbl-receive td, .tbl-receive th { vertical-align: middle; }
        .btn-receive { min-width: 90px; }
    </style>
    @endpush

    {{-- Page Header --}}
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Purchase Receive List</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Receive</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <div class="card-title mb-0">
                <i class="bx bx-package me-1 text-primary"></i> Purchase Receive List
            </div>
            <a href="{{ route('purchase.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus me-1"></i> Add Purchase
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap mb-0 tbl-receive">
                    <thead class="table-light">
                        <tr>
                            <th width="1%">#</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th class="text-center">Ordered</th>
                            <th class="text-center">Received</th>
                            <th class="text-center">Remaining</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $item)
                            @php
                                $ordered   = $item->purchase_items->sum('purchase_qty');
                                $received  = $item->purchase_items->sum('recived_qty');
                                $remaining = $ordered - $received;
                                $pct       = $ordered > 0 ? round(($received / $ordered) * 100) : 0;
                            @endphp
                            <tr id="purchase-row-{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d M Y', strtotime($item->date)) }}</td>
                                <td class="fw-semibold">{{ $item->supplier->name }}</td>

                                <td class="text-center">{{ $ordered }}</td>
                                <td class="text-center text-success fw-semibold list-received">{{ $received }}</td>
                                <td class="text-center {{ $remaining > 0 ? 'text-danger fw-semibold' : 'text-muted' }} list-remaining">
                                    {{ $remaining }}
                                </td>

                                <td class="text-end">{{ number_format($item->sub_total, 2) }}</td>

                                {{-- Status badge --}}
                                <td class="text-center">
                                    @if ($item->status == 1)
                                        <span class="badge bg-success status-badge">
                                            <i class="bx bx-check-circle me-1"></i> Completed
                                        </span>
                                    @elseif ($item->status == 2)
                                        <span class="badge bg-warning text-dark status-badge">
                                            <i class="bx bx-time me-1"></i> Partial
                                            <span class="ms-1">({{ $pct }}%)</span>
                                        </span>
                                    @else
                                        <span class="badge bg-secondary status-badge">
                                            <i class="bx bx-hourglass me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>

                                {{-- Action button --}}
                                <td class="text-center" id="action-{{ $item->id }}">
                                    @if ($item->status == 1)
                                        {{-- Fully received — no button --}}
                                        <span class="text-success small fw-semibold">
                                            <i class="bx bx-check-double"></i> Done
                                        </span>
                                    @else
                                        <button class="btn btn-sm btn-primary btn-receive view_btn"
                                            data-id="{{ $item->id }}">
                                            <i class="bx bx-download me-1"></i>
                                            {{ $item->status == 2 ? 'Continue' : 'Receive' }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bx bx-package fs-3 d-block mb-1"></i>
                                    No purchase records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $purchases->links('backend.pagination.paginate') }}
            </div>
        </div>
    </div>

    {{-- Receive Modal --}}
    <div class="modal fade" id="receiveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="modal-title fw-semibold">
                        <i class="bx bx-package me-1 text-primary"></i> Receive Stock
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" id="purchase_item_view">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2 text-muted">Loading items...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
    $(function () {
        let activePurchaseId = null;

        // ── Open modal ────────────────────────────────────────────────────────
        $(document).on('click', '.view_btn', function () {
            activePurchaseId = $(this).data('id');
            $('#receiveModal').modal('show');
            $('#purchase_item_view').html(`
                <div class="text-center p-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted">Loading items...</p>
                </div>
            `);
            $.get("{{ route('get.purchase-recive-items') }}", { id: activePurchaseId }, function (data) {
                $('#purchase_item_view').html(data);
                checkAllDoneInModal(); // in case purchase is already complete
            }).fail(function () {
                $('#purchase_item_view').html('<p class="text-danger text-center p-4">Error loading items.</p>');
            });
        });

        // ── Update a single row inside modal after receiving ──────────────────
        function updateRow(id, newReceived, purchaseQty) {
            const remaining   = purchaseQty - newReceived;
            const pct         = purchaseQty > 0 ? (newReceived / purchaseQty) * 100 : 0;
            const input       = $(`.receive-input[data-id="${id}"]`);
            const saveBtn     = $(`#save-${id}`);
            const progressBar = $(`#progress-${id} .progress-bar`);

            // Update displayed values
            $(`#received-${id}`).text(newReceived);
            $(`#remaining-${id}`).text(remaining);
            progressBar.css('width', pct + '%').attr('aria-valuenow', pct);

            if (remaining <= 0) {
                // Item fully received
                input.val('').prop('disabled', true);
                saveBtn.replaceWith(`<span class="badge bg-success" id="save-${id}"><i class="bx bx-check-circle me-1"></i>Done</span>`);
                $(`#row-${id}`).addClass('table-success');
            } else {
                input.data('max', remaining).attr('max', remaining).val('');
            }

            checkAllDoneInModal();
        }

        // ── Check if all modal items are done; update Receive All + list row ──
        function checkAllDoneInModal() {
            const pendingInputs = $('.receive-input:not(:disabled)').length;
            const saveAllBtn = $('#save-all');

            if (pendingInputs === 0) {
                // Hide Receive All button
                saveAllBtn.addClass('d-none');
            } else {
                saveAllBtn.removeClass('d-none');
            }

            syncListRowWithModal();
        }

        // ── Sync main page table row values & status badge dynamically from the modal ──
        function syncListRowWithModal() {
            if (!activePurchaseId) return;

            let totalOrdered = 0;
            let totalReceived = 0;

            // Iterate modal rows to sum actual counts
            $('#purchase_item_view tbody tr').each(function () {
                const ordered = parseInt($(this).find('td').eq(3).text()) || 0;
                const received = parseInt($(this).find('td').eq(4).find('span').text()) || 0;
                totalOrdered += ordered;
                totalReceived += received;
            });

            const totalRemaining = totalOrdered - totalReceived;
            const pct = totalOrdered > 0 ? Math.round((totalReceived / totalOrdered) * 100) : 0;

            const row = $(`#purchase-row-${activePurchaseId}`);
            if (row.length) {
                // Update received & remaining columns
                row.find('.list-received').text(totalReceived);
                const remainingCell = row.find('.list-remaining');
                remainingCell.text(totalRemaining);

                if (totalRemaining > 0) {
                    remainingCell.removeClass('text-muted').addClass('text-danger fw-semibold');
                } else {
                    remainingCell.removeClass('text-danger fw-semibold').addClass('text-muted');
                }

                // Update Status Badge and Action Button on the index table row
                const statusBadgeCell = row.find('.status-badge').parent();
                const actionCell = $(`#action-${activePurchaseId}`);

                if (totalRemaining === 0) {
                    statusBadgeCell.html(`
                        <span class="badge bg-success status-badge">
                            <i class="bx bx-check-circle me-1"></i> Completed
                        </span>
                    `);
                    actionCell.html(`
                        <span class="text-success small fw-semibold">
                            <i class="bx bx-check-double"></i> Done
                        </span>
                    `);
                } else if (totalReceived > 0) {
                    statusBadgeCell.html(`
                        <span class="badge bg-warning text-dark status-badge">
                            <i class="bx bx-time me-1"></i> Partial
                            <span class="ms-1">(${pct}%)</span>
                        </span>
                    `);
                    actionCell.html(`
                        <button class="btn btn-sm btn-primary btn-receive view_btn" data-id="${activePurchaseId}">
                            <i class="bx bx-download me-1"></i> Continue
                        </button>
                    `);
                } else {
                    statusBadgeCell.html(`
                        <span class="badge bg-secondary status-badge">
                            <i class="bx bx-hourglass me-1"></i> Pending
                        </span>
                    `);
                    actionCell.html(`
                        <button class="btn btn-sm btn-primary btn-receive view_btn" data-id="${activePurchaseId}">
                            <i class="bx bx-download me-1"></i> Receive
                        </button>
                    `);
                }
            }
        }

        // ── AJAX: save a single item ──────────────────────────────────────────
        function saveReceive(id, qty, btn, done) {
            if (qty <= 0) { if (done) done(); return; }

            const purchaseQty = parseInt($(`.receive-input[data-id="${id}"]`).data('purchase-qty'));

            btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);
            $.post("{{ route('purchase.receive.ajax') }}", {
                _token: "{{ csrf_token() }}",
                item_id: id,
                received: qty
            }).done(function (res) {
                if (res.success) {
                    updateRow(id, res.new_received, purchaseQty);
                    if (done) done(true);
                } else {
                    alert(res.message || 'Error saving.');
                    if (done) done(false);
                }
            }).fail(function () {
                alert('Server error. Please try again.');
                if (done) done(false);
            }).always(function () {
                // Restore button only if item is not yet complete
                const remaining = parseInt($(`#remaining-${id}`).text()) || 0;
                if (remaining > 0) btn.html('Save').prop('disabled', false);
            });
        }

        // ── Input validation ──────────────────────────────────────────────────
        $(document).on('input', '.receive-input', function () {
            const val     = parseInt($(this).val()) || 0;
            const max     = parseInt($(this).data('max'));
            const id      = $(this).data('id');
            const saveBtn = $(`#save-${id}`);
            const errEl   = $(`#error-${id}`);

            if (val < 1 || val > max) {
                $(this).addClass('is-invalid');
                errEl.removeClass('d-none').text(`Max allowed: ${max}`);
                saveBtn.prop('disabled', true);
            } else {
                $(this).removeClass('is-invalid');
                errEl.addClass('d-none');
                saveBtn.prop('disabled', false);
            }
        });

        // ── Single item Save ──────────────────────────────────────────────────
        $(document).on('click', '.save-receive', function () {
            const id  = $(this).data('id');
            const val = parseInt($(`.receive-input[data-id="${id}"]`).val()) || 0;
            const max = parseInt($(`.receive-input[data-id="${id}"]`).data('max'));
            if (val < 1 || val > max) return alert(`Enter valid quantity (1 – ${max})`);
            saveReceive(id, val, $(this));
        });

        // ── Receive All ───────────────────────────────────────────────────────
        $(document).on('click', '#save-all', function () {
            const btn   = $(this);
            const items = [];

            $('.receive-input:not(:disabled)').each(function () {
                const id  = $(this).data('id');
                const max = parseInt($(this).data('max'));
                const val = parseInt($(this).val()) || max; // default: fill remaining
                if (val > 0 && val <= max) items.push({ id, qty: val });
            });

            if (!items.length) return alert('No pending items to receive.');

            btn.html('<span class="spinner-border spinner-border-sm me-1"></span>Processing...').prop('disabled', true);

            let done = 0;
            items.forEach(function (it) {
                saveReceive(it.id, it.qty, $(`#save-${it.id}`), function () {
                    done++;
                    if (done === items.length) {
                        btn.html('<i class="bx bx-download me-1"></i>Receive All').prop('disabled', false);
                    }
                });
            });
        });
    });
    </script>
    @endpush

</x-backend-layout>
