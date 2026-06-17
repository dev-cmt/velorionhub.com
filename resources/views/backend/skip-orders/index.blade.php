<x-backend-layout title="Stores Management">

    @push('css')
        <style>
            @media (max-width: 576px) {
                .form-inline .form-control {
                    display: inline-block;
                    width: auto;
                    vertical-align: middle;
                }
            }
        </style>
        <link rel="stylesheet" href="{{ asset('/') }}backend/vendor/datetimepicker/bootstrap-datetimepicker.min.css">
    @endpush

    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Incomplete Orders</h5>
        </div>
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 1%">SL.</th>
                        <th style="width: 10%">Date</th>
                        <th style="width: 20%">Customer Info</th>
                        <th style="width: 60%">Products</th>
                        <th style="width: 7%">Total</th>
                        <th>Note</th>
                        <th style="width: 2%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i = 1)
                    @if ($data->count() > 0)
                        @foreach ($data as $item)
                            <tr id="tr_{{ $item->id }}">

                                <td>{{ $i++ }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $item->get_store->name }}</span> <br>
                                    {{ date('d M, Y', strtotime($item->created_at)) }}<br>
                                    {{ date('h:i:s A', strtotime($item->created_at)) }}
                                </td>

                                <td>
                                    <span><strong>Name: </strong>{{ $item->customer_name }}</span>
                                    <br>
                                    <a href="tel:{{ $item->customer_phone }}"><span><strong>Phone:
                                            </strong>{{ $item->customer_phone }}</span>
                                    </a>
                                    <br>
                                    <span><strong>Address:
                                        </strong>{{ $item->customer_address }}</span>
                                </td>

                                <td>
                                    <?php
                                    $abandonedItems = json_decode($item->abandoned_item, true);
                                    ?>

                                    @foreach ($abandonedItems as $key => $abandoned_item)
                                        <?php
                                        $product = \App\Models\Product::find($abandoned_item['product_id']);
                                        ?>

                                        @if ($product)
                                            <span class="text-danger fw-bold">{{ $abandoned_item['qty'] }}</span> x
                                            {{ $product->name }} <br>

                                            @if (!empty($abandoned_item['attributes']) && is_array($abandoned_item['attributes']))
                                                <small class="fw-bold text-primary">
                                                    @foreach ($abandoned_item['attributes'] as $variant => $variant_item)
                                                        {{ $variant }}: {{ $variant_item }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </small>
                                                <br>
                                            @endif
                                        @endif
                                    @endforeach
                                </td>

                                <td>
                                    {{ number_format($item->total, 2, '.', '') }}
                                </td>
                                <td>
                                    <i class="fa fa-edit edit-note" data-note="{{ $item->note }}"
                                        data-id="{{ $item->id }}"></i>
                                    {{ $item->note }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('incomplete.order.create', $item->id) }}" title="Create Order"
                                        class="badge bg-info w-100 mb-1">
                                        Create Order
                                    </a>
                                    <a href="{{ route('incomplete.order.delete', $item->id) }}" title="Delete Order"
                                        class="badge bg-danger w-100 mb-1"
                                        onclick="return confirm('Are you sure to delete this?')">
                                        Delete
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12" class="text-center text-danger font-weight-bold">No Data Found!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="mt-3">
                {{ $data->links('backend.pagination.paginate') }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="note_modal" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="note_modal_modalTitle">Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('incomplete.order.note.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id_e">
                        <div class="form-group">
                            <textarea name="note" id="note" class="form-control mb-2"></textarea>
                            <input type="submit" class="btn btn-success" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).on('click', '.edit-note', function() {
                $('#id_e').val($(this).data('id'));
                $('#note').text($(this).data('note'));
                $('#note_modal').modal('show')
            });
        </script>
    @endpush
</x-backend-layout>
