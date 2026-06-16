@extends('backEnd.layouts.master')
@section('title')
    Parcel Handover
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/flatpickr/flatpickr.min.css') }}">
    <link href="{{ asset('backEnd/assets/libs/select2/select2.min.css') }}" rel="stylesheet" />
    <style>
        .table td, .table th{
            padding: 5px;
        }
    </style>
@endsection

@php
    //$currency_sign = $data['settings']->currency_sign;
@endphp

@section('body')
    {{-- @dd($profitLoss); --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between py-2" style="background-color: #845adf3b !important; color: #845adf">
                    <div class="card-title"> Parcel Scan </div>
                </div>
                <div class="card-body pt-1" style="height: calc(100vh - 130px); overflow-y: auto;">
                    <div class="d-flex justify-content-between mb-1">
                        {{-- <form action="{{ route('parcel.handover.add.temp') }}" method="get">--}}
                        <form id="parcen-scan-form">
                            <input type="text" name="scaning" class="form-control form-control-sm" required
                                id="scaning" value="{{ request()->query('scaning') ?? '' }}" placeholder="Scan Barcode"
                                autocomplete="off" autofocus>
                        </form>

                        <button class="btn btn-primary btn-sm {{ count($processing) > 0 ? '' :'d-none'}}" id="submitToHandover">
                            <i class='bx bx-save'></i> Finish Handover
                        </button>
                    </div>

                    <div class="{{count($processing) > 0 ? '': 'd-none'}} table-responsive" id="processing_data">
                        <table id="courierTable" class="table table-bordered text-wrap">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Invoice No</th>
                                    <th>Courier</th>
                                    <th>Handover Time</th>
                                    <th class="text-center">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($processing as $key => $parcel)
                                    <tr data-id="{{ $parcel->sale_id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-nowrap">{{ $parcel->invoice_no ?? '' }}</td>
                                        <td class="courier-name">
                                            {{ $parcel->courier_name ?? '---' }}
                                        </td>
                                        <td>{{ date('d-m-Y h:i:s A', strtotime($parcel->created_at)) }}</td>
                                        <td class="text-center align-middle">
                                            <div class="spinner-border d-none" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="result-text text-muted">Pending...</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card">
                <div class="card-header justify-content-between py-2" style="background-color: #26bf943d !important; color: green">
                    <div class="card-title">
                        Total Handover: <strong>{{ count($parcels) }}</strong>
                    </div>
                    <a href="{{ route('parcel.handover.clear.temp') }}" onclick="return confirm('Are you sure to clear?')"
                        class="btn btn-sm btn-danger btn-wave waves-effect waves-light">
                        <i class="ti ti-refresh-dot d-inline-block"></i> Clear
                    </a>
                </div>
                <div class="card-body pt-1" style="height: calc(100vh - 130px); overflow-y: auto;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="mb-2">
                            {{-- <form action="" method="get" class=" d-inline-block">
                                <input type="text" name="query" class="form-control form-control-sm" required id="query" value="{{request()->query('query')??""}}" placeholder="Scan Barcode" autocomplete="off">
                            </form> --}}
                            <a href="{{ route('report.parcel.handover.sales', ['date_range' => now()->format('d-m-Y'), 'is_temp' => 1 ]) }}"
                                target="_blank" class="btn btn-sm btn-success"> <i class="ti ti-printer"></i> Sales </a>

                            <a href="javascript:void(0)" class="btn btn-sm btn-info print">Print</a>
                            <a href="javascript:void(0)" class="btn btn-sm btn-info print2">Print2</a>
                            <a href="{{ route('parcel.handover.csv.export', 'courier=' . request()->query('courier')) }}" class="btn btn-sm btn-warning export">CSV Export</a>
                        </div>
                        <div class="d-flex gap-1 flex-wrap align-items-center">
                            <form action="" class=" d-flex align-items-center flex-wrap gap-1">
                                <div>
                                    <select name="courier" id="courier" class="form-select form-select-sm">
                                        <option value="">--Select Courier--</option>
                                        @foreach ($couriers as $courier_id => $courier_name)
                                            <option value="{{ $courier_id }}"
                                                {{ request()->query('courier') == $courier_id ? 'selected' : '' }}>{{ $courier_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div>
                                    <input type="submit" value="Search" class="btn btn-success btn-sm">
                                    <a href="{{ route('parcel.handover') }}" class="btn btn-sm btn-dark">
                                        <i class="ti ti-refresh"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="mytable" class="table table-bordered text-wrap">
                            <thead>
                                <tr>
                                    <th width="1%">SL.</th>
                                    <th>Invoice No</th>
                                    <th>Courier</th>
                                    <th>Handover Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @if (count($parcels) > 0)
                                    @foreach ($parcels as $key => $parcel)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td class="text-nowrap">{{ $parcel->invoice_no ?? '' }}</td>
                                            <td>{{ $parcel->courier_name ?? '---' }}</td>
                                            <td>{{ date('d-m-Y h:i:s A', strtotime($parcel->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="17" class="text-danger text-center">No Data Available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="errorSound" preload="auto">
        <source src="{{ asset('backEnd/assets/error-2.mp3') }}" type="audio/mpeg">
    </audio>
@endsection

@section('js')
    <script src="{{ asset('backEnd/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('backEnd/assets/libs/select2/select2.min.js') }}"></script>

    <script type="text/javascript">
        $('.select2').select2();
        $('#scaning').focus();

        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "d-m-Y",
            //defaultDate: new Date()
        });

        $(document).on('click', '.print', function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('parcel.handover.print') }}',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    'courier': $('#courier').val()
                },
                success: function(data) {
                    newWin = window.open("");
                    newWin.document.write(data);
                    newWin.document.close();
                }
            });
        });

        $(document).on('click', '.print2', function() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('parcel.handover.print2') }}',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    'courier': $('#courier').val()
                },
                success: function(data) {
                    newWin = window.open("");
                    newWin.document.write(data);
                    newWin.document.close();
                }
            });
        });

        @if (session()->has('handover_success_msg'))
            toastr.options = {
                "positionClass": "toast-top-center"
            };
            toastr.success("{{ session('handover_success_msg') }}");
        @endif


        @if (session()->has('already_exist_error'))
            toastr.options = {
                "positionClass": "toast-top-center"
            };
            toastr.error("{{ session('already_exist_error') }}");

            $('#errorSound').attr('autoplay', true);
            const audio = document.getElementById('errorSound');
            audio.play().catch(err => console.error("Play error:", err));
        @endif
    </script>

    <script>
        $(document).ready(function() {
            var isProcessing = false;

            function courierEntry() {
                var rows = $('#courierTable tbody tr');
                var totalSuccess = 0;
                var totalFailed = 0;
                var totalRows = rows.length;

                isProcessing = true;

                function updateSummary() {
                    console.log(`✅ Success: ${totalSuccess}, ❌ Failed: ${totalFailed}`);
                }

                function sendRow(index) {
                    if (index >= totalRows) {
                        isProcessing = false;
                        alert(`✅ Process Complete\nSuccess: ${totalSuccess}\nFailed: ${totalFailed}`);
                        // location.reload(); // $('#submitToHandover').hide();
                        return;
                    }

                    var row = $(rows[index]);
                    var saleId = row.data('id');
                    var spinner = row.find('.spinner-border');
                    var resultText = row.find('.result-text');

                    spinner.removeClass('d-none');
                    resultText.text('Processing...').removeClass('text-danger text-success');

                    $.ajax({
                        url: "{{ route('parcel.handover.send.row') }}",
                        type: "POST",
                        data: {
                            sale_id: saleId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            spinner.addClass('d-none');
                            if (res.status) {
                                resultText.text('✅ ' + res.message).addClass('text-success');
                                totalSuccess++;
                            } else {
                                resultText.text('❌ ' + res.message).addClass('text-danger');
                                totalFailed++;
                            }
                            updateSummary();
                            setTimeout(() => sendRow(index + 1), 800);
                        },
                        error: function() {
                            spinner.addClass('d-none');
                            resultText.text('❌ Server error').addClass('text-danger');
                            totalFailed++;
                            updateSummary();
                            setTimeout(() => sendRow(index + 1), 800);
                        }
                    });
                }

                sendRow(0);
            }

            $('#submitToHandover').on('click', function() {
                courierEntry();
            });

            window.addEventListener('beforeunload', function(e) {
                if (isProcessing) {
                    e.preventDefault();
                    e.returnValue = "⚠️ Handover process is running. Are you sure you want to leave?";
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#parcen-scan-form').on('submit', function(e) {
                e.preventDefault();
                let scaning = $('#scaning').val();

                $.ajax({
                    url: "{{ route('parcel.handover.add.temp') }}",
                    method: "GET",
                    data: { scaning: scaning },
                    success: function(response) {
                        toastr.options = {"positionClass": "toast-top-center"};
                        toastr.success(response.message);

                        $('#submitToHandover', ).removeClass('d-none');
                        $('#processing_data').removeClass('d-none');

                        $('#processing_data').load(window.location.href + ' #processing_data > *');
                        $('#scaning').val('').focus();
                    },
                    error: function(xhr) {
                        let err = xhr.responseJSON?.message || 'Something went wrong';

                        toastr.options = {"positionClass": "toast-top-center"};
                        toastr.error(err);

                        const audio = document.getElementById('errorSound');
                        if (audio) {
                            audio.currentTime = 0; // rewind to start
                            audio.play().catch(err => console.error("Audio play failed:", err));
                        }

                        $('#scaning').val('').focus();
                    }
                });
            });

        });
    </script>

@endsection
