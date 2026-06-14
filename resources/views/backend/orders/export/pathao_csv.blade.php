<table>
    <thead>
    <tr>
        <th>ItemType</th>
        <th>StoreName</th>
        <th>MerchantOrderId</th>
        <th>RecipientName(*)</th>
        <th>RecipientPhone(*)</th>
        <th>RecipientAddress(*)</th>
        <th>RecipientCity(*)</th>
        <th>RecipientZone(*)</th>
        <th>RecipientArea</th>
        <th>AmountToCollect(*)</th>
        <th>ItemQuantity</th>
        <th>ItemWeight</th>
        <th>ItemDesc</th>
        <th>SpecialInstruction</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>parcel</td>
            <td>{{$item->get_store->name ?? ''}}</td>
            <td>{{$item->invoice_no}}</td>
            <td>{{$item->customer_name ?? ''}}</td>
            <td>{{$item->customer_phone ?? ''}}</td>
            <td>{{$item->customer_address ?? ''}}</td>
            <td>{{$item->get_courier_city() ?? null}}</td>
            <td>{{$item->get_courier_zone() ?? null}}</td>
            <td></td>
            <td>{{ number_format($item->due,2,'.','') }}</td>
            <td>{{ $item->sale_items()->sum('quantity') }}</td>
            <td>0.5</td>
            <td>
                @foreach($item->sale_items as $key => $prod)
                    @if($key!=0)
                        <br>
                    @endif
                    {{ $prod->get_product->name }}
                @endforeach
            </td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
