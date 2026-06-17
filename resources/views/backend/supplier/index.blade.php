<x-backend-layout title="Supplier Management">
    @push('css')
        <!-- Sweetalerts CSS -->
        <link href="{{ asset('backend/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/libs/select2/select2.min.css') }}" rel="stylesheet" />
    @endpush

    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Supplier Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Supplier List
                    </div>
                    <div class="prism-toggle">
                        <button type="button" class="btn btn-sm btn-success-gradient" data-bs-toggle="modal"
                            data-bs-target="#add_modal">Add Suppler</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th>Transaction</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @if (count($suppliers) > 0)
                                    @foreach ($suppliers as $key => $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>{{ number_format($item->balance, 2) }}</td>
                                            <td>
                                                <a href="{{ route('supplier.report', $item->id) }}"
                                                    class="badge bg-primary">View</a>
                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="hstack gap-2 flex-wrap">
                                                    <a href="javascript:void(0);" class="text-info fs-14 lh-1 edit-btn"
                                                        data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                        data-email="{{ $item->email }}" data-status="{{ $item->status }}"
                                                        data-phone="{{ $item->phone }}" data-balance="{{ $item->balance }}">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="deleteForm({{ $item->id }})"
                                                        class="text-danger fs-14 lh-1"><i class="ri-delete-bin-5-line"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-danger text-center">No Data Available!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $suppliers->links('backend.pagination.paginate') }}
                </div>
            </div>
        </div>
    </div>

    <!-- add Modal -->
    <div class="modal fade" id="add_modal" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="supplier_form">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" placeholder="name">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <span class="text-danger error-msg name_error"></span>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="email" placeholder="Email">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <span class="text-danger error-msg email_error"></span>
                        </div>


                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="phone" placeholder="Phone">
                            <label for="floatingInput">Phone<span class="text-danger">*</span></label>
                            <span class="text-danger error-msg phone_error"></span>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="balance" placeholder="Balance"
                                value="0" required>
                            <label for="balance">Balance</label>
                            <span class="text-danger error-msg balance_error"></span>
                        </div>

                        <div class="form-floating mb-4">
                            <select class="form-select" name="status" id="status"
                                aria-label="Floating label select example">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <span class="text-danger error-msg status_error"></span>
                        </div>

                        <button type="submit" class="btn btn-success m-1">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="edit-form">
                        @csrf
                        <input type="hidden" name="id" id="id_e">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" id="name_e"
                                placeholder="name">
                            <label for="name_e">Name <span class="text-danger">*</span></label>
                            <span class="text-danger uerror-msg name_uerror"></span>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="email" id="email_e"
                                placeholder="Contact Person">
                            <label for="email_e">Email <span class="text-danger">*</span></label>
                            <span class="text-danger error-msg email_uerror"></span>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="phone" id="phone_e"
                                placeholder="Phone">
                            <label for="phone_e">Phone <span class="text-danger">*</span></label>
                            <span class="text-danger uerror-msg phone_uerror"></span>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="number" class="form-control" name="balance" id="balance_e"
                                placeholder="Balance" value="0" required>
                            <label for="balance_e">Balance </label>
                            <span class="text-danger uerror-msg balance_uerror"></span>
                        </div>

                        <div class="form-floating mb-4">
                            <select class="form-select" name="status" id="status_e"
                                aria-label="Floating label select example">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <label for="status_e">Status <span class="text-danger">*</span></label>
                            <span class="text-danger uerror-msg status_uerror"></span>
                        </div>

                        <button type="submit" class="btn btn-success m-1">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <!-- Sweetalerts JS -->
        <script src="{{ asset('backend/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('backend/libs/select2/select2.min.js') }}"></script>

        <script>
            $('.select2_add_modal').select2({
                dropdownParent: $("#add_modal"),
                placeholder: "--Select Store--"
            });

            $(document).ready(function() {
                $("#add_modal").on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('supplier.store') }}",
                        type: "post",
                        data: $("#supplier_form").serialize(),
                        beforeSend: function() {
                            $(document).find('span.error-msg').text('');
                        },
                        success: function(data) {
                            if (data.res_status == 0) {
                                $.each(data.error, function(prefix, val) {
                                    $('span.' + prefix + '_error').text(val[0]);
                                });
                            } else {
                                $('#add_modal').modal('hide');
                                $("#supplier_form")[0].reset();
                                $("#mytable").load(location.href + ' #mytable>*', "");
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supplier added successfully',
                                    showConfirmButton: false,
                                    timer: 1000
                                })
                            }
                        }
                    })
                });

                $(document).on('click', '.edit-btn', function() {
                    $('#id_e').val($(this).data('id'));
                    $('#name_e').val($(this).data('name'));
                    $('#email_e').val($(this).data('email'));
                    $('#phone_e').val($(this).data('phone'));
                    $('#balance_e').val(Number($(this).data('balance')).toFixed(2));
                    $('#status_e').val($(this).data('status'));
                    $('#edit_modal').modal('show');
                });

                $("#edit-form").on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#id_e').val();
                    $.ajax({
                        url: "{{ route('supplier.update') }}",
                        type: "post",
                        data: $("#edit-form").serialize(),
                        beforeSend: function() {
                            $(document).find('span.uerror-msg').text('');
                        },
                        success: function(data) {
                            if (data.res_status == 0) {
                                $.each(data.error, function(prefix, val) {
                                    $('span.' + prefix + '_uerror').text(val[0]);
                                });
                            } else {
                                $('#edit_modal').modal('hide');
                                $("#mytable").load(location.href + ' #mytable>*', "");
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supplier update successfully',
                                    showConfirmButton: false,
                                    timer: 1000
                                })
                            }
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    })
                });
            });

            function deleteForm(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to be delete this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('supplier.delete') }}",
                            type: "GET",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                $("#mytable").load(location.href + ' #mytable>*', "");
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supplier Delete Successfully',
                                    showConfirmButton: false,
                                    timer: 1000
                                })
                            },
                            error: function() {
                                alert("Nothing Data");
                            }
                        });
                    }
                })
            }
        </script>
    @endpush

</x-backend-layout>
