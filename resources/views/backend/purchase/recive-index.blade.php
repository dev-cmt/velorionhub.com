@extends('backEnd.layouts.master')
@section('title', 'Purchase Receive')

@section('css')
    <style>
        .receive-input {
            width: 80px;
        }

        .received-display {
            width: 60px;
            text-align: center;
        }

        .progress-sm {
            height: 5px;
        }

        .item-row {
            transition: background-color 0.3s;
        }

        .item-row.updated {
            background-color: rgba(40, 167, 69, 0.2);
        }
    </style>
@endsection

@section('body')
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title">Purchase Receive List</div>
            <a href="{{ route('purchase.create') }}" class="btn btn-sm btn-success-gradient">Add Purchase</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Purchase Qty</th>
                            <th>Received Qty</th>
                            <th>Stock Qty</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d M Y', strtotime($item->date)) }}</td>
                                <td>{{ $item->get_supplier->name }}</td>
                                <td>{{ $item->purchase_items->sum('purchase_qty') }}</td>
                                <td>{{ $item->purchase_items->sum('recived_qty') }}</td>
                                <td>{{ $item->purchase_items->sum('quantity') }}</td>
                                <td>{{ $setting->currency_sign }} {{ number_format($item->sub_total, 2) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success-gradient view_btn" data-id="{{ $item->id }}">
                                        Receive
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">No Data Available!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $purchases->links('backEnd.includes.paginate') }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="view" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Purchase Item(s) - Receive Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="purchase_item_view">
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Loading purchase items...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function() {

            // Open modal and load purchase items
            $(document).on('click', '.view_btn', function() {
                const id = $(this).data('id');
                $('#view').modal('show');
                $('#purchase_item_view').html(`
            <div class="text-center p-3">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2">Loading purchase items...</p>
            </div>
        `);
                $.get("{{ route('get.purchase-recive-items') }}", {
                    _token: "{{ csrf_token() }}",
                    id
                }, function(data) {
                    $('#purchase_item_view').html(data);
                }).fail(() => {
                    $('#purchase_item_view').html(
                        '<p class="text-danger text-center">Error loading purchase items.</p>');
                });
            });

            // Update row UI
            function updateRow(id, newReceived, purchaseQty) {
                const input = $(`.receive-input[data-id="${id}"]`);
                const saveBtn = $(`#save-${id}`);
                const row = $(`#row-${id}`);
                const progressBar = $(`#progress-${id} .progress-bar`);
                const remaining = purchaseQty - newReceived;

                $(`#received-${id}`).val(newReceived);
                $(`#remaining-${id}`).text(remaining);
                input.data('max', remaining).attr('max', remaining).val('');
                progressBar.css('width', (newReceived / purchaseQty) * 100 + '%');

                if (remaining === 0) {
                    input.prop('disabled', true);
                    saveBtn.removeClass('btn-success').addClass('btn-secondary').text('Completed');
                }

                row.addClass('updated');
                setTimeout(() => row.removeClass('updated'), 2000);
            }

            // AJAX save for single or all
            function saveReceive(id, qty, btn, done) {
                if (qty <= 0) {
                    if (done) done();
                    return;
                } // Skip invalid

                btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);
                $.post("{{ route('purchase.receive.ajax') }}", {
                    _token: "{{ csrf_token() }}",
                    item_id: id,
                    received: qty
                }).done(res => {
                    if (res.success) {
                        updateRow(id, res.new_received, parseInt($(`.receive-input[data-id="${id}"]`).data(
                            'purchase-qty')));
                        if (done) done(true);
                    } else {
                        alert(res.message || 'Error saving.');
                        if (done) done(false);
                    }
                }).fail(() => {
                    alert('Server error');
                    if (done) done(false);
                }).always(() => btn.html('Save').prop('disabled', false));
            }

            // Input validation
            $(document).on('input', '.receive-input', function() {
                const val = parseInt($(this).val()) || 0;
                const max = parseInt($(this).data('max'));
                const id = $(this).data('id');
                const errorEl = $(`#error-${id}`);
                const saveBtn = $(`#save-${id}`);

                if (val < 1 || val > max) {
                    $(this).addClass('is-invalid');
                    errorEl.removeClass('d-none').text(`Max: ${max}`);
                    saveBtn.prop('disabled', true);
                } else {
                    $(this).removeClass('is-invalid');
                    errorEl.addClass('d-none');
                    saveBtn.prop('disabled', false);
                }
            });

            // Single Save
            $(document).on('click', '.save-receive', function() {
                const id = $(this).data('id');
                const input = $(`.receive-input[data-id="${id}"]`);
                const val = parseInt(input.val()) || 0;
                const max = parseInt(input.data('max'));
                if (val < 1 || val > max) return alert(`Enter valid quantity (1-${max})`);
                saveReceive(id, val, $(this));
            });

            // Save All
            $(document).on('click', '#save-all', function() {
                const btn = $(this);
                const items = [];

                $('.receive-input:not(:disabled)').each(function() {
                    const id = $(this).data('id');
                    const max = parseInt($(this).data('max'));
                    const val = parseInt($(this).val()) || 0;
                    if (val > 0 && val <= max) {
                        items.push({
                            id,
                            qty: val
                        });
                    }
                });

                if (!items.length) return alert('No valid quantities to receive.');

                btn.html('<span class="spinner-border spinner-border-sm"></span> Processing...').prop(
                    'disabled', true);

                let doneCount = 0;
                items.forEach(it => {
                    saveReceive(it.id, it.qty, $('#save-' + it.id), () => {
                        doneCount++;
                        if (doneCount === items.length) {
                            btn.html('Receive All').prop('disabled', false);
                        }
                    });
                });
            });

        });
    </script>
@endsection
