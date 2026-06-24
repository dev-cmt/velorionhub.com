<div>
    {{-- Modal Card Header --}}
    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
        <div>
            <span class="fw-semibold text-dark">Purchase #{{ $data->id }}</span>
            <span class="text-muted ms-2 small">{{ date('d M Y', strtotime($data->date)) }} &bull; {{ $data->supplier->name }}</span>
        </div>
        <button type="button" class="btn btn-primary btn-sm {{ $data->status == 1 ? 'd-none' : '' }}" id="save-all">
            <i class="bx bx-download me-1"></i> Receive All
        </button>
    </div>

    @php
        $allDone = $data->purchase_items->every(fn($i) => $i->purchase_qty <= $i->recived_qty);
    @endphp

    @if($allDone)
        <div class="text-center py-5 text-success">
            <i class="bx bx-check-circle" style="font-size:3rem;"></i>
            <p class="mt-2 fw-semibold fs-5">All items fully received!</p>
        </div>
    @elseif(count($data->purchase_items) > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0" style="vertical-align:middle;">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th class="text-center">Ordered</th>
                    <th class="text-center">Received</th>
                    <th class="text-center">Remaining</th>
                    <th style="width:130px;">Qty to Receive</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->purchase_items as $loop => $item)
                @php
                    $remaining      = $item->purchase_qty - $item->recived_qty;
                    $progressPct    = $item->purchase_qty > 0
                                      ? round(($item->recived_qty / $item->purchase_qty) * 100)
                                      : 0;
                    $isDone         = $remaining <= 0;
                @endphp
                <tr id="row-{{ $item->id }}" class="{{ $isDone ? 'table-success' : '' }}">
                    <td class="text-muted small">{{ $loop + 1 }}</td>
                    <td>{{ optional($item->get_product)->name ?? '<em class="text-muted">Deleted</em>' }}</td>
                    <td><span class="badge bg-info-transparent text-info">{{ $item->sku ?: '—' }}</span></td>
                    <td class="text-center">{{ $item->purchase_qty }}</td>
                    <td class="text-center text-success fw-semibold">
                        <span id="received-{{ $item->id }}">{{ $item->recived_qty }}</span>
                    </td>
                    <td class="text-center {{ $isDone ? 'text-muted' : 'text-danger fw-semibold' }}">
                        <span id="remaining-{{ $item->id }}">{{ $remaining }}</span>
                        @if($item->purchase_qty > 0)
                        <div class="progress mt-1" style="height:4px; border-radius:2px;">
                            <div id="progress-{{ $item->id }}"
                                 class="progress-bar {{ $isDone ? 'bg-success' : 'bg-primary' }}"
                                 role="progressbar"
                                 style="width: {{ $progressPct }}%"
                                 aria-valuenow="{{ $progressPct }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        @endif
                    </td>
                    <td>
                        @if(!$isDone)
                        <input type="number"
                               class="form-control form-control-sm receive-input"
                               id="input-{{ $item->id }}"
                               data-id="{{ $item->id }}"
                               data-max="{{ $remaining }}"
                               data-purchase-qty="{{ $item->purchase_qty }}"
                               min="1"
                               max="{{ $remaining }}"
                               value="{{ $remaining }}"
                               placeholder="Qty">
                        <div id="error-{{ $item->id }}" class="text-danger d-none small mt-1"></div>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($isDone)
                            <span class="badge bg-success" id="save-{{ $item->id }}">
                                <i class="bx bx-check-circle me-1"></i>Done
                            </span>
                        @else
                            <button type="button"
                                    class="btn btn-sm btn-success save-receive"
                                    id="save-{{ $item->id }}"
                                    data-id="{{ $item->id }}">
                                Save
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="bx bx-package" style="font-size:2.5rem;"></i>
        <p class="mt-2">No items found for this purchase.</p>
    </div>
    @endif
</div>
