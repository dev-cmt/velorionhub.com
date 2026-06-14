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
            0 => ['label' => 'Pending',           'color' => 'warning', 'icon' => 'bi-clock-history'],
            1 => ['label' => 'Confirmed',         'color' => 'info', 'icon' => 'bi-check2-circle'],
            2 => ['label' => 'Hold',              'color' => 'secondary', 'icon' => 'bi-pause-circle'],
            3 => ['label' => 'Cancelled',         'color' => 'danger', 'icon' => 'bi-x-circle'],
            4 => ['label' => 'Stock Out',         'color' => 'danger', 'icon' => 'bi-box-seam'],
            5 => ['label' => 'Packaged',          'color' => 'secondary', 'icon' => 'bi-gift'],
            6 => ['label' => 'Courier Entry',     'color' => 'primary', 'icon' => 'bi-truck'],
            7 => ['label' => 'On Delivery',       'color' => 'info', 'icon' => 'bi-truck'],
            8 => ['label' => 'Delivered',         'color' => 'success', 'icon' => 'bi-check-lg'],
            9 => ['label' => 'Partial Delivered', 'color' => 'secondary', 'icon' => 'bi-envelope-paper'],
            10 => ['label' => 'Exchange',         'color' => 'warning', 'icon' => 'bi-arrow-left-right'],
            11 => ['label' => 'Return',           'color' => 'danger', 'icon' => 'bi-arrow-return-left'],
            12 => ['label' => 'Return Received',  'color' => 'success', 'icon' => 'bi-check2-all'],
        ];
    @endphp

    <!-- Status Filter and Search -->
    <div class="row g-2">
        <div class="col-md-2 col-sm-6">
            <div class="card custom-card mb-2">
                <div class="card-body p-3">
                    <a class="d-flex flex-wrap align-items-top justify-content-between" href="{{ route('orders.index') }}">
                        <div class="flex-fill">
                            <p class="mb-0 text-muted">All Orders</p>
                            <div class="d-flex align-items-center">
                                <span class="fs-5 fw-semibold">{{ $totalOrders }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fs-18">
                                <i class="bi bi-cart-check fs-16"></i>
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
                                <span class="fs-5 fw-semibold">{{ $orderCounts[$status] ?? 0 }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="avatar avatar-md avatar-rounded bg-{{ $data['color'] }}-transparent text-{{ $data['color'] }} fs-18">
                                <i class="bi {{ $data['icon'] }} fs-16"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="card custom-card mt-4">
        <div class="card-body d-flex align-items-center flex-wrap gap-2">
            <div class="flex-fill">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('orders.create') }}" class="btn btn-success btn-sm">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Create New Order
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" id="courier_entry_btn">
                        <i class="ri-truck-line me-1 fw-semibold align-middle"></i>Sent to courier
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="label_print_btn">
                        <i class="ri-printer-line me-1 fw-semibold align-middle"></i>Label Print
                    </button>

                    <form action="{{ route('orders.bulk-status') }}" method="post" id="bulk_status_form" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="order_ids" id="bulk_status_ids">
                        <select class="form-select form-select-sm" name="bulk_status" id="bulk_status">
                            <option value="">--Select Status--</option>
                            @foreach($orderStatus as $status => $data)
                                <option value="{{ $status }}">{{ $data['label'] }}</option>
                            @endforeach
                        </select>
                    </form>

                    <form action="{{ route('orders.bulk-assign') }}" method="post" id="bulk_assign_form" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="order_ids" class="bulk_assign_ids">
                        <select class="form-select form-select-sm" name="bulk_assign" id="bulk_assign">
                            <option value="">--Bulk Assign--</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </form>

                    <form action="{{ route('orders.courier-export') }}" method="post" id="courier_export_form" class="d-flex align-items-center gap-2">
                        @csrf
                        <input type="hidden" name="order_ids" class="courier_export_ids">
                        <select class="form-select form-select-sm" name="courier_export" id="courier_export">
                            <option value="">--Courier Export--</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <form method="GET" action="{{ route('orders.index') }}" class="d-flex align-items-center gap-2 ms-auto" role="search">
                @if(request('status') !== null && request('status') !== '')
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input class="form-control form-control-sm" name="search" value="{{ $search ?? request('search') }}" type="search" placeholder="Search orders" aria-label="Search">
                <button class="btn btn-light btn-sm" type="submit">Search</button>
            </form>
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
                                    <th>Activity</th>
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
                                            <input type="checkbox" class="form-check-input order-checkbox sub_chk" data-id="{{ $order->id }}" value="{{ $order->id }}">
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
                                        <td>
                                            @php
                                                $activity = json_decode($order->customer_activity, true) ?: [];
                                                $total = (int) ($activity['total'] ?? 0);
                                                $delivered = (int) ($activity['total_delivered'] ?? 0);
                                                $returned = (int) ($activity['total_returned'] ?? 0);
                                                $successRate = $total > 0 ? round(($delivered / $total) * 100) : 0;
                                                $cancelRate = $total > 0 ? round(($returned / $total) * 100) : 0;
                                                $chartColor = $successRate < 30 ? 'red' : ($successRate < 70 ? 'yellow' : 'green');
                                            @endphp

                                            <a href="javascript:void(0)" class="customer_activity_btn"
                                                data-customer_phone="{{ $order->customer_phone }}"
                                                data-total="{{ $total }}"
                                                data-total_delivered="{{ $delivered }}"
                                                data-total_returned="{{ $returned }}"
                                                data-pathao_delivered="{{ data_get($activity, 'Pathao.delivered', 0) }}"
                                                data-pathao_returned="{{ data_get($activity, 'Pathao.returned', 0) }}"
                                                data-redx_delivered="{{ data_get($activity, 'Redx.delivered', 0) }}"
                                                data-redx_returned="{{ data_get($activity, 'Redx.returned', 0) }}"
                                                data-paperfly_delivered="{{ data_get($activity, 'Paperfly.delivered', 0) }}"
                                                data-paperfly_returned="{{ data_get($activity, 'Paperfly.returned', 0) }}"
                                                data-steadfast_delivered="{{ data_get($activity, 'SteadFast.delivered', 0) }}"
                                                data-steadfast_returned="{{ data_get($activity, 'SteadFast.returned', 0) }}"
                                                data-update_route="{{ route('fraud.check', $order->id) }}">

                                                <canvas class="successChart" data-order="{{ $total }}" data-rate="{{ $successRate }}" data-cancel="{{ $cancelRate }}" data-color="{{ $chartColor }}" width="50" height="50"></canvas>
                                            </a>
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
                                            <span class="badge bg-{{ ($orderStatus[$order->status]['color'] ?? 'secondary') }}-transparent">
                                                {{ $orderStatus[$order->status]['label'] ?? 'Unknown' }}
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



    <!-- Customer Activity Modal - Gorgeous Design -->
    <div class="modal fade" id="customer_activity_modal" tabindex="-1" aria-labelledby="customer_activity_modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 10px;">

                <!-- Header with Gradient Background -->
                <div class="modal-header border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex align-items-center gap-3 w-100">
                        <span class="avatar avatar-lg avatar-rounded bg-white text-primary fs-5">
                            <i class="ri-line-chart-line"></i>
                        </span>
                        <div class="text-white">
                            <h5 class="modal-title mb-1 fs-5 fw-bold" id="customer_activity_modalTitle">Customer Activity</h5>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-phone-line fs-6"></i>
                                <small id="customer_phone_preview" class="fw-semibold">-</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body py-3">
                    <!-- Quick Stats Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6 col-lg-3">
                            <div class="p-2 rounded-3 border-0 bg-light text-center">
                                <div class="text-muted small mb-1">Total Parcels</div>
                                <div class="fs-4 fw-bold text-primary" id="total_parcel">0</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="p-2 rounded-3 border-0 bg-success bg-opacity-10 text-center">
                                <div class="text-muted small mb-1">Delivered</div>
                                <div class="fs-4 fw-bold text-success" id="total_delivered">0</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="p-2 rounded-3 border-0 bg-danger bg-opacity-10 text-center">
                                <div class="text-muted small mb-1">Returned</div>
                                <div class="fs-4 fw-bold text-danger" id="total_returned">0</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="p-2 rounded-3 border-0 bg-warning bg-opacity-10 text-center">
                                <div class="text-muted small mb-1">Success Rate</div>
                                <div class="fs-4 fw-bold text-warning" id="total_success_ratio">0%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Courier Cards Grid -->
                    <div class="row g-2">
                        <!-- Pathao Card -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="p-2 border-bottom" style="background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('backend/images/courier-logo/pathao.jpg') }}" alt="Pathao" style="height:36px" class="rounded-2">
                                            <div class="text-white">
                                                <div class="fw-bold">Pathao</div>
                                                <small>Delivery Partner</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">Total</div>
                                                <div class="fs-6 fw-bold text-dark" id="pathao_total">0</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">Success</div>
                                                <div class="fs-6 fw-bold text-success" id="pathao_success_ratio">0%</div>
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-6">
                                                <small class="text-success d-flex align-items-center gap-1"><i class="ri-check-double-line"></i> <span id="pathao_delivered">0</span> Delivered</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-danger d-flex align-items-center gap-1"><i class="ri-close-line"></i> <span id="pathao_returned">0</span> Returned</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SteadFast Card -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="p-2 border-bottom" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('backend/images/courier-logo/steadfast.jpg') }}" alt="Steadfast" style="height:36px" class="rounded-2">
                                            <div class="text-white">
                                                <div class="fw-bold">Steadfast</div>
                                                <small>Delivery Partner</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">Total</div>
                                                <div class="fs-6 fw-bold text-dark" id="steadfast_total">0</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">Success</div>
                                                <div class="fs-6 fw-bold text-success" id="steadfast_success_ratio">0%</div>
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-6">
                                                <small class="text-success d-flex align-items-center gap-1"><i class="ri-check-double-line"></i> <span id="steadfast_delivered">0</span> Delivered</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-danger d-flex align-items-center gap-1"><i class="ri-close-line"></i> <span id="steadfast_returned">0</span> Returned</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Carrybee Card -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="p-2 border-bottom" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('backend/images/courier-logo/carrybee.png') }}" alt="Carrybee" style="height:36px" class="rounded-2">
                                            <div class="text-white">
                                                <div class="fw-bold">Carrybee</div>
                                                <small>Delivery Partner</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">Total</div>
                                                <div class="fs-6 fw-bold text-dark" id="carrybee_total">0</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">Success</div>
                                                <div class="fs-6 fw-bold text-success" id="carrybee_success_ratio">0%</div>
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-6">
                                                <small class="text-success d-flex align-items-center gap-1"><i class="ri-check-double-line"></i> <span id="carrybee_delivered">0</span> Delivered</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-danger d-flex align-items-center gap-1"><i class="ri-close-line"></i> <span id="carrybee_returned">0</span> Returned</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RedX Card -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="p-2 border-bottom" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('backend/images/courier-logo/redx.jpg') }}" alt="RedX" style="height:36px" class="rounded-2">
                                            <div class="text-white">
                                                <div class="fw-bold">RedX</div>
                                                <small>Delivery Partner</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">Total</div>
                                                <div class="fs-6 fw-bold text-dark" id="redx_total">0</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">Success</div>
                                                <div class="fs-6 fw-bold text-success" id="redx_success_ratio">0%</div>
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-6">
                                                <small class="text-success d-flex align-items-center gap-1"><i class="ri-check-double-line"></i> <span id="redx_delivered">0</span> Delivered</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-danger d-flex align-items-center gap-1"><i class="ri-close-line"></i> <span id="redx_returned">0</span> Returned</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paperfly Card -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="p-2 border-bottom" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset('backend/images/courier-logo/paperfly.jpg') }}" alt="Paperfly" style="height:36px" class="rounded-2">
                                            <div class="text-white">
                                                <div class="fw-bold">Paperfly</div>
                                                <small>Delivery Partner</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">Total</div>
                                                <div class="fs-6 fw-bold text-dark" id="paperfly_total">0</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">Success</div>
                                                <div class="fs-6 fw-bold text-success" id="paperfly_success_ratio">0%</div>
                                            </div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-6">
                                                <small class="text-success d-flex align-items-center gap-1"><i class="ri-check-double-line"></i> <span id="paperfly_delivered">0</span> Delivered</small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-danger d-flex align-items-center gap-1"><i class="ri-close-line"></i> <span id="paperfly_returned">0</span> Returned</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer with Action -->
                <div class="modal-footer border-top py-2 bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i> Close
                    </button>
                    <a href="#" id="updateRouteLink" class="btn btn-primary btn-sm">
                        <i class="ri-refresh-line"></i> Refresh Activity
                    </a>
                </div>

            </div>
        </div>
    </div>

    @push('js')
        <script>
            //send to courier
            $(document).on('click', '#courier_entry_btn', function(e) {
                e.preventDefault();

                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select at least one row.");
                    return;
                }

                if (confirm("Are you sure you want to send the selected orders to the courier?")) {
                    $('.courier_export_ids').val(allVals.join(','));
                    $('#courier_export_form').submit();
                }
            });

            $(document).on('change', '#bulk_assign', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                    $('#bulk_assign').prop('selectedIndex', 0);
                    return;
                } else {
                    if (confirm('Are Your Sure To Assign?') == true) {
                        $('.bulk_assign_ids').val(allVals);
                        $('#bulk_assign_form').submit();
                    }
                }
            });

            $(document).on('click', '.cancel_status', function(e) {
                $("#sale_id").val($(this).data('id'));
                $("#cancel_status_modal").modal('show');
            });

            $('input[name="cancel_reason"]').change(function() {
                if ($(this).val() === 'others_reason') {
                    $('.other_reason_area').show();
                    $('#others').prop('required', true);
                } else {
                    $('.other_reason_area').hide();
                    $('#others').prop('required', false);
                }
            });

            $(document).on('change', '#bulk_status', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                    $('#bulk_status').prop('selectedIndex', 0);
                    return;
                } else {
                    $('#bulk_status_ids').val(allVals.join(','));
                    $('#bulk_status_form').submit();
                }
            });

            $(document).on('change', '#courier_export', function(e) {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });

                if (allVals.length <= 0) {
                    alert("Please select row.");
                    $('#courier_export').prop('selectedIndex', 0);
                    return;
                }

                $('.courier_export_ids').val(allVals.join(','));
                $('#courier_export_form').submit();
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#product').select2({
                    placeholder: "Select product(s)"
                });

                // Update hidden input whenever selection changes
                $('#product').on('change', function() {
                    const selected = $(this).val(); // array of selected product IDs
                    $('#product_ids').val(selected ? selected.join(',') : '');
                });
            });
        </script>

        <script>
        $(document).on('click', '.show-transactions', function () {
            let saleId = $(this).data('id');
            $('#transactionsModal').modal('show');
            $('#transaction-content').html('<div class="spinner-border text-warning" role="status"></div>');

            $.ajax({
                url: "{{ url('sale/transactions') }}/" + saleId,
                type: "GET",
                success: function (data) {
                    $('#transaction-content').html(data);
                },
                error: function () {
                    $('#transaction-content').html('<p class="text-danger">Failed to load transactions.</p>');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize chart in each row
            document.querySelectorAll('.successChart').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const order = parseInt(canvas.dataset.order);
                const rate = parseInt(canvas.dataset.rate);
                const cancelRate = parseInt(canvas.dataset.cancel);
                const color = canvas.dataset.color;
                // Fix canvas size
                canvas.width = 80; // width in px
                canvas.height = 80; // height in px

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [rate, 100 - rate],
                            backgroundColor: [color, cancelRate === 100 ? '#f53346' : '#f0f0f0'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '75%',
                        responsive: false, // prevent resizing
                        plugins: {
                            tooltip: {
                                enabled: false
                            },
                            legend: {
                                display: false
                            }
                        }
                    },
                    // plugins: [{
                    //     id: 'centerText',
                    //     beforeDraw(chart) {
                    //         const {
                    //             ctx,
                    //             width,
                    //             height
                    //         } = chart;
                    //         ctx.save();
                    //         ctx.font = 'bold 12px Arial'; // increase font size
                    //         ctx.fillStyle = '#000';
                    //         ctx.textAlign = 'center';
                    //         ctx.textBaseline = 'middle';
                    //         ctx.fillText(rate + '%', width / 2, height / 2);
                    //         ctx.restore();
                    //     }
                    // }]
                    plugins: [{
                        id: 'centerText',
                        beforeDraw(chart) {
                            const { ctx, width, height } = chart;
                            ctx.save();

                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = '#000';

                            const line1 = rate + '%';
                            const line2 = order + ' Order';

                            ctx.font = 'bold 14px Arial';
                            ctx.fillText(line1, width / 2, height / 2 - 8);

                            ctx.font = 'bold 11px Arial';
                            ctx.fillText(line2, width / 2, height / 2 + 8);

                            ctx.restore();
                        }
                    }]



                });
            });

            // Modal populate
            document.querySelectorAll('.customer_activity_btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const phone = this.dataset.customer_phone;
                    const updateRoute = this.dataset.update_route;

                    document.getElementById('customer_activity_modalTitle').textContent =
                        'Activity of ' + phone;
                    document.getElementById('customer_phone_preview').textContent = phone || '-';
                    document.getElementById('updateRouteLink').href = updateRoute;

                    const couriers = ['pathao', 'redx', 'paperfly', 'steadfast', 'carrybee'];
                    let total = 0,
                        totalDelivered = 0,
                        totalReturned = 0;

                    couriers.forEach(c => {
                        const delivered = parseInt(this.dataset[`${c}_delivered`]) || 0;
                        const returned = parseInt(this.dataset[`${c}_returned`]) || 0;
                        const sum = delivered + returned;
                        const ratio = sum > 0 ? ((delivered / sum) * 100).toFixed(1) + '%' :
                            '0%';
                        document.getElementById(`${c}_total`).textContent = sum;
                        document.getElementById(`${c}_delivered`).textContent = delivered;
                        document.getElementById(`${c}_returned`).textContent = returned;
                        document.getElementById(`${c}_success_ratio`).textContent = ratio;

                        total += sum;
                        totalDelivered += delivered;
                        totalReturned += returned;
                    });

                    const totalRatio = total > 0 ? ((totalDelivered / total) * 100).toFixed(1) +
                        '%' : '0%';
                    document.getElementById('total_parcel').textContent = total;
                    document.getElementById('total_delivered').textContent = totalDelivered;
                    document.getElementById('total_returned').textContent = totalReturned;
                    document.getElementById('total_success_ratio').textContent = totalRatio;

                    $('#customer_activity_modal').modal('show');
                });
            });
        });
    </script>
    @endpush

</x-backend-layout>
