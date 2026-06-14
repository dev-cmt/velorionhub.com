<x-backend-layout title="Orders Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Orders Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </div>

     @php
        $orderStatus = [
            0 => ['label' => 'Pending',             'color' => 'warning'],
            1 => ['label' => 'Confirmed',           'color' => 'info'],
            2 => ['label' => 'Hold',                'color' => 'secondary'],
            3 => ['label' => 'Cancelled',           'color' => 'danger'],
            4 => ['label' => 'Stockout',            'color' => 'danger'],
            5 => ['label' => 'Packaged',            'color' => 'secondary'],
            6 => ['label' => 'Courier Entry',       'color' => 'primary'],
            7 => ['label' => 'On Delivery',         'color' => 'info'],
            8 => ['label' => 'Delivered',           'color' => 'success'],
            9 => ['label' => 'Partial Delivered',   'color' => 'secondary'],
            10 => ['label' => 'Exchange',           'color' => 'warning'],
            11 => ['label' => 'Return',             'color' => 'danger'],
            12 => ['label' => 'Return Received',    'color' => 'success'],
        ];
    @endphp

    <!-- Status Filter and Search -->
    <div class="row">
        <div class="col-md-2 col-sm-6">
            <div class="card custom-card mb-2">
                <div class="card-body p-3">
                    <a class="d-flex flex-wrap align-items-top justify-content-between" href="{{ route('orders.index') }}">
                        <div class="flex-fill">
                            <p class="mb-0 text-muted">All Orders</p>
                            <div class="d-flex align-items-center">
                                <span class="fs-5 fw-semibold">1,234</span>
                            </div>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                <i class="bi bi-people-fill fs-16"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @foreach ($orderStatus as $status => $data)
        <div class="col-md-2 col-sm-6">
            <div class="card custom-card mb-2">
                <div class="card-body p-3">
                    <a class="d-flex flex-wrap align-items-top justify-content-between" href="{{ route('orders.index', ['status' => $status]) }}">
                        <div class="flex-fill">
                            <p class="mb-0 text-muted">{{ $data['label'] }}</p>
                            <div class="d-flex align-items-center">
                                <span class="fs-5 fw-semibold">773</span>
                            </div>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-info-transparent text-info fs-18">
                                <i class="bi bi-file-earmark-text-fill fs-16"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card-body d-flex align-items-center flex-wrap">
        <div class="flex-fill">
            <div class="d-flex align-items-center gap-1">
                <a href="{{ route('orders.create') }}" class="btn btn-success btn-sm">
                    <i class="ri-add-line me-1 fw-semibold align-middle"></i>Create New Order
                </a>
                <button type="button" class="btn btn-primary btn-sm" id="courier_entry_btn">
                    <i class="ri-truck-line me-1 fw-semibold align-middle"></i>Sent to curier
                </button>
                <button type="button" class="btn btn-secondary btn-sm" id="label_print_btn">
                    <i class="ri-printer-line me-1 fw-semibold align-middle"></i>Label Print
                </button>
                <form action="/bulk-status" method="post" id="bulk_status_form">
                    <select class="form-select form-select-sm" name="bulk_status" id="bulk_status">
                        <option value="">--Select Status--</option>
                        <option value="0">Pending</option>
                        <option value="1">Confirmed</option>
                        <option value="2">hold</option>

                        <option value="4">Print</option>
                        <option value="5">Stockout</option>
                        <option value="6">Packaged</option>
                        <option value="7">Courier Entry</option>
                        <option value="8">On Delivery</option>
                        <option value="9">Delivered</option>
                        <option value="10">Partial Delivered</option>
                        <option value="11">Return</option>
                        <option value="12">Return Received</option>
                        <option value="13">Exchange</option>
                    </select>
                </form>

                <form action="/bulk-assign" method="post" id="bulk_assign_form">
                    <select class="form-select form-select-sm" name="bulk_assign" id="bulk_assign">
                        <option value="">--User Assign--</option>
                            <option value="2">saba</option>
                            <option value="3">barsha</option>
                            <option value="4">employee</option>
                            <option value="8">Yasmin</option>
                            <option value="9">guest</option>
                            <option value="10">Joy</option>
                            <option value="15">Tarif</option>
                            <option value="16">NEWMART</option>
                            <option value="18">Ismat</option>
                            <option value="24">Shuvo</option>
                            <option value="25">AI Agent</option>
                            <option value="26">proma</option>
                            <option value="27">Fahmida</option>
                            <option value="28">Rahi</option>
                            <option value="31">shethia</option>
                    </select>
                </form>

                <form action="/courier-export" method="post" id="courier_export_form">
                    <select class="form-select form-select-sm" name="courier_export" id="courier_export">
                        <option value="">--Courier Export--</option>
                        <!--Dynamic options will be populated here-->
                    </select>
                </form>

            </div>
        </div>
        {{-- <select class="form-select form-select-sm choices" name="product_filter" id="product_filter" multiple>
            <option value="">All Products</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select> --}}
        <div class="d-flex align-items-center m-1" role="search">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-light ms-2" type="submit">Search</button>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                {{-- <div class="card-header justify-content-between">
                    <div class="card-title">Orders List</div>
                    <div class="d-flex align-items-center m-1" role="search">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-light ms-2" type="submit">Search</button>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Courier</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $key => $order)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}">
                                            {{ $orders->firstItem() + $key }}
                                        </td>
                                        <td>
                                            <sapn class="badge bg-success-transparent">{{ $order->source }}</sapn><br>
                                            <a href="{{ route('orders.edit', $order->id) }}" class="fw-bold text-primary">{{ $order->invoice_no }}</a><br>
                                            <small class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <small class="text-muted"><i class="ri-phone-line"></i> {{ $order->customer_phone }}</small><br>
                                            <small class="text-muted"><i class="ri-map-pin-line"></i> {{ $order->customer_address }}</small>
                                        </td>
                                        <td style="min-width:220px; white-space:normal;">
                                            @forelse($order->items as $item)
                                                @php
                                                    $productName = $item->product->name ?? ('SKU: ' . $item->sku);
                                                    $attrParts = [];
                                                    if (!empty($item->attributes)) {
                                                        $attributes = $item->attributes;
                                                        if (is_string($attributes)) {
                                                            $attributes = json_decode($attributes, true);
                                                        }
                                                        if (is_array($attributes)) {
                                                            foreach ($attributes as $k => $v) {
                                                                if (!is_numeric($k) && !in_array($k, ['image','url','variant_label','has_variant','product_url','variant_attributes','product_id','variant_id','variant_key'])) {
                                                                    if (is_scalar($v)) {
                                                                        $attrParts[] = ucfirst($k) . ': ' . $v;
                                                                    }
                                                                } elseif ($k === 'variant_label') {
                                                                    if (is_scalar($v)) {
                                                                        $attrParts[] = $v;
                                                                    }
                                                                }
                                                            }
                                                            // fallback: show variant_label or variant_attributes if empty
                                                            if (empty($attrParts)) {
                                                                if (!empty($attributes['variant_label'])) {
                                                                    $attrParts[] = $attributes['variant_label'];
                                                                } elseif (!empty($attributes['variant_attributes']) && is_array($attributes['variant_attributes'])) {
                                                                    foreach ($attributes['variant_attributes'] as $vk => $vv) {
                                                                        if (is_scalar($vv)) {
                                                                            $attrParts[] = ucfirst($vk) . ': ' . $vv;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <div class="mb-1">
                                                    <a href="{{ route('product.show', $item->product->slug) }}">
                                                        <span class="fw-semibold one-line-text">{{ $productName }}</span>
                                                    </a>
                                                    <span class="badge bg-secondary-transparent ms-1">×{{ $item->quantity }}</span>
                                                    @if(!empty($attrParts))
                                                        <br><small class="text-muted">{{ implode(' | ', $attrParts) }}</small>
                                                    @endif
                                                    <small class="text-muted d-block">TK {{ number_format($item->sale_price, 2) }} each</small>
                                                </div>
                                            @empty
                                                <span class="text-muted">—</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <span>{{ $order->courier->name ?? '--' }}</span><br>
                                            @if($order->tracking_url)
                                                <small  class="badge bg-success-transparent"><i class="ri-eye-line"></i>
                                                    <a href="{{ $order->tracking_url ?? '#' }}" target="_blank">{{ $order->tracking_no ?? '' }}</a>
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent">Total: TK {{ number_format($order->total, 2) }}</span><br>
                                            <span class="badge bg-success-transparent">Paid: TK {{ number_format($order->paid, 2) }}</span><br>
                                            <span class="badge bg-danger-transparent">Due: TK {{ number_format($order->due, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $orderStatus[$order->status]['color'] }}-transparent">
                                                {{ $orderStatus[$order->status]['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning-light btn-icon" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this order and all its items?')" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend-layout>
