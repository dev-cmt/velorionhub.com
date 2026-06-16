@extends('backEnd.layouts.master')
@section('title') Return Received @endsection

@section('css')
    <link rel="stylesheet" href="{{asset('backEnd/assets/libs/flatpickr/flatpickr.min.css')}}">
    <link href="{{asset('backEnd/assets/libs/select2/select2.min.css')}}" rel="stylesheet"/>
@endsection

@php
    //$currency_sign = $data['settings']->currency_sign;
@endphp

@section('body')
    {{-- @dd($profitLoss); --}}
    <div class="card custom-card">
        <div class="card-header justify-content-between py-2" style="background-color: #e6533c3d !important;color: red">
            <div class="card-title">
                Return Received
            </div>
            <a href="{{route('return.receive.clear.temp')}}" onclick="return confirm('Are you sure to clear?')" class="btn btn-sm btn-danger btn-wave waves-effect waves-light">
                <i class="ti ti-refresh-dot d-inline-block"></i> Clear
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <div class="row">
                        <form action="" method="get" class="d-flex gap-2 align-items-center">
                            <div class="flex-grow-1">
                                <input type="text" name="query" class="form-control form-control-sm"
                                    required id="query" value="{{request()->query('query')??""}}"
                                    placeholder="🔍 Scan Barcode" autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                    <div class="btn-group btn-group-sm">
                        <a href="javascript:void(0)" class="btn btn-info print">
                            <i class="ti ti-printer"></i> Print
                        </a>
                        <a href="javascript:void(0)" class="btn btn-primary print2">
                            <i class="ti ti-printer"></i> Print 2
                        </a>
                        <a href="{{ route('return.receive.csv.export') }}" class="btn btn-warning export">
                            <i class="ti ti-file-text"></i> CSV
                        </a>
                    </div>
                </div>

            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <b>Total Received: <span class="text-danger">{{count($parcels)}}</span></b>
                </div>
            </div>
            <div class="table-responsive">
                <table id="mytable" class="table table-bordered text-wrap">
                    <thead>
                    <tr>
                        <th width="1%">SL.</th>
                        <th>Invoice No</th>
                        <th>Courier</th>
                        <th>Received Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @if(count($parcels) > 0)
                        @foreach($parcels as $key => $parcel)
                            <tr>
                                <td>{{$i++}}</td>
                                <td class="text-nowrap">{{$parcel->get_sale->invoice_no}}</td>
                                <td>{{$parcel->get_sale->get_courier?$parcel->get_sale->get_courier->name:"---"}}</td>
                                <td>{{date('d-m-Y h:i:s A',strtotime($parcel->created_at))}}</td>
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

    <audio id="errorSound" preload="auto">
        <source src="{{asset('backEnd/assets/error-2.mp3')}}" type="audio/mpeg">
    </audio>

@endsection

@section('js')
    <script src="{{asset('backEnd/assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('backEnd/assets/libs/select2/select2.min.js')}}"></script>

    <script type="text/javascript">
        $('.select2').select2();
        $('#query').focus();

        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "d-m-Y",
            //defaultDate: new Date()
        });

        $('.print').on('click', function () {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{route('return.receive.print')}}',
                type: 'POST',
                data: {_token: CSRF_TOKEN},
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
                data: {_token: CSRF_TOKEN},
                success: function(data) {
                    newWin = window.open("");
                    newWin.document.write(data);
                    newWin.document.close();
                }
            });
        });


        @if(session()->has('received_success_msg'))
            toastr.options = {
            "positionClass": "toast-top-center"
        };
        toastr.success("{{ session('received_success_msg') }}");
        @endif


            @if(session()->has('already_exist_error'))
            toastr.options = {
            "positionClass": "toast-top-center"
        };
        toastr.error("{{ session('already_exist_error') }}");

        $('#errorSound').attr('autoplay', true);
        const audio = document.getElementById('errorSound');
        audio.play().catch(err => console.error("Play error:", err));
        @endif
    </script>

@endsection

