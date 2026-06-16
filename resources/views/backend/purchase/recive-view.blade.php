<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0">Purchase #{{ $data->id }}</h6>
            <small class="text-muted">Date: {{ date('d M Y', strtotime($data->date)) }}, Supplier: {{ $data->get_supplier->name }}</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm" id="save-all">Receive All</button>
    </div>
    <div class="card-body">
        @if(count($data->purchase_items) > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Purchase Qty</th>
                        <th>Received</th>
                        <th>Remaining</th>
                        <th>Receive Now</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->purchase_items as $item)
                    @php
                        $remaining = $item->purchase_qty - $item->recived_qty;
                        $progressPercent = $item->purchase_qty > 0 ? ($item->recived_qty / $item->purchase_qty) * 100 : 0;
                    @endphp
                    <tr id="row-{{ $item->id }}" class="item-row">
                        <td>{{ $item->get_product->name }} <span class="text-info">#{{ $item->sku }}</span></td>
                        <td>{{ $item->purchase_qty }}</td>
                        <td>
                            <input type="text" class="form-control form-control-sm received-display" id="received-{{ $item->id }}" value="{{ $item->recived_qty }}" disabled>
                        </td>
                        <td id="remaining-{{ $item->id }}">{{ $remaining }}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm receive-input"
                                   data-id="{{ $item->id }}"
                                   data-max="{{ $remaining }}"
                                   data-purchase-qty="{{ $item->purchase_qty }}"
                                   min="0"
                                   max="{{ $remaining }}"
                                   placeholder="Qty"
                                   value="{{ $remaining }}"
                                   @if($remaining == 0) disabled @endif>
                            <div id="error-{{ $item->id }}" class="text-danger d-none">Invalid quantity</div>
                            @if($item->purchase_qty > 0)
                            <div id="progress-{{ $item->id }}" class="progress progress-sm mt-1">
                                <div class="progress-bar" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            @endif
                        </td>
                        <td>
                            @if($remaining > 0)
                                <button type="button" class="btn btn-sm btn-success save-receive" id="save-{{ $item->id }}" data-id="{{ $item->id }}">Save</button>
                            @else
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center p-4 text-muted">
            <i class="fas fa-box-open fa-3x"></i>
            <p class="mt-2">No items found for this purchase.</p>
        </div>
        @endif
    </div>
</div>
