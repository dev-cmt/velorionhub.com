<x-backend-layout title="Expired Products Management">

<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h1 class="page-title fw-semibold fs-18 mb-1">Expired Products</h1>
        <p class="text-muted mb-0">Track, restore, or dispose of inventory past its shelf life.</p>
    </div>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Expired Products</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card shadow-sm border-0 overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
                <div class="card-title mb-0">
                    <i class='bx bx-time-five me-1 align-middle text-danger fs-20'></i>
                    Expired Inventory List <span class="badge bg-danger-transparent ms-2 rounded-pill">{{ $expiredProducts->count() }} Items</span>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="search-box">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0 px-3">
                                <i class="ri-calendar-event-line text-muted"></i>
                            </span>
                            <input type="text" id="dateFilter" class="form-control border-0 bg-light fs-12" 
                                placeholder="Filter dates..." style="width: 180px;">
                        </div>
                    </div>

                    <button id="downloadPdf" class="btn btn-sm btn-danger shadow-none px-3">
                        <i class="ri-file-pdf-2-line me-1 align-middle"></i> Export PDF
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div id="alertMsg" class="px-3 mt-3"></div>

                @if($expiredProducts->isEmpty())
                    <div class="p-5 text-center">
                        <div class="avatar avatar-xl bg-primary-transparent mb-3">
                            <i class="ri-shield-check-line fs-40 text-primary"></i>
                        </div>
                        <h5 class="fw-semibold">All products are within date!</h5>
                        <p class="text-muted mx-auto" style="max-width: 400px;">No expired items were found in your inventory. Good job on keeping the stock fresh.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="expiredTable" class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product Details</th>
                                    <th>SKU</th>
                                    <th>Manufactured</th>
                                    <th>Expired On</th>
                                    <th>Overdue By</th>
                                    <th class="text-center">Quick Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiredProducts as $product)
                                    @php
                                        $expiredDate = \Carbon\Carbon::parse($product->expire_date);
                                        $daysExpired = round($expiredDate->diffInHours(now()) / 24);
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md bg-light text-muted border border-dashed rounded me-3">
                                                    <i class="ri-flask-line"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-semibold mb-0 fs-14 text-dark">{{ $product->name }}</h6>
                                                    <small class="text-muted">ID: #{{ $product->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-muted border fw-medium">{{ $product->sku }}</span>
                                        </td>
                                        <td>{{ optional($product->manufacturer_date)->format('d M, Y') ?? '—' }}</td>
                                        <td>
                                            <span class="text-danger fw-semibold">{{ $expiredDate->format('d M Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-danger">
                                                <i class="ri-alarm-warning-line me-1"></i>{{ $daysExpired }} days
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group shadow-none">
                                                <button class="btn btn-sm btn-primary-light" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#restoreModal{{ $product->id }}"
                                                        title="Extend Expiry">
                                                    <i class="ri-refresh-line"></i> Restore
                                                </button>
                                                <button class="btn btn-sm btn-danger-light" 
                                                        onclick="handleExpiredProduct({{ $product->id }})"
                                                        title="Mark as Handled">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="restoreModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Extend Expiry Date</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form id="restoreForm{{ $product->id }}">
                                                    @csrf
                                                    <div class="modal-body py-4 text-center">
                                                        <p class="text-muted mb-3">Updating <strong>{{ $product->name }}</strong></p>
                                                        <input type="date" name="expire_date" class="form-control form-control-lg text-center" 
                                                               min="{{ now()->format('Y-m-d') }}" 
                                                               value="{{ now()->addMonths(6)->format('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="modal-footer justify-content-center bg-light border-0">
                                                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary px-4" onclick="restoreProduct({{ $product->id }})">Update Date</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ✅ JS Routes --}}
<script>
    window.routes = {
        handleExpired: "{{ route('products.handleExpired', ':id') }}",
        restoreExpired: "{{ route('products.restoreExpired', ':id') }}"
    };
</script>

@push('js')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
$(document).ready(function() {
    const table = $('#expiredTable').DataTable({
        pageLength: 20,
        ordering: true,
        order: [[3, 'desc']],
        language: {
            searchPlaceholder: 'Search products...',
            sSearch: '',
        }
    });

    $('#dateFilter').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear', format: 'YYYY-MM-DD' }
    });

    $('#dateFilter').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $.fn.dataTable.ext.search.push(function(settings, data) {
            const min = picker.startDate.format('YYYY-MM-DD');
            const max = picker.endDate.format('YYYY-MM-DD');
            const expireDate = moment(data[3], 'DD MMM YYYY').format('YYYY-MM-DD');
            return expireDate >= min && expireDate <= max;
        });
        table.draw();
    });

    $('#dateFilter').on('cancel.daterangepicker', function() {
        $(this).val('');
        $.fn.dataTable.ext.search.pop();
        table.draw();
    });

    $('#downloadPdf').on('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        doc.setFontSize(16);
        doc.text("Expired Inventory Report", 14, 15);
        doc.setFontSize(10);
        doc.text(`Generated on: ${moment().format('LLL')}`, 14, 22);

        const rows = [];
        table.rows({ search: 'applied' }).every(function() {
            const data = this.data();
            // Extraction logic for PDF rows (Clean labels from HTML)
            const productName = $(data[0]).find('h6').text();
            rows.push([productName, data[1], data[2], data[3], data[4]]);
        });

        doc.autoTable({
            head: [['Product', 'SKU', 'Mfd Date', 'Expiry Date', 'Overdue']],
            body: rows,
            startY: 30,
            theme: 'striped',
            headStyles: { fillColor: [220, 53, 69] }
        });

        doc.save('expired_report.pdf');
    });
});

function handleExpiredProduct(id) {
    Swal.fire({
        title: 'Confirm Handling',
        text: "Mark this product as disposed or handled? It will be removed from this list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, Mark Handled'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(window.routes.handleExpired.replace(':id', id), { _token: "{{ csrf_token() }}" })
                .done(res => {
                    Swal.fire('Updated!', res.message, 'success');
                    location.reload();
                });
        }
    });
}

function restoreProduct(id) {
    const form = $('#restoreForm' + id);
    $.post(window.routes.restoreExpired.replace(':id', id), form.serialize())
        .done(res => {
            Swal.fire('Restored!', res.message, 'success');
            location.reload();
        })
        .fail(err => Swal.fire('Error', 'Update failed', 'error'));
}
</script>
@endpush

</x-backend-layout>