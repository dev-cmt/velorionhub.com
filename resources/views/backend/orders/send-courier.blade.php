<x-backend-layout title="Orders Management">
    <div class="row my-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Send Courier</h4>
            <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
                <!-- Summary Buttons -->
                <div id="courierSummary" class="d-flex gap-2">
                    <button class="btn btn-outline-dark btn-sm" disabled>
                        Total: <span id="totalCount">0</span>
                    </button>
                    <button class="btn btn-outline-success btn-sm" disabled>
                        ✅ Success: <span id="successCount">0</span>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" disabled>
                        ❌ Failed: <span id="failedCount">0</span>
                    </button>
                    <style>
                        #courierSummary {
                            position: relative;
                            transition: all 0.3s ease;
                            z-index: 1050;
                        }

                        #courierSummary.sticky {
                            position: fixed;
                            top: 65px;
                            right: 30px;
                            background-color: #f0f1f7;
                            padding: 5px 7px;
                        }
                    </style>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const summary = document.getElementById("courierSummary");
                            const offsetTop = summary.offsetTop;

                            window.addEventListener("scroll", function() {
                                if (window.scrollY >= offsetTop) {
                                    summary.classList.add("sticky");
                                } else {
                                    summary.classList.remove("sticky");
                                }
                            });
                        });
                    </script>
                </div>

                <!-- Action Buttons -->
                <button class="btn btn-success btn-sm d-none" id="submitToCourier">
                    <i class='bx bx-revision'></i> Re-Submit
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-danger btn-sm d-none" id="backToList">
                    <i class='bx bxs-share'></i> Back
                </a>
            </div>

        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="courierTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Courier</th>
                            <th>Courier</th>
                            <th>Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr data-id="{{ $order->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $order->store->name }}</span>
                                    <br>{{ $order->invoice_no }}
                                </td>
                                <td class="text-start">
                                    <b>Name: </b>{{$order->customer_name ?? ''}}<br>
                                    <b>Phone: </b>{{$order->customer_phone ?? ''}}
                                    <a href="tel:{{$order->customer_phone ?? ''}}">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    <br><b>Address: </b>{{$order->customer_address ?? ''}}
                                </td>
                                <td>{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <select name="courier_id" class="form-control form-control-sm courier-id">
                                        @foreach ($couriers as $courier)
                                            <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                                {{ $courier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    @if ($order->consignment_id)
                                        <span class="badge bg-secondary consignment-id">{{ $order->consignment_id }}</span>
                                        <br>
                                    @endif
                                    <span class="courier-name">{{ $order->courier ? $order->courier->name : '---' }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="spinner-border d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="result-text"></span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.city-zone-dropdown').select2({
                    width: '100%',
                    placeholder: "Search...",
                    allowClear: true
                });

                var isProcessing = false;
                courierEntry();

                function courierEntry() {
                    var rows = $('#courierTable tbody tr').toArray();
                    var totalSuccess = 0;
                    var totalFailed = 0;

                    // Hide re-submit button at start
                    $('#totalCount').text(rows.length);

                    isProcessing = true; // mark processing started

                    function updateSummary() {
                        $('#successCount').text(totalSuccess);
                        $('#failedCount').text(totalFailed);
                    }

                    function sendRow(index) {
                        if (index >= rows.length) {
                            isProcessing = false;

                            // Show re-submit button if any failures
                            if (totalFailed > 0) {
                                $('#submitToCourier').removeClass('d-none');
                            } else {
                                $('#backToList').removeClass('d-none');
                                alert("✅ All rows processed successfully!");
                            }
                            return;
                        }

                        var row = $(rows[index]);
                        var orderId = row.data('id');
                        var courierId = row.find('.courier-id').val();
                        var spinner = row.find('.spinner-border');
                        var resultText = row.find('.result-text');
                        var courierNameEl = row.find('.courier-name');
                        var consignmentEl = row.find('.consignment-id');

                        spinner.removeClass('d-none');
                        resultText.addClass('d-none');

                        // Auto scroll to current row
                        $('html, body').animate({
                            scrollTop: row.offset().top - 100
                        }, 300);

                        $.ajax({
                            url: "{{ route('courier.send.row') }}",
                            type: "POST",
                            data: {
                                order_id: orderId,
                                courier_id: courierId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                spinner.addClass('d-none');
                                if (res.status) {
                                    resultText
                                        .removeClass('d-none text-muted text-danger')
                                        .addClass('text-success')
                                        .text('✅ ' + res.message);

                                    totalSuccess++;
                                    updateSummary();

                                    if (res.consignment_id) {
                                        if (consignmentEl.length) {
                                            consignmentEl.text(res.consignment_id);
                                        } else {
                                            row.find('td:nth-child(6)').prepend(
                                                '<span class="badge bg-secondary consignment-id">' + res.consignment_id + '</span><br>');
                                        }
                                    }
                                    courierNameEl.text(res.courier ?? courierNameEl.text());
                                } else {
                                    resultText
                                        .removeClass('d-none text-muted text-success')
                                        .addClass('text-danger')
                                        .text('❌ ' + (res.message || 'Failed'));

                                    totalFailed++;
                                    updateSummary();
                                }
                                setTimeout(() => sendRow(index + 1), 1000);
                            },
                            error: function(xhr, status, error) {
                                spinner.addClass('d-none');

                                // Try to parse JSON from response
                                let message = '❌ Error';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    message = '❌ ' + xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    message = '❌ ' + xhr.responseText;
                                }
                                resultText
                                    .removeClass('d-none text-muted text-success')
                                    .addClass('text-danger')
                                    .text(message);

                                totalFailed++;
                                updateSummary();
                                setTimeout(() => sendRow(index + 1), 1000);
                            }
                        });
                    }

                    sendRow(0); // start sending from first row
                }

                // Submit button click
                $('#submitToCourier').on('click', function() {
                    courierEntry();
                });

                // Warn user if they try to reload or leave page
                window.addEventListener('beforeunload', function(e) {
                    if (isProcessing) {
                        e.preventDefault();
                        e.returnValue = "⚠️ Courier process is running. Are you sure you want to leave?";
                    }
                });

            });
        </script>
    @endpush
</x-backend-layout>
