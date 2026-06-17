<x-backend-layout title="Stores Management">
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Return List
            </div>
            {{--<div class="prism-toggle">
                <a href="{{route('sale.create')}}" type="button" class="btn btn-sm btn-success-gradient">Add Sale</a>
            </div>--}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="mytable" class="table table-bordered text-nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Store</th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th class="text-wrap">Customer Info</th>
                        <th class="text-wrap">Product(s)</th>
                        <th>Amount</th>
                        <th>Profit</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($i=1)
                    @if(count($data)>0)
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    @if($item->error_msg)
                                        <i class="ti ti-exclamation-mark text-danger fw-bold" data-bs-toggle="tooltip" data-bs-title="{{$item->error_msg}}"
                                           data-bs-placement="top"></i>
                                        <br>
                                    @endif
                                    {{ $i++ }}
                                </td>
                                <td><span class="badge bg-dark-gradient">{{ $item->get_store->name }}</span></td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>
                                    {{ date('d-m-Y',strtotime($item->date)) }}<br>
                                    <small>{{ date('h:s:i A',strtotime($item->created_at)) }}</small>
                                </td>
                                <td class="text-wrap">
                                    <b>Name: </b>{{$item->customer_name ?? ''}} <br>
                                    <b>Phone: </b>{{$item->customer_phone ?? ''}} <br>
                                    <b>Address: </b>{{$item->customer_address ?? ''}}
                                </td>

                                <td class="text-wrap">
                                    @if(count($item->sale_items)>0)
                                        @foreach($item->sale_items as $key => $product)
                                            @if($key != 0)
                                                <br>
                                            @endif
                                            @if($product->is_exchange_product == 1 )
                                                <small class="badge bg-danger rounded-pill" style="padding: 0.15rem 0.25rem;">R</small>
                                            @endif
                                            <span class="text-danger">{{$product->quantity}}</span> x {{$product->get_product->name}}
                                            @if($product->attributes)
                                                <br>
                                                <span class="text-primary">{{$product->attributes}}</span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="text-danger fw-bold">{{$item->error_msg}}</span>
                                    @endif
                                </td>
                                <td>TK {{ number_format($item->sub_total, 2)}}</td>
                                <td>
                                    @if($item->status == 'delivered')
                                        @if($item->get_profit()>0)
                                            <span class="text-success">TK{{ number_format($item->get_profit(), 2)}}</span>
                                        @else
                                            <span class="text-danger">TK{{ number_format($item->get_profit(), 2)}}</span>
                                        @endif
                                    @elseif($item->status == 'returned')
                                        TK0
                                    @else
                                        <span class="text-muted text-light-emphasis">Est. Prof. TK{{ number_format($item->get_profit(), 2,'.','')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-danger">Return</span>
                                    @if($item->is_return_received == 1)
                                        <br>
                                        <span class="badge bg-success">Received</span>
                                        <br>
                                        <small>{{date('d-m-Y',strtotime($item->received_time))}}</small><br>
                                        <small>{{date('h:i:s A',strtotime($item->received_time))}}</small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-danger text-center">No Data Available!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            {{ $data->links('backend.pagination.paginate') }}
        </div>
    </div>
</x-backend-layout>

