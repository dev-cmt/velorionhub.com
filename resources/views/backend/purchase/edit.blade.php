@extends('backEnd.layouts.master')
@section('title')
    Purchase Edit
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('backEnd/assets/libs/flatpickr/flatpickr.min.css')}}">
    <link href="{{asset('backEnd/assets/libs/select2/select2.min.css')}}" rel="stylesheet"/>
    <style>
        table td {
            vertical-align: top !important;
        }

        .no_data {
            display: table-row;
        }
    </style>
@endsection

@php
    $purchase = $data['purchase'] ?? [];
    $suppliers = $data['suppliers'] ?? [];
    $products = $data['products'] ?? [];
@endphp

@section('body')
<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">
            Purchase Edit
        </div>
        <div class="prism-toggle">
            <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-danger"><i class="ti ti ti-chevron-left"></i>Back</a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase.update') }}" method="POST">
            @csrf
            <input type="hidden" class="form-control" value="{{ $purchase->id }}" name="purchase_id" id="purchase_id">

            <!-- Supplier, Date, Status -->
            <div class="mb-5">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label class="form-label" for="supplier_id">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="supplier_id" id="supplier_id" required>
                                    <option value="">--Select Supplier--</option>
                                    @foreach ($suppliers as $id => $name)
                                        <option value="{{ $id }}" {{ $id == $purchase->supplier_id ? 'selected' : '' }} >{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                            <input type="text" name="date" class="form-control" value="{{ date('d-m-Y',strtotime($purchase->date)) }}" required id="date">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="status" id="status" required>
                                <option value="">--Select Status--</option>
                                <option value="0" {{ $purchase->status == 0 ? 'selected' : '' }}>Ordered</option>
                                <option value="1" {{ $purchase->status == 1 ? 'selected' : '' }}>Received</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="memo_number">Memo Number</label>
                            <input type="text" class="form-control" name="memo_number" id="memo_number" value="{{ $purchase->memo_number }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="1" placeholder="Remarks">{{ $purchase->remarks }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product selection -->
            <div>
                <div class="row d-flex justify-content-center mb-3">
                    <div class="col-md-10">
                        <div class="input-group input-group-sm mb-3">
                            <select class="form-select select2" name="select_product" id="select_product">
                                <option value="" selected="">--Select Product--</option>
                                @foreach ($products as $key=>$product)
                                    <option value="{{ $key }}">{{ $product }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table text-nowrap table-bordered">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th width="44%">Product Name</th>
                            <th width="10%">Purchase Quantity</th>
                            <th width="15%">Purchase Cost</th>
                            <th width="15%">Sell Price</th>
                            <th width="15%">Total</th>
                            <th width="1%"></th>
                        </tr>
                        </thead>

                        <tbody id="product-row-put">
                            <!-- Existing purchase items -->
                            @if(count($purchase->purchase_items) > 0)
                                @foreach ($purchase->purchase_items->groupBy('product_id') as $key => $itm)
                                    @if(optional($itm[0]->get_product)->has_variant == 1)
                                        <tr class="item-row-template product_row">
                                            <td colspan="4">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th width="59%">Name</th>
                                                        <th width="10%">Purchase Quantity</th>
                                                        <th width="15%">Purchase Cost</th>
                                                        <th width="15%">Sell Price</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <input type="hidden" name="product_id[]" value="{{ $itm[0]->product_id }}">
                                                    @foreach ($itm as $key2 => $item)
                                                        <tr>
                                                            <td>
                                                                <i style="cursor:pointer;" class="remove_btn bi bi-x-circle btn-lg"></i>
                                                            </td>
                                                            <td width="59%" class="p-1">
                                                                <input type="hidden" name="sku[{{$item->product_id}}][]" value="{{ $item->sku }}">
                                                                <label class="col-form-label p-0">
                                                                    <small class="text-info">#{{ $item->sku }}</small><br>
                                                                    {{ $item->get_product->name }}
                                                                </label>
                                                            </td>
                                                            <td class="qty_section p-1" width="10%">
                                                                <input type="number" class="form-control form-control-sm quantity qty_{{$item->product_id}}"
                                                                       name="quantity[{{$item->product_id}}][]"
                                                                       data-id="{{$item->product_id}}"
                                                                       id="quantity_{{$key2}}" value="{{$item->purchase_qty}}" required>
                                                            </td>
                                                            <input type="hidden" class="sub_total_qty" name="sub_total_qty[{{$item->product_id}}]">
                                                            <td class="purchase_cost_section p-1" width="15%">
                                                                <input type="number" class="form-control form-control-sm purchase_cost prch_cost_{{$item->product_id}}"
                                                                       data-id="{{$item->product_id}}"
                                                                       name="purchase_cost[{{$item->product_id}}][]"
                                                                       id="purchase_cost_{{$key2}}"
                                                                       value="{{number_format($item->purchase_cost,2,'.','')}}" min="1" step="0.01" required>
                                                                <input type="hidden" class="put_sub_toot" name="put_sub_toot[{{$item->product_id}}][]">
                                                            </td>
                                                            <input type="hidden" class="sub_total_purchase_cost">
                                                            <td class="sell_price_section p-1" width="15%">
                                                                <input type="number" class="form-control form-control-sm sell_price sell_prc_{{$item->product_id}}"
                                                                       data-id="{{$item->product_id}}"
                                                                       name="sell_price[{{$item->product_id}}][]"
                                                                       id="sell_price_{{$key2}}"
                                                                       value="{{number_format($item->sell_price,2,'.','')}}" min="1" step="0.01" required>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                            <input type="hidden" class="sub_total_sell_price">
                                            <td>
                                                <input type="number" class="form-control form-control-sm total"
                                                       name="total[{{$item->product_id}}]"
                                                       value="{{number_format($itm->sum('total'), 2,'.','')}}" step="0.01" required>
                                            </td>
                                            <td>
                                                <i style="cursor:pointer;" class="remove_btn bi bi-x-circle btn-lg"></i>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($itm as $key2 => $item)
                                            <tr class="item-row-template product_row">
                                                <td>
                                                    <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                                    <input type="hidden" name="sku[{{ $item->product_id }}][]" value="{{ $item->sku }}">
                                                    <small class="text-info">#{{ $item->sku }}</small><br>
                                                    {{ $item->get_product->name }}
                                                </td>
                                                <td class="qty_section">
                                                    <input type="number" class="form-control form-control-sm quantity"
                                                           name="quantity[{{$item->product_id}}][]"
                                                           value="{{$item->purchase_qty}}" required>
                                                </td>
                                                <input type="hidden" class="sub_total_qty" name="sub_total_qty[{{$item->product_id}}]">
                                                <td class="purchase_cost_section">
                                                    <input type="number" class="form-control form-control-sm purchase_cost"
                                                           name="purchase_cost[{{$item->product_id}}][]"
                                                           value="{{number_format($item->purchase_cost,2,'.','')}}" min="1" step="0.01" required>
                                                    <input type="hidden" class="put_sub_toot" name="put_sub_toot[{{$item->product_id}}][]">
                                                </td>
                                                <input type="hidden" class="sub_total_purchase_cost">
                                                <td class="sell_price_section">
                                                    <input type="number" class="form-control form-control-sm sell_price"
                                                           name="sell_price[{{$item->product_id}}][]"
                                                           value="{{number_format($item->sell_price,2,'.','')}}" min="1" step="0.01" required>
                                                </td>
                                                <input type="hidden" class="sub_total_sell_price">
                                                <td>
                                                    <input type="number" class="form-control form-control-sm total"
                                                           name="total[{{$item->product_id}}]"
                                                           value="{{number_format($item->total,2,'.','')}}" step="0.01" required>
                                                </td>
                                                <td>
                                                    <i style="cursor:pointer;" class="remove_btn bi bi-x-circle btn-lg"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </tbody>

                        <!-- TFOOT -->
                        <tfoot>
                        <tr>
                            <th colspan="4" class="text-end p-1"><b>Sub Total:</b></th>
                            <td colspan="2" class="p-1">
                                <input type="number" readonly class="form-control text-end" name="sub_total" id="sub_total"
                                       value="{{number_format($purchase->sub_total,2,'.','')}}" step="0.01">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end p-1"><b>Discount:</b></th>
                            <td colspan="2" class="p-1">
                                <input type="number" class="form-control text-end" name="discount" id="discount"
                                       value="{{number_format($purchase->discount,2,'.','')}}" step="0.01">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end p-1"><b>Total :</b></th>
                            <td colspan="2" class="p-1">
                                <input type="number" readonly class="form-control text-end" name="balance" id="balance" value="{{number_format($purchase->total,2,'.','')}}" step="0.01">
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Payment Section -->
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
                                            <option value="{{$id}}" {{ $id == $purchase->payment_mode ? "selected":""}}> {{ $name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 col-12 mb-3">
                                    <label for="paid_amount" class="form-label">Paid Amount <span class="text-danger fw-bold">*</span></label>
                                    <input type="number" class="form-control" name="paid_amount" id="paid_amount" value="{{number_format($purchase->paid_amount, 2,'.','')}}" step="0.01">
                                </div>

                                <div class="col-md-4 col-12 mb-3">
                                    <label for="due_amount" class="form-label">Due</label>
                                    <input type="number" class="form-control" name="due_amount" id="due_amount" value="{{number_format($purchase->due_amount,2,'.','')}}" step="0.01" readonly>
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
                    <button type="submit" class="btn btn-success mt-1">Update</button>
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
flatpickr("#date", { dateFormat: "d-m-Y", defaultDate: new Date() });

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
            sum += parseFloat($(this).val());
        });
        $('.total', this).val(sum);
    });

    var sub_total = 0;
    $('.total').each(function () { sub_total += parseFloat($(this).val()); });
    $('#sub_total').val(sub_total);

    var total = parseFloat($('#sub_total').val()) - parseFloat($('#discount').val());
    $('#balance').val(total);

    var due = total - parseFloat($('#paid_amount').val());
    $('#due_amount').val(due);
}

