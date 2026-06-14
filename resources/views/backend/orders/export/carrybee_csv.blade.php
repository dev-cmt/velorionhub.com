<table>
    <thead>
    <tr>
        <th>Store *</th>
        <th>Product Type *</th>
        <th>Merchant Order ID</th>
        <th>Recipient Name *</th>
        <th>Recipient Phone *</th>
        <th>Recipient Address *</th>
        <th>Recipient City *</th>
        <th>Recipient Zone *</th>
        <th>Recipient Area</th>
        <th>Amount To Collect *</th>
        <th>Weight *</th>
        <th>Quantity *</th>
        <th>Close Box</th>
        <th>Item description</th>
        <th>Special instruction</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td></td>
            <td>Parcel</td>
            <td>{{ $item->invoice_id ?? null }}</td>
            <td>{{ $item->customer_name ?? null }}</td>
            <td>{{ $item->customer_phone ?? null }}</td>
            <td>{{ $item->customer_address ?? null }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $item->total ?? null}}</td>
            <td>0.5</td>
            <td>{{ $item->get_products->sum('qty') ?? 1 }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
