<x-backend-layout title="Return Received">

    @push('css')
        <link rel="stylesheet" href="{{ asset('backend/libs/flatpickr/flatpickr.min.css') }}">
        <link href="{{ asset('backend/libs/select2/select2.min.css') }}" rel="stylesheet" />
    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Return Received</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Return Received</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start :: Content -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card custom-card h-100">
                <div class="card-header justify-content-between py-2" style="background-color: rgba(30, 133, 230, 0.1) !important; color: #1e85e6;">
                    <h6 class="card-title mb-0 fw-semibold text-primary">
                        <i class="bx bx-scan me-1"></i> Return Scan
                    </h6>
                    <span class="badge bg-primary rounded-pill">{{ count($processing) }} Pending</span>
                </div>

                <div class="card-body d-flex flex-column gap-3">
                    {{-- Scan form --}}
                    <form id="return-scan-form" class="d-flex gap-2">
                        <input type="text" name="scaning" class="form-control" required
                            id="scaning" value="{{ request()->query('scaning') ?? '' }}"
                            placeholder="Scan / type invoice barcode..." autocomplete="off" autofocus>
                        <button type="submit" class="btn btn-primary text-nowrap">
                            <i class="bx bx-search-alt"></i> Scan
                        </button>
                    </form>

                    {{-- Processing table --}}
                    <div class="{{ count($processing) > 0 ? '' : 'd-none' }}" id="processing_data">
                        <div class="table-responsive rounded border">
                            <table id="returnTable" class="table table-bordered table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="1%">#</th>
                                        <th>Invoice No</th>
                                        <th>Courier</th>
                                        <th>Scanned At</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($processing as $key => $parcel)
                                        <tr data-id="{{ $parcel->sale_id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-nowrap fw-semibold">{{ $parcel->get_sale->invoice_no ?? '' }}</td>
                                            <td>{{ $parcel->get_sale->get_courier ? $parcel->get_sale->get_courier->name : '---' }}</td>
                                            <td class="text-nowrap">{{ date('d-m-Y h:i A', strtotime($parcel->created_at)) }}</td>
                                            <td class="text-center align-middle">
                                                <div class="spinner-border spinner-border-sm d-none text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span class="result-text text-muted small">Pending...</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Finish button --}}
                    <div class="mt-auto">
                        <button class="btn btn-success w-100 {{ count($processing) > 0 ? '' : 'd-none' }}" id="submitToReturn">
                            <i class="bx bx-check-circle me-1"></i> Finish Return Received ({{ count($processing) }})
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card custom-card h-100">
                <div class="card-header py-2 d-flex justify-content-between align-items-center" style="background-color: #e6533c3d !important; color: red;">
                    <h6 class="card-title mb-0 fw-semibold text-danger">
                        <i class="bx bx-package me-1"></i> Total Received:
                        <strong>{{ count($parcels) }}</strong>
                    </h6>
                    <a href="{{ route('return.receive.clear.temp') }}" onclick="return confirm('Are you sure to clear all received returns?')"
                        class="btn btn-sm btn-danger">
                        <i class="bx bx-refresh me-1"></i> Clear
                    </a>
                </div>

                <div class="card-body d-flex flex-column gap-3">
                    {{-- Filters & Actions --}}
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
                        <div class="d-flex gap-2 flex-wrap">
                            {{-- Actions / Export --}}
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-info print">
                                <i class="bx bx-printer me-1"></i> Print
                            </a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary print2">
                                <i class="bx bx-printer me-1"></i> Print 2
                            </a>
                            <a href="{{ route('return.receive.csv.export', 'courier=' . request()->query('courier')) }}"
                                class="btn btn-sm btn-outline-warning export">
                                <i class="bx bx-export me-1"></i> CSV Export
                            </a>
                        </div>

                        <form action="" class="d-flex align-items-center gap-2">
                            <select name="courier" id="courier" class="form-select form-select-sm">
                                <option value="">-- All Couriers --</option>
                                @foreach ($couriers as $courier_id => $courier_name)
                                    <option value="{{ $courier_id }}"
                                        {{ request()->query('courier') == $courier_id ? 'selected' : '' }}>
                                        {{ $courier_name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Filter</button>
                            <a href="{{ route('return.receive') }}" class="btn btn-sm btn-dark">
                                <i class="bx bx-reset"></i>
                            </a>
                        </form>
                    </div>

                    {{-- Parcel table --}}
                    <div class="table-responsive rounded border">
                        <table class="table table-bordered table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="1%">#</th>
                                    <th>Invoice No</th>
                                    <th>Courier</th>
                                    <th>Received Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parcels as $i => $parcel)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="text-nowrap fw-semibold">{{ $parcel->get_sale->invoice_no ?? '' }}</td>
                                        <td>{{ $parcel->get_sale->get_courier ? $parcel->get_sale->get_courier->name : '---' }}</td>
                                        <td class="text-nowrap">{{ date('d-m-Y h:i A', strtotime($parcel->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="bx bx-package fs-3 d-block mb-1"></i>
                                            No returns received recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="errorSound" preload="auto">
        <source src="{{ asset('backend/error-2.mp3') }}" type="audio/mpeg">
    </audio>

    @push('js')
        <script src="{{ asset('backend/libs/flatpickr/flatpickr.min.js') }}"></script>
        <script src="{{ asset('backend/libs/select2/select2.min.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                var isProcessing = false;
                $('#scaning').focus();

                // ── Finish Return Receive flow ────────────────────────────────────────
                function returnEntry() {
                    var rows         = $('#returnTable tbody tr');
                    var totalSuccess = 0;
                    var totalFailed  = 0;
                    var totalRows    = rows.length;
                    isProcessing     = true;

                    function sendRow(index) {
                        if (index >= totalRows) {
                            isProcessing = false;
                            alert('Process Complete\nSuccess: ' + totalSuccess + '\nFailed: ' + totalFailed);
                            window.location.reload(); // Reload to show updated totals/stock properly
                            return;
                        }

                        var row        = $(rows[index]);
                        var orderId    = row.data('id');
                        var spinner    = row.find('.spinner-border');
                        var resultText = row.find('.result-text');

                        spinner.removeClass('d-none');
                        resultText.text('Processing...').removeClass('text-danger text-success text-muted');

                        $.ajax({
                            url: "{{ route('return.receive.send.row') }}",
                            type: "POST",
                            data: { order_id: orderId, _token: "{{ csrf_token() }}" },
                            success: function (res) {
                                spinner.addClass('d-none');
                                if (res.status) {
                                    resultText.text('Done').addClass('text-success');
                                    totalSuccess++;
                                } else {
                                    resultText.text('Failed').addClass('text-danger');
                                    totalFailed++;
                                }
                                setTimeout(function () { sendRow(index + 1); }, 800);
                            },
                            error: function () {
                                spinner.addClass('d-none');
                                resultText.text('Error').addClass('text-danger');
                                totalFailed++;
                                setTimeout(function () { sendRow(index + 1); }, 800);
                            }
                        });
                    }

                    sendRow(0);
                }

                $('#submitToReturn').on('click', function () { returnEntry(); });

                window.addEventListener('beforeunload', function (e) {
                    if (isProcessing) {
                        e.preventDefault();
                        e.returnValue = 'Return process is running. Are you sure you want to leave?';
                    }
                });

                // ── Barcode scan: Optimistic UI ──────────────────────────────────────
                $('#return-scan-form').on('submit', function (e) {
                    e.preventDefault();
                    var scaning = $('#scaning').val().trim();
                    if (!scaning) return;

                    var tbody    = $('#returnTable tbody');
                    var rowCount = tbody.find('tr').length + 1;
                    var tempId   = 'scan-' + Date.now();

                    // Add row IMMEDIATELY with spinner before server responds
                    tbody.append(
                        '<tr id="' + tempId + '">' +
                            '<td>' + rowCount + '</td>' +
                            '<td class="text-nowrap fw-semibold">' + scaning + '</td>' +
                            '<td class="text-muted small">verifying...</td>' +
                            '<td class="text-muted small">--:--</td>' +
                            '<td class="text-center">' +
                                '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>' +
                                '<span class="result-text d-none small text-muted">Pending...</span>' +
                            '</td>' +
                        '</tr>'
                    );

                    $('#processing_data').removeClass('d-none');
                    $('#submitToReturn').removeClass('d-none');
                    $('.badge.bg-primary').text(rowCount + ' Pending');
                    $('#scaning').val('').focus(); // clear instantly for next scan

                    // Fire AJAX — update row when server responds
                    $.ajax({
                        url: "{{ route('return.receive.add.temp') }}",
                        method: "GET",
                        data: { scaning: scaning },
                        success: function (response) {
                            toastr.options = { positionClass: 'toast-top-center' };

                            if (!response.status) {
                                $('#' + tempId).remove();
                                var remaining = tbody.find('tr').length;
                                $('.badge.bg-primary').text(remaining + ' Pending');
                                if (remaining === 0) {
                                    $('#processing_data').addClass('d-none');
                                    $('#submitToReturn').addClass('d-none');
                                }
                                toastr.error(response.message);
                                var snd = document.getElementById('errorSound');
                                if (snd) { snd.currentTime = 0; snd.play().catch(function () {}); }
                                return;
                            }

                            // Confirm row with real server data
                            toastr.success(response.message);
                            var row = $('#' + tempId);
                            row.attr('data-id', response.order_id);
                            row.find('td').eq(1).text(response.invoice_no);
                            row.find('td').eq(2).text(response.courier_name).removeClass('text-muted small');
                            row.find('td').eq(3).text(response.created_at).removeClass('text-muted small');
                            row.find('.spinner-border').addClass('d-none');
                            row.find('.result-text').removeClass('d-none');
                        },
                        error: function (xhr) {
                            $('#' + tempId).remove();
                            var remaining = tbody.find('tr').length;
                            $('.badge.bg-primary').text(remaining + ' Pending');
                            if (remaining === 0) {
                                $('#processing_data').addClass('d-none');
                                $('#submitToReturn').addClass('d-none');
                            }
                            toastr.options = { positionClass: 'toast-top-center' };
                            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Server error. Please try again.';
                            toastr.error(msg);
                            var snd = document.getElementById('errorSound');
                            if (snd) { snd.currentTime = 0; snd.play().catch(function () {}); }
                        }
                    });
                });

                // Print handlers
                $('.print').on('click', function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{ route('return.receive.print') }}',
                        type: 'POST',
                        data: { _token: CSRF_TOKEN, 'courier': $('#courier').val() },
                        success: function (data) {
                            newWin = window.open("");
                            newWin.document.write(data);
                            newWin.document.close();
                        }
                    });
                });

                $('.print2').on('click', function () {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{ route('return.receive.print2') }}',
                        type: 'POST',
                        data: { _token: CSRF_TOKEN, 'courier': $('#courier').val() },
                        success: function (data) {
                            newWin = window.open("");
                            newWin.document.write(data);
                            newWin.document.close();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-backend-layout>
