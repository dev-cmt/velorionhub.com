@extends('backEnd.layouts.master')
@section('title')
    Purchase
@endsection
@section('css')

@endsection
@section('body')
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Purchase List
            </div>
            <div class="prism-toggle">
                <a href="{{ route('purchase.create') }}" type="button" class="btn btn-sm btn-success-gradient">Add Purchase</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        {{-- <th>Store</th> --}}
                        {{-- <th>Memo Number</th> --}}
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @if(count($purchases)>0)
                        @foreach ($purchases as $key => $item)
                            {{-- @dd($item->stores); --}}
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                    {{ date('d M Y',strtotime($item->date)) }}
                                </td>
                                {{-- <td>
                                    @if($item->stores)
                                        @foreach($item->stores as $store)
                                            @if($store)
                                                <span class="badge bg-dark-gradient">{{ $store }}</span><br/>
                                            @endif
                                        @endforeach
                                    @else
                                        <br>
                                    @endif
                                </td> --}}

                                <td>{{ $item->get_supplier->name }}</td>
                                <td>{{ $setting->currency_sign }} {{ number_format($item->sub_total,2,'.','') }}</td>
                                <td>{{ $setting->currency_sign }} {{ number_format($item->discount,2,'.','') }}</td>
                                <td>{{ $setting->currency_sign }} {{ number_format($item->due_amount,2,'.','') }}</td>
                                <td>
                                    @if($item->status == 0)
                                        <span class="badge bg-info">Ordered</span>
                                    @elseif($item->status == 1)
                                        <span class="badge bg-success">Received</span>
                                    @elseif($item->status == 2)
                                        <span class="badge bg-warning">Partial Receive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="hstack gap-2 flex-wrap justify-content-end">
                                        <a href="javascript:void(0);" class="text-info fs-14 lh-1 view_btn" data-id="{{$item->id}}">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if($item->status == 0)
                                        <a href="{{ route('purchase.edit', $item->id) }}" class="text-info fs-14 lh-1 edit-btn">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="deleteForm({{ $item->id }})" class="text-danger fs-14 lh-1">
                                            <i class="ri-delete-bin-5-line"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-danger text-center">No Data Available!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            {{$purchases->links('backEnd.includes.paginate')}}
        </div>
    </div>

    <!-- add Modal -->
    <div class="modal fade" id="view" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Purchase Item(s)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="purchase_item_view">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
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
                        url: "{{ route('purchase.delete') }}",
                        type: "GET",
                        data: {id: id},
                        success: function (data) {
                            $("#mytable").load(location.href + ' #mytable>*', "");
                            Swal.fire({
                                icon: 'success',
                                title: 'Purchase Delete Successfully',
                                showConfirmButton: false,
                                timer: 1000
                            })
                        },
                        error: function () {
                            alert("Nothing Data");
                        }
                    });
                }
            })
        }

        $(document).on('click', '.view_btn', function () {
            $.ajax({
                url: "{{ route('supplier.ajax.get.purchase.items') }}",
                type: "GET",
                data: {_token: `{{csrf_token()}}`,id: $(this).data('id')},
                success: function (data) {
                    $('#purchase_item_view').html(data);
                    $('#view').modal('show');
                }
            });
        });
    </script>
@endsection

