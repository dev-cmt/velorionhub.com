<x-backend-layout title="Stores Management">

    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Return Receive List
            </div>
            <div class="prism-toggle d-flex">
                @if(session()->has('first_receive_time'))
                    <div style="display: flex; flex-direction: column;margin-right: 5px; justify-content: center;">
                        <small style="display: block; line-height: 13px;">{{date('d-m-Y',strtotime(session('first_receive_time')))}}</small>
                        <small style="display: block; line-height: 13px;">{{date('h:i:s A',strtotime(session('first_receive_time')))}}</small>
                    </div>
                @endif
                <form action="{{route('return.receive.clear')}}" method="post">
                    @csrf
                    <input type="submit" name="clear" class="btn btn-sm btn-danger me-1" value="Clear">
                </form>

                <form action="" method="get" id="return_receive_store_form">
                    <div class="d-flex">
                        {{--<select class="form-select form-select-sm me-1 {{request()->query('store_id')!=null?"bg-info-subtle":""}}" name="store_id" id="store_id" required>
                            <option value="">--Select Store--</option>
                            @foreach($stores as $key => $store)
                                <option value="{{$key}}" {{request()->query('store_id')==$key?"selected":""}}>{{$store}}</option>
                            @endforeach
                        </select>--}}
                        <input type="text" name="invoice" id="invoice" class="form-control form-control-sm" placeholder="Scan Barcode"
                               value="{{request()->query('invoice')}}" onclick="this.select();" required>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Store</th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th class="text-wrap">Customer Info</th>
                        <th class="text-wrap">Product(s)</th>
                        <th>Amount</th>
                        <th>Profit</th>
                        <th>Status</th>
                        <th>Return</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @if(count($data)>0)
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td><span class="badge bg-dark-gradient">{{ $item->get_store->name }}</span></td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>
                                    {{ date('d-m-Y',strtotime($item->date)) }}<br>
                                    <small>{{ date('h:s:i A',strtotime($item->created_at)) }}</small>
                                </td>
                                <td class="text-wrap">
                                    <b>Name: </b>{{$item->customer_name ?? ''}} <br>
                                    <b>Phone: </b>{{$item->customer_phone ?? ''}} <br>
                                    <b>Address: </b>{{$item->customer_address ?? ''}}
                                </td>

                                <td class="text-wrap">
                                    @if(count($item->sale_items)>0)
                                        @foreach($item->sale_items as $key => $product)
                                            @if($key != 0)
                                                <br>
                                            @endif
                                            @if($product->is_exchange_product == 1 )
                                                <small class="badge bg-danger rounded-pill" style="padding: 0.15rem 0.25rem;">R</small>
                                            @endif
                                            <span class="text-danger">{{$product->quantity}}</span> x {{$product->get_product->name}}
                                            @if($product->attributes)
                                                <br>
                                                <span class="text-primary">{{$product->attributes}}</span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-danger fw-bold">{{$item->error_msg}}</span>
                                    @endif
                                </td>
                                <td>TK {{ number_format($item->sub_total, 2)}}</td>
                                <td>
                                    @if($item->status == 'delivered')
                                        @if($item->get_profit()>0)
                                            <span class="text-success">TK{{ number_format($item->get_profit(), 2)}}</span>
                                        @else
                                            <span class="text-danger">TK{{ number_format($item->get_profit(), 2)}}</span>
                                        @endif
                                    @elseif($item->status == 'returned')
                                        TK0
                                    @else
                                        <span class="text-muted text-light-emphasis">Est. Prof. TK{{ number_format($item->get_profit(), 2,'.','')}}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($item->status == 1)
                                        <span class="badge bg-info">Packaging Done</span>
                                    @elseif($item->status == 2)
                                        <span class="badge bg-primary">On Delivery</span>
                                    @elseif($item->status == 3)
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($item->status == 4)
                                        <span class="badge bg-danger">Return</span>
                                    @elseif($item->status == 5)
                                        <span class="badge bg-indigo">Partial Delivered</span>
                                    @elseif($item->status == 6)
                                        <span class="badge bg-purple">Exchange</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_return_received == 1)
                                        <span class="badge bg-success">Received</span><br>
                                        <small>{{date('d-m-Y',strtotime($item->received_time))}}</small><br>
                                        <small>{{date('h:i:s A',strtotime($item->received_time))}}</small>
                                    @else
                                        ----
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-danger text-center">No Data Available!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            {{ $data->links('backend.pagination.paginate') }}
        </div>
    </div>

@push('js')
    <script>
        $(document).on('change', '#store_id', function () {
            $('#return_receive_store_form').submit();
        });
        $(document).ready(function () {
            if ($('#store_id').find(":selected").val()) {
                $('#invoice').focus().select();
            } else {
                $('#invoice').val('');
            }
        });

        $('#invoice').focus();
    </script>
@endpush
</x-backend-layout>
