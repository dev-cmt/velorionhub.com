<div class="card-body">
    <div class="table-responsive">
        <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
            <thead>
            <tr>
                <th>SL</th>
                {{-- <th>Store Name</th> --}}
                <th>Product</th>
                <th>Quantity</th>
                <th>Purchase Cost</th>
                <th>Sell Price</th>
                {{--<th>Discount</th>
                <th>Tax</th>
                <th>Profit Margin</th>--}}
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @php($i=1)
            @if(count($data->purchase_items)>0)
                @foreach($data->purchase_items as $item)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{ optional($item->get_product)->name ?? 'Deleted Product' }} <span class="text-info">#{{$item->sku}}</span></td>
                        <td>{{ $item->quantity }}</td>
                        <td> {{ number_format($item->purchase_cost,2) }}</td>
                        <td> {{ number_format($item->sell_price,2) }}</td>
                        {{--<td> {{ number_format($item->discount,2,'.','') }}</td>--}}
                        {{--<td> {{ number_format($item->tax,2,'.','') }}</td>
                        <td> {{ number_format($item->profit_margin,2,'.','') }}</td>--}}
                        <td> {{ number_format($item->total,2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-danger text-center">No Data Available!</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
