<div class="p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <div>
            <h6 class="fw-semibold mb-0">Invoice: {{ $order->invoice_no }}</h6>
            <small class="text-muted">Customer: {{ $order->customer_name }} ({{ $order->customer_phone }})</small>
        </div>
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-primary-light">
            <i class="ri-eye-line me-1"></i> Edit/Full Details
        </a>
    </div>

    @if($order->histories->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="ri-history-line fs-3 d-block mb-1"></i> No history logs recorded for this order.
        </div>
    @else
        <div class="timeline-container px-2" style="max-height: 450px; overflow-y: auto;">
            <ul class="list-unstyled mb-0">
                @foreach($order->histories as $history)
                    @php
                        $statusMap = [
                            0 => ['label' => 'Pending',           'color' => 'secondary'],
                            1 => ['label' => 'Confirmed',         'color' => 'info'],
                            2 => ['label' => 'Hold',              'color' => 'warning text-dark'],
                            3 => ['label' => 'Cancelled',         'color' => 'danger'],
                            4 => ['label' => 'Stock Out',         'color' => 'danger'],
                            5 => ['label' => 'Packaged',          'color' => 'secondary'],
                            6 => ['label' => 'Courier Entry',     'color' => 'primary'],
                            7 => ['label' => 'On Delivery',       'color' => 'info'],
                            8 => ['label' => 'Delivered',         'color' => 'success'],
                            9 => ['label' => 'Partial Delivered', 'color' => 'secondary'],
                            10 => ['label' => 'Exchange',         'color' => 'warning text-dark'],
                            11 => ['label' => 'Return',           'color' => 'danger'],
                            12 => ['label' => 'Return Received',  'color' => 'success'],
                        ];
                    @endphp
                    <li class="position-relative pb-4 ps-4 border-start border-2" style="border-color: #dee2e6 !important; min-height: 50px;">
                        <span class="position-absolute translate-middle-x bg-white border border-2 border-primary rounded-circle" 
                              style="left: 0; top: 0; width: 12px; height: 12px;"></span>
                        
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-dark fs-13">
                                {{ ucfirst($history->action == 'status_changed' ? 'Status changed' : $history->action) }} 
                                @if($history->user)
                                    by <span class="text-primary">{{ $history->user->name }}</span>
                                @else
                                    by <span class="text-muted">System/Webhook</span>
                                @endif
                            </span>
                            <span class="text-muted small fs-11">
                                <i class="ri-calendar-line"></i> {{ $history->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>

                        @if($history->old_status !== null && $history->old_status != $history->new_status)
                            <div class="mb-2 fs-12">
                                <span class="badge bg-{{ $statusMap[$history->old_status]['color'] ?? 'light' }}-transparent">
                                    {{ $statusMap[$history->old_status]['label'] ?? $history->old_status }}
                                </span>
                                <i class="ri-arrow-right-line mx-1 align-middle text-muted"></i>
                                <span class="badge bg-{{ $statusMap[$history->new_status]['color'] ?? 'light' }}-transparent">
                                    {{ $statusMap[$history->new_status]['label'] ?? $history->new_status }}
                                </span>
                            </div>
                        @endif

                        @if($history->reason)
                            <p class="text-muted mb-1 fs-12">
                                <strong>Reason:</strong> {{ $history->reason }}
                            </p>
                        @endif

                        @if(!empty($history->changes))
                            <div class="mt-1 fs-11">
                                <a href="javascript:void(0)" class="text-primary toggle-modal-changes" data-target="#modal-changes-{{ $history->id }}">
                                    Show Changes
                                </a>
                                <div class="modal-changes-list d-none mt-1 p-2 bg-light rounded" id="modal-changes-{{ $history->id }}">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($history->changes as $field => $val)
                                            <li>
                                                <code class="text-dark">{{ ucwords(str_replace('_', ' ', $field)) }}</code>: 
                                                <span class="text-danger">{{ is_array($val['old']) ? json_encode($val['old']) : ($val['old'] ?? 'N/A') }}</span> 
                                                <i class="ri-arrow-right-line align-middle"></i> 
                                                <span class="text-success">{{ is_array($val['new']) ? json_encode($val['new']) : ($val['new'] ?? 'N/A') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
