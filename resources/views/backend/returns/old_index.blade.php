@extends('backEnd.layouts.master')
@section('title')
    Returns
@endsection

@section('css')
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endsection

@section('body')
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Returns List
            </div>
            <div class="prism-toggle">
                {{--<button type="button" class="btn btn-sm btn-success-gradient" data-bs-toggle="modal"
                        data-bs-target="#add_modal">Add Return
                </button>--}}
                <form action="{{route('sale.return.store')}}" method="post">
                    @csrf
                    <div class="d-flex">
                        <select class="form-select form-select-sm me-1" name="store_id" id="store_id" required>
                            <option value="">--Select Store--</option>
                            @foreach($stores as $key => $store)
                                <option value="{{$key}}">{{$store}}</option>
                            @endforeach
                        </select>
                        <input type="text" name="query" id="sale_id" class="form-control form-control-sm" placeholder="Scan Barcode" required>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th width="1%">SL</th>
                        <th width="10%">Date</th>
                        <th>Sale ID & Order Invoice ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        {{--<th>Note</th>--}}
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i = 1)
                    @if (count($data) > 0)
                        @foreach ($data as $key => $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                    {{ date('d M Y', strtotime($item->date)) }}
                                </td>
                                <td>
                                    <a href="{{route('sale.index','?sale_id='.$item->get_sale->id)}}">
                                        <span class="text-info">#{{$item->get_sale->id}}</span><br>
                                        <span class="text-info">{{$item->get_sale->invoice_no}}</span>
                                    </a>
                                </td>
                                <td>
                                    @foreach($item->get_sale->sale_items as $key => $product)
                                        @if($key!=0)
                                            <br>
                                        @endif
                                        {{ $product->get_product->name }} <span class="text-info">#{{ $product->sku  }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($item->get_sale->sale_items as $key => $product)
                                        @if($key!=0)
                                            <br>
                                        @endif
                                        {{ $product->quantity }}
                                    @endforeach
                                </td>
                                <td>
                                    <div class="hstack gap-2 flex-wrap">
                                        <a href="javascript:void(0);" onclick="deleteForm({{ $item->id }})" class="text-danger fs-14 lh-1"><i
                                                class="ri-delete-bin-5-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-danger text-center">No Data Available!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- add Modal -->
    <div class="modal fade modal-md" id="add_modal" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Returns</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="store_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mb-4">
                                    <select class="form-select select2" name="product_id" id="product_id" required>
                                        <option>Select Product</option>
                                        {{--@foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                        @endforeach--}}
                                    </select>
                                    <label for="product_id">Product <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg product_id_error"></span>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" name="date" class="form-control" required id="date">
                                    <label for="name">Date <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg date_error"></span>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" type="number" name="return_quantity">
                                    <label for="note">Quantity</label>
                                    <span class="text-danger error-msg note_error"></span>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="note" rows="4"></textarea>
                                    <label for="note">Note</label>
                                    <span class="text-danger error-msg note_error"></span>
                                </div>

                            </div>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Returns</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="edit-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id" id="id_e">
                                <div class="form-floating mb-4">
                                    <select class="form-select" name="product_id" id="product_id_e">

                                    </select>
                                    <label for="product_id">Product <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg product_id_e_uerror"></span>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" name="date" class="form-control" required id="date_e">
                                    <label for="name">Date <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg date_uerror"></span>
                                </div>

                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="note" id="note_e" rows="4"></textarea>
                                    <label for="note">Note <span class="text-danger">*</span></label>
                                    <span class="text-danger error-msg note_eurror"></span>
                                </div>


                            </div>
                        </div>

                        <button type="submit" class="btn btn-success m-1">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- Sweetalerts JS -->
    <script src="{{ asset('backEnd/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('backEnd/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>


    <script type="text/javascript">
        $(function () {
            $(document).on('change', '#store_id', function () {
                var store_id = $(this).val();
                $.ajax({
                    url: "{{ route('get.product') }}",
                    type: "GET",
                    data: {
                        store_id: store_id
                    },
                    success: function (data) {
                        var html = '<option value="">Select Product</option>';
                        $.each(data, function (key, v) {
                            html += '<option value="' + v.p_id + '">' + v.name +
                                '</option>';
                        });

                        $('#product_id').html(html);
                    }
                });

            });
        });
    </script>


    <script type="text/javascript">
        $(function () {
            $(document).on('change', '#store_id_e', function () {
                var store_id = $(this).val();
                $.ajax({
                    url: "{{ route('get.product') }}",
                    type: "GET",
                    data: {
                        store_id: store_id
                    },
                    success: function (data) {
                        var html = '<option value="">Select Product</option>';
                        $.each(data, function (key, v) {
                            html += '<option value="' + v.p_id + '">' + v.name +
                                '</option>';
                        });

                        $('#product_id_e').html(html);
                    }
                });

            });
        });
    </script>


    <script>
        function editForm(id) {
            $.ajax({
                url: "{{ route('get.damage.data') }}",
                type: "GET",
                data: {
                    id: id
                },

                success: function (data) {
                    $('#id_e').val(data.id);
                    $('#date_e').val(data.date);
                    $('#note_e').val(data.note);

                    var html = '<option value="">Select Product</option>';
                    if (data.get_products.length > 0) {
                        $.each(data.get_products, function (key, item) {
                            html += '<option value="' + item.p_id + '">' + item.name + '</option>';
                        });
                    }

                    $('#product_id_e').html(html);
                    $('#product_id_e').val(data.product_id);
                    $('#edit_modal').modal('show');
                },
                error: function () {
                    alert("Nothing Data");
                }
            });
        }
    </script>





    <script>
        $(document).ready(function () {
            $(document).on('change', '#store_id', function () {
                $('#sale_id').focus();
            });

            flatpickr("#date", {
                dateFormat: "d-m-Y",
                defaultDate: new Date()
            });

            flatpickr("#date_e", {
                dateFormat: "d-m-Y",
                defaultDate: new Date()
            });

            $("#add_modal").on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('sale.return.store') }}",
                    type: "post",
                    data: $("#store_form").serialize(),
                    beforeSend: function () {
                        $(document).find('span.error-msg').text('');
                    },
                    success: function (data) {
                        if (data.res_status == 0) {
                            $.each(data.error, function (prefix, val) {
                                $('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            $('#add_modal').modal('hide');
                            $("#store_form")[0].reset();
                            $("#mytable").load(location.href + ' #mytable>*', "");
                            Swal.fire({
                                icon: 'success',
                                title: 'Returns added successfully',
                                showConfirmButton: false,
                                timer: 1000
                            })
                        }
                    }
                })
            });


            // $(document).on('click', '.edit-btn', function () {
            //     $('#id_e').val($(this).data('id'));
            //     $('#store_id_e').val($(this).data('store_id'));
            //     $('#product_id_e').val($(this).data('product_id'));
            //     $('#date_e').val($(this).data('date'));
            //     $('#note_e').val($(this).data('note'));
            //     $('#edit_modal').modal('show');
            // });

            $("#edit-form").on('submit', function (e) {
                e.preventDefault();
                var id = $('#id_e').val();
                $.ajax({
                    url: "{{ route('damage.update') }}",
                    type: "post",
                    data: $("#edit-form").serialize(),
                    beforeSend: function () {
                        $(document).find('span.uerror-msg').text('');
                    },
                    success: function (data) {
                        if (data.res_status == 0) {
                            $.each(data.error, function (prefix, val) {
                                $('span.' + prefix + '_uerror').text(val[0]);
                            });
                        } else {
                            $('#edit_modal').modal('hide');
                            $("#mytable").load(location.href + ' #mytable>*', "");
                            Swal.fire({
                                icon: 'success',
                                title: 'Returns update successfully',
                                showConfirmButton: false,
                                timer: 1000
                            })
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            });
        });

        function deleteForm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('damage.delete') }}",
                        type: "GET",
                        data: {
                            id: id
                        },
                        success: function (data) {
                            $("#mytable").load(location.href + ' #mytable>*', "");
                            Swal.fire({
                                icon: 'success',
                                title: 'Returns Delete Successfully',
                                showConfirmButton: false,
                                timer: 1000
                            })
                        },
                        error: function () {
                            console.log(data);
                        }
                    });
                }
            })
        }


    </script>
@endsection
