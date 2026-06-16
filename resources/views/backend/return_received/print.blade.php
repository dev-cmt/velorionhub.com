<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Received</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            margin: 0;
        }

        .main-body {
            min-height: 380px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        @media print {
            body {
                zoom: 85%;
            }
        }
    </style>
</head>

<body>
    <div class="main-body">
        <h1 style="text-align:center">{{ $courier_name }} Return Received List</h1>
        <p style="text-align:center"><b>{{ date('d-m-Y h:i A', strtotime($min_date)) }}</b> -
            <b>{{ date('d-m-Y h:i A', strtotime($max_date)) }}</b></p>

        @foreach ($courier_data as $id => $count)
            <p><b>Total {{ $couriers[$id] }} Parcels:</b> {{ $count }}</p>
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
                @foreach ($sale_items as $productId => $rows)
                    @php
                        $product = $rows->first()->get_product->name ?? 'N/A';
                        $totalQty = $rows->sum('quantity');
                        $totalPrice = $rows->sum('total_price');
                        $grandQty += $totalQty;
                        $grandPrice += $totalPrice;

                        $courierCounts = array_fill_keys(array_keys($couriers), 0);
                        foreach ($rows as $r) {
                            $courierCounts[$r->courier_id] = $r->quantity;
                            $totalPerCourier[$r->courier_id] += $r->quantity;
                        }
                    @endphp
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td style="text-align:left;">{{ $product }}</td>
                        <td>{{ $totalQty }}</td>
                        <td>TK {{ number_format($totalPrice) }}</td>
                        @foreach ($couriers as $id => $name)
                            <td>{{ $courierCounts[$id] ?: '---' }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"></td>
                    <td><b>{{ $grandQty }}</b></td>
                    <td><b>TK {{ number_format($grandPrice) }}</b></td>
                    @foreach ($couriers as $id => $name)
                        <td><b>{{ $totalPerCourier[$id] }}</b></td>
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
