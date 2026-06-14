<table>
    <thead>
    <tr>
        <th>Order Date</th>
        <th>Order ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>Number</th>
        <th>Total amount</th>
        <th>Item Name</th>
        <th>SKU</th>
        <th>Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ date('d M, Y',strtotime($item->order_date)) }}</td>
            <td>{{ $item->invoice_id }}</td>
            <td>{{ $item->customer_name }}</td>
            <td>{{ $item->customer_address }}</td>
            <td>{{ $item->customer_phone }}</td>
            <td>{{ $item->total }}</td>
            <td>
                @foreach ($item->get_products as $product)
                    {{ $product->qty}} x {{ $product->get_product->name }} <br>
                    @if (is_array($product->attributes))
                        <?php
                        $variantOptions = collect($product->attributes ?? [])
                            ->map(function ($name) {
                                return collect(explode('_', $name))->map(fn($word) => ucfirst($word))->implode(' ');
                            })
                            ->implode(' , ');
                        ?>
                        <span class="text-primary">
                            {{ $variantOptions }}
                        </span>
                        <br>
                    @endif
                @endforeach
            </td>
            <td>
                @if(count($item->get_products)>0)
                    @foreach($item->get_products as $product)
                        @if($product->get_product)
                            {{$product->get_product->sku}}
                        @endif
                        <br>
                    @endforeach
                @endif
            </td>
            <td>
                @if($item->order_note)
                    Customer:- {{ $item->order_note }}
                @endif
                @if($item->staff_note)
                    <br>
                    Staff:- {{ $item->staff_note }}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
