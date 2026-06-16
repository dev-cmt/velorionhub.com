<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Received</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; margin: 0; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 5px; text-align: center; }
        th { background: #f2f2f2; }
        @media print { body { zoom: 85%; } }
    </style>
</head>
<body>
    <div>
        <h1 style="text-align:center">{{ $courier_name }} Return Received List</h1>
        <p style="text-align:center">
            <b>{{ $min_date ? date('d-m-Y h:i A', strtotime($min_date)) : 'N/A' }}</b> -
            <b>{{ $max_date ? date('d-m-Y h:i A', strtotime($max_date)) : 'N/A' }}</b>
        </p>

        @foreach ($courier_data as $id => $count)
            <p><b>Total {{ $couriers[$id] ?? 'Unknown' }} Parcels:</b> {{ $count }}</p>
        @endforeach

        @php
            $sl = 1;
            $grandQty = 0;
            $grandPrice = 0;
            $totalPerCourier = array_fill_keys(array_keys($couriers), 0);
        @endphp

        <table>
            <thead>
                <tr>
                    <th>SL</th>
                    <th style="text-align:left;">Product</th>
                    <th>Qty</th>
                    <th>Total Price</th>
                    @foreach ($couriers as $name)
                        <th>{{ $name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($sale_items_combined as $item)
                    @php
                        $rows = $item['rows'];
                        $productName = $item['product_name'];
                        $totalQty = collect($rows)->sum('quantity');
                        $totalPrice = collect($rows)->sum(fn($r) => $r['quantity'] * $r['unit_price']);
                        $grandQty += $totalQty;
                        $grandPrice += $totalPrice;

                        $courierCounts = array_fill_keys(array_keys($couriers), 0);
                        foreach ($rows as $r) {
                            $courierCounts[$r['courier_id']] += $r['quantity'];
                            $totalPerCourier[$r['courier_id']] += $r['quantity'];
                        }
                    @endphp
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td style="text-align:left;">{{ $productName }}</td>
                        <td>{{ $totalQty }}</td>
                        <td>TK {{ number_format($totalPrice, 2) }}</td>
                        @foreach ($couriers as $id => $name)
                            <td>{{ $courierCounts[$id] ?: '---' }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr style="font-weight:bold; background:#ddd;">
                    <td colspan="2">Grand Total</td>
                    <td>{{ $grandQty }}</td>
                    <td>TK {{ number_format($grandPrice, 2) }}</td>
                    @foreach ($couriers as $id => $name)
                        <td>{{ $totalPerCourier[$id] }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.close();
        }
    </script>
</body>
</html>
