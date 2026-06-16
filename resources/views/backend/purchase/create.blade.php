@extends('backEnd.layouts.master')
@section('title')
    Purchase
@endsection
@section('css')
    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{asset('backEnd/assets/libs/flatpickr/flatpickr.min.css')}}">
    <link href="{{asset('backEnd/assets/libs/select2/select2.min.css')}}" rel="stylesheet"/>
    <style>
        table td {
            vertical-align: top !important;
        }
        .no_data{
            display: table-row;
        }
    </style>
@endsection
@php
    $suppliers = $data['suppliers'] ?? 0;
    $products = $data['products'] ?? 0;
@endphp
@section('body')
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Purchase Create
            </div>
            <div class="prism-toggle">
                <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-danger"><i class="ti ti ti-chevron-left"></i>Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('purchase.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <div class="col-md-12">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <label class="form-label" for="supplier_id">Supplier <span class="text-danger">*</span></label>
                                    <select class="form-select select2" name="supplier_id" id="supplier_id" required>
                                        <option value="">--Select Supplier--</option>
                                        @foreach ($suppliers as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                                <input type="text" name="date" class="form-control" required id="date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="status" id="status" required>
                                    <option value="">--Select Status--</option>
                                    <option value="0">Ordered</option>
                                    <option value="1">Received</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label" for="memo_number">Memo Number</label>
                                <input type="text" class="form-control" name="memo_number" id="memo_number">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="1" placeholder="Remarks"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-flex justify-content-center mb-3">
                    <div class="col-md-10">
                        <div class="input-group input-group-sm mb-3">
                            <select class="form-select select2" name="select_product" id="select_product" required>
                                <option value="" selected="">--Select Product--</option>
                                @foreach ($products as $key => $product)
                                    <option value="{{ $key }}">{{ $product }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-nowrap table-bordered" id="itemTable">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th width="59%">Product Name</th>
                            <th width="10%">Purchase Quantity</th>
                            <th width="10%">Purchase Cost</th>
                            <th width="10%">Sell Price</th>
                            {{-- <th>Discount</th>
                             <th>Tax</th>
                             <th>Profit Margin %</th>--}}
                            <th width="10%">Total</th>
                            <th width="1%"></th>
                        </tr>
                        </thead>
                        <tbody id="product-row-put">
                        <tr class="no_data">
                            <td colspan="6" class="text-center text-danger">No Data Found!</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="3" class="text-end p-1"><b>Sub Total:</b></th>
                            <td colspan="3" class="p-1">
                                <input type="number" readonly class="form-control text-end" name="sub_total" id="sub_total" value="0.00"
                                       aria-describedby="inputGroup-sizing-sm">
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3" class="text-end p-1"><b>Discount:</b></th>
                            <td colspan="3" class="p-1">
                                <input type="number" class="form-control text-end" name="discount" id="discount" value="0.00"
                                       aria-describedby="inputGroup-sizing-sm">
                            </td>
                        </tr>

                        {{--<tr>
                            --}}{{-- from product table --}}{{--
                            <th class="col-md-8 text-end">Previous Due:</th>
                            <td class="col-md-4 text-end">
                                <input type="number" readonly class="form-control" name="previous_due" id="previous_due" value="0.00"
                                       aria-describedby="inputGroup-sizing-sm">
                            </td>
                        </tr>--}}

                        <tr>
                            {{-- (subl_total + previous_due) --}}
                            <th colspan="3" class="text-end p-1"><b>Total :</b></th>
                            <td colspan="3" class="p-1">
                                <input type="number" readonly class="form-control text-end" name="balance" id="balance" value="0.00"
                                       aria-describedby="inputGroup-sizing-sm">
                            </td>
                        </tr>

                        {{--<tr>
                            --}}{{-- (subl_total + previous_due) --}}{{--
                            <th colspan="3" class="text-end p-1"><b>Paid:</b></th>
                            <td colspan="3" class="p-1">
                                <input type="number" class="form-control text-end" name="paid_amount" id="paid_amount" value="0.00"
                                       aria-describedby="inputGroup-sizing-sm">
                            </td>
                        </tr>

                        <tr>
                            <th colspan="3" class="text-end p-1"><b>Due:</b></th>
                            <td colspan="3" class="p-1">
                                <input type="number" readonly class="form-control text-end" name="due_amount" id="due_amount" value="0.00"
                                       aria-describedby="inputGroup-sizing-sm">
                            </td>
                        </tr>--}}
                        </tfoot>
                    </table>
                </div>
                <div class="row mt-2">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <b>Payment</b>
                                <button type="button" id="full_paid_btn" class="btn btn-sm btn-info">Full Paid?</button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-3">
                                        <label for="payment_mode" class="form-label">Payment Method <span class="text-danger fw-bold">*</span></label>
                                        <select class="form-control" name="payment_mode" id="payment_mode">
                                            <option value="">--Select Payment method--</option>
                                            @foreach($data['payment_methods'] as $id => $name)
                                                <option value="{{$id}}" {{ $id == old('payment_mode') ? "selected":""}}> {{ $name }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-12 mb-3">
                                        <label for="paid_amount" class="form-label">Paid Amount <span class="text-danger fw-bold">*</span></label>
                                        <input type="number" class="form-control" name="paid_amount" id="paid_amount" value="0.00" step="0.01">
                                    </div>

                                    <div class="col-md-4 col-12 mb-3">
                                        <label for="due_amount" class="form-label">Due</label>
                                        <input type="number" class="form-control" name="due_amount" id="due_amount" value="0.00" step="0.01" readonly>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea class="form-control" name="note" id="note"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success mt-1">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <!-- Date & Time Picker JS -->
    <script src="{{asset('backEnd/assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('backEnd/assets/libs/select2/select2.min.js')}}"></script>

    <script type="text/javascript">
        flatpickr("#date", {
            dateFormat: "d-m-Y",
            defaultDate: new Date()
        });

        function totalQuantity() {
            var sub_total_qty = 0;
            $('.qty_section').each(function () {
                $(this).find('.quantity').each(function () {
                    sub_total_qty += parseFloat($(this).val());
                });
                $(this).next('.sub_total_qty').val(sub_total_qty);
            });

            $('.quantity').each(function (index) {
                $('.put_sub_toot').eq(index).val((parseFloat($(this).val()) * parseFloat($('.purchase_cost').eq(index).val())));
            });

            $('tr.product_row').each(function () {
                var sum = 0;
                $('.put_sub_toot', this).each(function () {
                    //console.log(parseFloat($(this).val()));
                    sum += parseFloat($(this).val());
                });
                $('.total', this).val(sum);
                //console.log(multiply);
            });

            var sub_total = 0;
            var total = 0;
            var due = 0;

            $('.total').each(function () {
                sub_total += parseFloat($(this).val());
            });

            $('#sub_total').val(sub_total);
            //console.log(multiply);

            var total = parseFloat($('#sub_total').val()) - parseFloat($('#discount').val());

            $('#balance').val(total);
            due = total - parseFloat($('#paid_amount').val());
            $('#due_amount').val(due);
        }

        $(document).on('click','#full_paid_btn',function () {
            $('#paid_amount').val($('#balance').val());
            $('#due_amount').val(0);
        });

        $(document).ready(function () {
            $('.select2').select2();

            $(document).on('change', '#select_product', function () {
                let arr = [];

                $('.products').each(function (index, element) {
                    var inputValue = $(element).val();
                    arr.push(inputValue);
                });

                if (arr.includes($(this).val())) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'This product already selected!',
                    })
                } else {
                    var CSRF_TOKEN = `{{csrf_token()}}`;
                    $.ajax({
                        url: '{{route('admin.ajax.get.products')}}',
                        type: 'GET',
                        data: {_token: CSRF_TOKEN, id: $(this).val()},
                        success: function (data) {
                            $('.no_data').css('display','none');
                            $('#product-row-put').append(data);
                            totalQuantity();
                        }
                    });
                }
            });

            $(document).on('click', '.remove_btn', function () {
                $(this).closest("tr").remove();
                totalQuantity();
            });


            $(document).on('keyup change', '.quantity', function () {
                //$('.quantity').val($(this).val());
                totalQuantity();
            });

            $(document).on('keyup change', '#quantity_0', function () {
                var qty_id = '.qty_'+$(this).data('id');
                $(qty_id).val($(this).val());
                totalQuantity();
            });

            $(document).on('keyup change', '.variant_qty', function () {
                $('.variant_qty').val($(this).val());
                totalQuantity();
            });

            $(document).on('keyup change', '.purchase_cost', function () {
                //$('.purchase_cost').val($(this).val());
                totalQuantity();
            });

            $(document).on('keyup change', '#purchase_cost_0', function () {
                var purc_cost_id = '.prch_cost_'+$(this).data('id');
                $(purc_cost_id).val($(this).val());
                totalQuantity();
            });

            $(document).on('keyup change', '#discount', function () {
                totalQuantity();
            });

            /*$(document).on('keyup', '.tax', function () {
                totalQuantity();
            });*/

            $(document).on('keyup change', '.sell_price', function () {
                //$('.sell_price').val($(this).val());
            });

            $(document).on('keyup change', '#sell_price_0', function () {
                //$('.sell_price').val($(this).val());
                var sell_prc_id = '.sell_prc_'+$(this).data('id');
                $(sell_prc_id).val($(this).val());
            });

            $(document).on('keyup change', '#paid_amount', function () {
                totalQuantity();
            });
        });


        $(document).on('change', '#supplier_id', function () {
            var supplier_id = $(this).val();
            $.ajax({
                url: "{{ route('supplier.ajax.get.balance') }}",
                type: "GET",
                data: {supplier_id: supplier_id},
                success: function (balance) {
                    $('#previous_due').val(balance);
                    totalQuantity();
                }
            });

        });
    </script>
@endsection