$(document).on('click','#full_paid_btn',function () {
    $('#paid_amount').val($('#balance').val());
    $('#due_amount').val(0);
});

$(document).ready(function () {
    $('.select2').select2();

    // FIXED: Already selected product check
    $(document).on('change', '#select_product', function () {
        let selectedProductId = $(this).val();
        if (!selectedProductId) return;

        let selectedProducts = [];
        $('input[name="product_id[]"]').each(function () {
            selectedProducts.push($(this).val());
        });

        if (selectedProducts.includes(selectedProductId)) {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'This product already selected!' });
            $(this).val(null).trigger('change');
            return;
        }

        var CSRF_TOKEN = `{{csrf_token()}}`;
        $.ajax({
            url: '{{route('admin.ajax.get.products')}}',
            type: 'GET',
            data: {_token: CSRF_TOKEN, id: selectedProductId},
            success: function (data) {
                $('.no_data').hide();
                $('#product-row-put').append(data);
                totalQuantity();
                $('#select_product').val(null).trigger('change');
            }
        });
    });

    $(document).on('click', '.remove_btn', function () {
        $(this).closest("tr").remove();
        totalQuantity();
    });

    $(document).on('keyup change', '.quantity, .purchase_cost, .sell_price, #discount, #paid_amount', function () {
        totalQuantity();
    });
});
</script>
@endsection
