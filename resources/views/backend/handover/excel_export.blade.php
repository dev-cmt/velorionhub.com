<table>
    <thead>
    <tr>
        <th style="font-weight: bold">Date</th>
        <th style="font-weight: bold">Courier</th>
        <th style="font-weight: bold">Shop</th>
        <th style="font-weight: bold">Invoice</th>
        <th style="font-weight: bold">Name</th>
        <th style="font-weight: bold">Number</th>
        <th style="font-weight: bold">Address</th>
        <th style="font-weight: bold">Product Name</th>
        <th style="font-weight: bold">Product Price</th>
        <th style="font-weight: bold">Quantity</th>
        <th style="font-weight: bold">Delivery Charge</th>
        <th style="font-weight: bold">Total Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sales as $key => $item)
        <tr style="vertical-align: top">
            <td>{{date('d-m-y',strtotime($item->sales_created_at))}}</td>
            <td>{{$item->get_courier?$item->get_courier->name:""}}</td>
            <td>{{$item->get_store?$item->get_store->name:""}}</td>
            <td>{{$item->invoice_no}}</td>
            <td>{{$item->customer_name ?? ''}}</td>
            <td>{{$item->customer_phone ?? ''}}</td>
            <td>{{$item->customer_address ?? ''}}</td>
            <td>
                @if(count($item->sale_items)>0)
                    @foreach($item->sale_items as $k => $product)
                        @if($k != 0)
                            <br>
                        @endif
                        {{$product->get_product?$product->get_product->name:""}}
                    @endforeach
                @endif
            </td>
            <td style="text-align: left">
                @if(count($item->sale_items)>0)
                    @foreach($item->sale_items as $k => $product)
                        @if($k != 0)
                            <br>
                        @endif
                        {{number_format($product->unit_price,0,'.','')}}
                    @endforeach
                @endif
            </td>
            <td style="text-align: left">
                @if(count($item->sale_items)>0)
                    @foreach($item->sale_items as $k => $product)
                        @if($k != 0)
                            <br>
                        @endif
                        {{$product->quantity}}
                    @endforeach
                @endif
            </td>

            <td style="text-align: left">{{number_format($item->shipping_cost,0,'.','')}}</td>
            <td style="text-align: left">{{number_format($item->total,0,'.','')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
