<x-backend-layout title="Label Print">
<!-- Page Header -->
<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <div>
        <h1 class="page-title fw-semibold fs-18 mb-0">Label & Code Generator</h1>
        <p class="text-muted mb-0">Design and print professional barcodes and QR labels.</p>
    </div>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Label Print</li>
            </ol>
        </nav>
    </div>
</div>

<form id="labelPrintForm">
    <div class="row">
        <div class="col-xxl-4 col-xl-5">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="card-title fs-15"><i class='bx bx-cog me-1 align-middle'></i> 1. Layout Settings</div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-12">
                            <label class="form-label fw-medium"><i class='bx bx-store-alt me-1'></i>Store Name</label>
                            <select name="store" class="form-select border-2">
                                <option value="">None (Hide Store)</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store }}">{{ $store }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class='bx bx-file me-1'></i>Paper Size</label>
                            <select name="paper_size" class="form-select">
                                <option value="A4" selected>A4 Standard</option>
                                <option value="A3">A3 Large</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium"><i class='bx bx-columns me-1'></i>Grid Columns</label>
                            <select name="columns" class="form-select">
                                <option value="1">1 Column</option>
                                <option value="2">2 Columns</option>
                                <option value="3" selected>3 Columns</option>
                                <option value="4">4 Columns</option>
                            </select>
                        </div>
                        
                        <hr class="my-2 text-muted">
                        
                        <div class="col-md-12">
                            <label class="form-label fw-medium mb-2"><i class='bx bx-show me-1'></i>Label Visibility</label>
                            <div class="d-flex flex-column gap-2 p-3 bg-light rounded shadow-inner">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="showStore" checked>
                                    <label class="form-check-label" for="showStore">Show Store Title</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="showProduct" checked>
                                    <label class="form-check-label" for="showProduct">Show Product Name</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="showPrice">
                                    <label class="form-check-label" for="showPrice">Include Price Tag</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card border-0 shadow-sm mt-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="d-grid">
                        <button type="button" id="previewPDF" class="btn btn-outline-info btn-lg border-0 rounded-0 py-3 fw-semibold">
                            <i class='bx bx-show-alt me-2'></i>Live Preview
                        </button>
                        <div class="btn-group w-100">
                            <button type="button" id="generateBarcodePDF" class="btn btn-primary btn-lg rounded-0 py-3 border-0">
                                <i class='bx bx-barcode-reader me-2'></i>Barcode PDF
                            </button>
                            <button type="button" id="generateQRCodePDF" class="btn btn-warning btn-lg rounded-0 py-3 border-0">
                                <i class='bx bx-qr-scan me-2'></i>QR PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-8 col-xl-7">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <div class="card-title fs-15"><i class='bx bx-list-check me-1 align-middle'></i> 2. Select Products</div>
                    <span class="badge bg-primary-transparent" id="selectedCount">0 Items</span>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="position-relative" id="searchContainer">
                            <div class="input-group input-group-lg shadow-sm rounded border overflow-hidden">
                                <span class="input-group-text bg-white border-0">
                                    <i class='bx bx-search fs-4 text-muted'></i>
                                </span>
                                <input type="text" id="productSearch" class="form-control border-0" 
                                    placeholder="Type name or scan SKU barcode..." autocomplete="off">
                            </div>

                            <div id="searchDropdown" class="dropdown-menu w-100 shadow-lg border-0 mt-1 py-0" 
                                style="display: none; max-height: 350px; overflow-y: auto; z-index: 1050;">
                                </div>
                        </div>
                    </div>

                    <div class="table-responsive rounded-3 border">
                        <table class="table table-hover align-middle mb-0" id="productsTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-3">Product Information</th>
                                    <th>SKU</th>
                                    <th width="120">Quantity</th>
                                    <th width="80" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <tr id="emptyRow">
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class='bx bx-package fs-1 d-block mb-2 op-3'></i>
                                        Search and select products to start
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class='bx bx-file-blank me-2'></i>Label Document Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="previewContent">
                </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function(){
    // Helper to update counter
    function updateCounter() {
        let count = $('#productsTable tbody tr:not(#emptyRow)').length;
        $('#selectedCount').text(count + ' Items');
        if(count > 0) $('#emptyRow').hide(); else $('#emptyRow').show();
    }

    // Product Search
    $('#productSearch').on('input', function(){
        let query = $(this).val();
        if(query.length < 2) { $('#searchDropdown').hide(); return; }

        $.get("{{ route('label-print.search') }}", { q: query }, function(data){
            let html = '';
            data.forEach(p => {
                html += `
                <a href="javascript:void(0);" class="dropdown-item p-3 border-bottom addProduct" 
                   data-id="${p.id}" data-name="${p.name}" data-sku="${p.sku}" data-price="${p.sale_price}">
                   <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-light me-3 text-primary"><i class='bx bx-package'></i></div>
                        <div>
                            <div class="fw-semibold">${p.name}</div>
                            <small class="text-muted">SKU: ${p.sku} | Price: $${p.sale_price}</small>
                        </div>
                   </div>
                </a>`;
            });
            $('#searchDropdown').html(html).show();
        });
    });

    // Add Product to Table
    $(document).on('click', '.addProduct', function(){
        let id = $(this).data('id');
        let name = $(this).data('name');
        let sku = $(this).data('sku');
        let price = $(this).data('price');

        if($('#productsTable tbody tr[data-id="'+id+'"]').length) return;

        let row = `
        <tr data-id="${id}" data-price="${price}">
            <td class="ps-3">
                <div class="fw-semibold">${name}</div>
                <small class="text-muted text-uppercase">Price: $${price}</small>
            </td>
            <td><span class="badge bg-light text-dark border">${sku}</span></td>
            <td><input type="number" class="form-control qty-input shadow-none border-2" value="1" min="1"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-icon btn-danger-light removeRow"><i class='bx bx-trash'></i></button>
            </td>
        </tr>`;
        $('#productsTable tbody').append(row);
        $('#searchDropdown').hide();
        $('#productSearch').val('');
        updateCounter();
    });

    // Remove Row
    $(document).on('click', '.removeRow', function(){
        $(this).closest('tr').remove();
        updateCounter();
    });

    // Form Data Handler
    function getFormData() {
        let products = [];
        $('#productsTable tbody tr:not(#emptyRow)').each(function(){
            products.push({
                id: $(this).data('id'), 
                qty: $(this).find('.qty-input').val(), 
                price: $(this).data('price')
            });
        });

        if(products.length === 0){ alert('Please select at least one product.'); return null; }

        let formData = new FormData();
        formData.append('products', JSON.stringify(products));
        formData.append('store', $('select[name="store"]').val()); 
        formData.append('paper_size', $('select[name="paper_size"]').val());
        formData.append('columns', $('select[name="columns"]').val());
        formData.append('show_store', $('#showStore').is(':checked') ? 1 : 0);
        formData.append('show_product', $('#showProduct').is(':checked') ? 1 : 0);
        formData.append('show_price', $('#showPrice').is(':checked') ? 1 : 0);

        return formData;
    }

    // PDF Handlers (Preview & Generate)
    $('#previewPDF').on('click', function(){
        let formData = getFormData();
        if(!formData) return;

        $.ajax({
            url: "{{ route('label-print.generate') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            xhrFields: { responseType: 'blob' },
            success: function(data){
                let blobUrl = URL.createObjectURL(data);
                $('#previewContent').html(`<iframe src="${blobUrl}" frameborder="0" width="100%" style="height: 75vh;"></iframe>`);
                $('#previewModal').modal('show');
            }
        });
    });

    // AJAX for downloads (Shared logic)
    function downloadPDF(url, filename) {
        let formData = getFormData();
        if(!formData) return;
        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            xhrFields: { responseType: 'blob' },
            success: function(blob){
                let link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = filename;
                link.click();
            }
        });
    }

    $('#generateBarcodePDF').on('click', () => downloadPDF("{{ route('label-print.generate') }}", "barcodes.pdf"));
    $('#generateQRCodePDF').on('click', () => downloadPDF("{{ route('label-print.generate.qr') }}", "qrcodes.pdf"));
});
</script>
@endpush
</x-backend-layout>