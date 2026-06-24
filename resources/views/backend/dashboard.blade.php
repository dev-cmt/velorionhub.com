<x-backend-layout title="Dashboard">
    <!-- Start::page-header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <p class="fw-semibold fs-18 mb-0">Welcome back, {{Auth::user()->name}} !</p>
            <span class="fs-semibold text-muted">Today is {{ now()->format('l, d M Y') }} &mdash; Here's what's happening.</span>
        </div>
        <div class="btn-list mt-md-0 mt-2">
            <a href="{{ route('orders.index') }}" class="btn btn-primary btn-wave">
                <i class="ri-shopping-bag-3-line me-2 align-middle d-inline-block"></i>All Orders
            </a>
            <a href="{{ route('orders.create') }}" class="btn btn-outline-secondary btn-wave">
                <i class="ri-add-line me-2 align-middle d-inline-block"></i>New Order
            </a>
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start::row-1 (Today's Summary Cards) -->
    <div class="row mb-3">
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card overflow-hidden border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fs-12">Today's Orders</p>
                            <h3 class="fw-bold mb-0 text-primary">{{ $data['todayOrderCount'] }}</h3>
                            <span class="text-muted fs-12">৳{{ number_format($data['todayRevenue'], 0) }} total value</span>
                        </div>
                        <div>
                            <span class="avatar avatar-lg avatar-rounded bg-primary-transparent">
                                <i class="ri-shopping-cart-2-line fs-22 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card overflow-hidden border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fs-12">Today's Collected</p>
                            <h3 class="fw-bold mb-0 text-success">৳{{ number_format($data['todayPaid'], 0) }}</h3>
                            <span class="text-muted fs-12">Due: ৳{{ number_format($data['todayDue'], 0) }}</span>
                        </div>
                        <div>
                            <span class="avatar avatar-lg avatar-rounded bg-success-transparent">
                                <i class="ri-money-dollar-circle-line fs-22 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card overflow-hidden border-start border-warning border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fs-12">Today's Activities</p>
                            <h3 class="fw-bold mb-0 text-warning">{{ $data['todayStatusChanges'] + $data['todayCreated'] + $data['todayUpdates'] }}</h3>
                            <span class="text-muted fs-12">{{ $data['todayCreated'] }} new &bull; {{ $data['todayStatusChanges'] }} status changes</span>
                        </div>
                        <div>
                            <span class="avatar avatar-lg avatar-rounded bg-warning-transparent">
                                <i class="ri-pulse-line fs-22 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card overflow-hidden border-start border-info border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 fs-12">Total Delivered Revenue</p>
                            <h3 class="fw-bold mb-0 text-info">৳{{ number_format($data['totalRevenue'], 0) }}</h3>
                            <span class="text-muted fs-12">{{ $data['deliveredOrders'] }} orders delivered</span>
                        </div>
                        <div>
                            <span class="avatar avatar-lg avatar-rounded bg-info-transparent">
                                <i class="ri-truck-line fs-22 text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End::row-1 -->

    <!-- Start::row-2 (Order Status Cards + Activity + Status Breakdown) -->
    <div class="row">
        <!-- Left: Order Status Breakdown -->
        <div class="col-xxl-8 col-xl-8">
            <div class="row">
                @foreach ($data['statusLabels'] as $statusCode => $statusInfo)
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-3">
                    <div class="card custom-card h-100">
                        <div class="card-body text-center py-3">
                            <span class="badge bg-{{ $statusInfo['color'] }}-transparent fs-12 mb-2">{{ $statusInfo['label'] }}</span>
                            <h4 class="fw-bold mb-0 text-{{ $statusInfo['color'] }}">{{ $data['orderCounts'][$statusCode] ?? 0 }}</h4>
                            <span class="text-muted fs-11">orders</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- All-Time Totals Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Order Overview</div>
                            <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm btn-wave">View All Orders</a>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 col-md-3 border-end">
                                    <h4 class="fw-bold mb-0">{{ $data['totalOrders'] }}</h4>
                                    <span class="text-muted fs-12">Total Orders</span>
                                </div>
                                <div class="col-6 col-md-3 border-end">
                                    <h4 class="fw-bold mb-0 text-warning">{{ $data['pendingOrders'] }}</h4>
                                    <span class="text-muted fs-12">Pending</span>
                                </div>
                                <div class="col-6 col-md-3 border-end">
                                    <h4 class="fw-bold mb-0 text-success">{{ $data['deliveredOrders'] }}</h4>
                                    <span class="text-muted fs-12">Delivered</span>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h4 class="fw-bold mb-0 text-danger">{{ $data['cancelledOrders'] }}</h4>
                                    <span class="text-muted fs-12">Cancelled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>

        <!-- Right: Today's Activity Feed -->Feed -->
        <div class="col-xxl-4 col-xl-4">
            <div class="card custom-card h-100">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        <i class="ri-pulse-line me-1 text-primary"></i>
                        Today's Activity
                        @if($data['recentActivities']->count() > 0)
                            <span class="badge bg-primary-transparent ms-2">{{ $data['recentActivities']->count() }}</span>
                        @endif
                    </div>
                    <a href="{{ route('orders.index') }}" class="p-2 fs-12 text-muted">
                        View Orders<i class="ri-arrow-right-s-line align-middle ms-1 d-inline-block"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($data['recentActivities']->count() > 0)
                    <div style="max-height: 520px; overflow-y: auto;">
                        <ul class="list-unstyled mb-0 crm-recent-activity p-3">
                            @foreach($data['recentActivities'] as $activity)
                            @php
                                $actionColors = [
                                    'created'        => 'success',
                                    'status_changed' => 'primary',
                                    'updated'        => 'warning',
                                ];
                                $actionIcons = [
                                    'created'        => 'ri-add-circle-line',
                                    'status_changed' => 'ri-refresh-line',
                                    'updated'        => 'ri-edit-line',
                                ];
                                $actionColor = $actionColors[$activity->action] ?? 'secondary';
                                $actionIcon  = $actionIcons[$activity->action]  ?? 'ri-information-line';
                                $statusLabels = [
                                    0=>'Pending', 1=>'Confirmed', 2=>'Hold', 3=>'Cancelled',
                                    4=>'Stockout', 5=>'Packaged', 6=>'Courier Entry', 7=>'On Delivery',
                                    8=>'Delivered', 9=>'Partial Delivered', 10=>'Exchange',
                                    11=>'Return', 12=>'Return Received',
                                ];
                                $statusColors = [
                                    0=>'warning', 1=>'info', 2=>'secondary', 3=>'danger',
                                    4=>'danger', 5=>'secondary', 6=>'primary', 7=>'info',
                                    8=>'success', 9=>'secondary', 10=>'warning', 11=>'danger', 12=>'success',
                                ];
                            @endphp
                            <li class="crm-recent-activity-content mb-1">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="flex-shrink-0">
                                        <span class="avatar avatar-xs bg-{{ $actionColor }}-transparent avatar-rounded">
                                            <i class="{{ $actionIcon }} fs-10 text-{{ $actionColor }}"></i>
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="crm-timeline-content">
                                            @if($activity->action === 'created')
                                                <span class="fw-semibold fs-12">New Order</span>
                                                @if($activity->order)
                                                    <a href="{{ route('orders.edit', $activity->order_id) }}" class="text-primary fw-semibold fs-12">
                                                        #{{ $activity->order->invoice_no ?? $activity->order_id }}
                                                    </a>
                                                @endif
                                                <span class="fs-12">placed</span>
                                            @elseif($activity->action === 'status_changed')
                                                <span class="fs-12">Order</span>
                                                @if($activity->order)
                                                    <a href="{{ route('orders.edit', $activity->order_id) }}" class="text-primary fw-semibold fs-12">
                                                        #{{ $activity->order->invoice_no ?? $activity->order_id }}
                                                    </a>
                                                @endif
                                                <span class="fs-12">status:</span>
                                                @if($activity->old_status !== null)
                                                    <span class="badge bg-{{ $statusColors[$activity->old_status] ?? 'secondary' }}-transparent fs-10">
                                                        {{ $statusLabels[$activity->old_status] ?? 'Unknown' }}
                                                    </span>
                                                    <i class="ri-arrow-right-line align-middle fs-10"></i>
                                                @endif
                                                <span class="badge bg-{{ $statusColors[$activity->new_status] ?? 'secondary' }}-transparent fs-10">
                                                    {{ $statusLabels[$activity->new_status] ?? 'Unknown' }}
                                                </span>
                                            @else
                                                <span class="fs-12">Order</span>
                                                @if($activity->order)
                                                    <a href="{{ route('orders.edit', $activity->order_id) }}" class="text-primary fw-semibold fs-12">
                                                        #{{ $activity->order->invoice_no ?? $activity->order_id }}
                                                    </a>
                                                @endif
                                                <span class="fs-12">updated</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <span class="text-muted fs-11">
                                                <i class="ri-user-line me-1"></i>
                                                {{ $activity->user?->name ?? 'System' }}
                                            </span>
                                            <span class="text-muted fs-11 op-7">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($activity->reason && !in_array($activity->reason, ['Order placed/created']))
                                            <div class="text-muted fs-11 mt-1 fst-italic">{{ Str::limit($activity->reason, 60) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="avatar avatar-xl bg-primary-transparent avatar-rounded mb-3 mx-auto">
                            <i class="ri-calendar-check-line fs-28 text-primary"></i>
                        </div>
                        <p class="text-muted mb-0">No activity today yet.</p>
                        <span class="text-muted fs-12">Orders and status changes will appear here.</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End::row-2 -->

</x-backend-layout>
        