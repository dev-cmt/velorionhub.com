@extends('backEnd.layouts.master')
@section('title', 'Damage')

@section('css')
<link rel="stylesheet" href="{{ asset('backEnd/assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('backEnd/assets/libs/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/select2.min.css') }}">
<style>
    .select2-container { width: 100% !important; }
    .select2-container--open { z-index: 9999 !important; }
</style>
@endsection

@section('body')
<div class="card custom-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-title">Damage List</div>
        {{-- Trigger for Unified Modal --}}
        <button class="btn btn-success btn-sm" onclick="openAddModal()">Add Damage</button>
    </div>

    <div class="card-body">
        {{-- Success/Error Messages for Standard Form Submission --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table id="mytable" class="table table-bordered text-nowrap w-100">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($damages as $key => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                        <td>{{ $item->get_product->name ?? 'N/A' }} <span class="text-info">#{{ $item->sku }}</span></td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->amount, 2) }}</td>
                        <td>{{ $item->note }}</td>
                        <td class="text-center">
                            <a href="javascript:void(0);" onclick="editForm({{ $item->id }})" class="text-primary me-2"><i class="ri-edit-line"></i></a>
                            <a href="javascript:void(0);" onclick="deleteForm({{ $item->id }})" class="text-danger"><i class="ri-delete-bin-5-line"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-danger">No Data Available!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $damages->links() }}
        </div>
    </div>
</div>

{{-- Unified Modal for Add & Edit --}}
<div class="modal fade" id="damage_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">Add Damage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="damage_form" method="POST" action="">
                    @csrf
                    <div id="method_field"></div> {{-- Place for @method('PUT') --}}

                    <input type="hidden" name="id" id="damage_id">
                    <input type="hidden" name="sku" id="sku">

                    <div class="form-floating mb-3">
                        <input type="text" name="date" class="form-control" id="date" required>
                        <label>Date <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select select2" name="product_id" id="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product['id'] }}" data-sku="{{ $product['sku'] }}">{{ $product['name'] }} ({{ $product['total_stock'] }}pc)</option>
                            @endforeach
                        </select>
                        <label>Product <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="number" name="quantity" class="form-control" id="quantity" required min="1">
                        <label>Quantity <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea name="note" id="note" class="form-control" rows="4" style="height: 100px;"></textarea>
                        <label>Note</label>
                    </div>

                    <button type="submit" id="submit_btn" class="btn btn-success w-100">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('backEnd/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('backEnd/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('backEnd/assets/libs/select2/select2.min.js') }}"></script>

<script>
$(function() {
    // Init Select2
    $('.select2').select2({
        width: '100%',
        dropdownParent: $('#damage_modal')
    });

    // Init Flatpickr
    flatpickr("#date", { dateFormat: "d-m-Y", defaultDate: new Date() });

    // Sync SKU field
    $('#product_id').on('change', function() {
        $('#sku').val($(this).find(':selected').data('sku'));
    });

    // OPEN ADD MODAL
    window.openAddModal = function() {
        $('#damage_form')[0].reset();
        $('#damage_id').val('');
        $('#product_id').val('').trigger('change');

        $('#modal_title').text('Add Damage');
        $('#submit_btn').text('Save');
        $('#damage_form').attr('action', "{{ route('damage.store') }}");
        $('#method_field').empty(); // Standard POST

        $('#damage_modal').modal('show');
    };

    // OPEN EDIT MODAL
    window.editForm = function(id) {
        $.get("{{ route('get.damage.data') }}", {id: id}, function(data) {
            $('#damage_id').val(data.id);
            $('#quantity').val(data.quantity);
            $('#note').val(data.note);
            $('#product_id').val(data.product_id).trigger('change');
            $('#sku').val(data.sku);

            // Convert date YYYY-MM-DD → DD-MM-YYYY
            let dateParts = data.date.split('-'); // ['2026','02','10']
            let formattedDate = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;

            // Set date using existing flatpickr instance
            let dateInput = document.getElementById('date');
            if(dateInput._flatpickr){
                dateInput._flatpickr.setDate(formattedDate, true, "d-m-Y");
            } else {
                $('#date').val(formattedDate); // fallback
            }

            $('#modal_title').text('Edit Damage');
            $('#submit_btn').text('Update');

            // Set URL to update route
            $('#damage_form').attr('action', "{{ route('damage.update') }}");

            // If your Laravel route is Route::put, uncomment the line below:
            // $('#method_field').html('<input type="hidden" name="_method" value="PUT">');

            $('#damage_modal').modal('show');
        }).fail(function(xhr, status, error) {
            console.error("Error: " + error);
            console.error("Status: " + status);
            console.dir(xhr);
            alert("Request Failed: " + xhr.status + " " + error);
        });
    };

    // DELETE ACTION
    window.deleteForm = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete route (standard GET or POST)
                window.location.href = "{{ route('damage.delete') }}?id=" + id;
            }
        });
    };
});
</script>
@endsection
