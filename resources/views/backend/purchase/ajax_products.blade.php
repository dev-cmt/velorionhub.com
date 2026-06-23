@if(count($product->get_product_attributes) > 0)
    <tr class="item-row-template product_row">
        <td colspan="4">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th></th>
                    <th width="69%">Name</th>
                    <th width="10%">Quantity</th>
                    <th width="10%">Cost</th>
                    <th width="10%">Sell Price</th>
                </tr>
                </thead>
                <tbody>
                <input type="hidden" name="product_id[]" value="{{ $product->id }}">
                <input type="hidden" value="{{ $product->has_variant }}" id="has_variant"/>
                @foreach ($product->get_product_attributes as $key => $item)
                    <tr>
                        <td>
                            <i style="cursor:pointer;" class="remove_btn bi bi-x-circle btn-lg"></i>
                        </td>
                        <td width="69%" class="p-1">
                            <input type="hidden" name="sku[{{$product->id}}][]" value="{{ $item->variant_sku }}">
                            <label class="col-form-label p-0"><small class="text-info">#{{ $item->variant_sku }}</small> <br>{{ $product->name }} (<span>{{ strtoupper($item->getAttributeNames()) }}</span>)</label>
                        </td>
                        <td class="qty_section p-1" width="10%">
                            <input type="number" class="form-control form-control-sm quantity qty_{{$product->id}}" data-id="{{$product->id}}" name="quantity[{{$product->id}}][]" id="quantity_{{$key}}" value="1" required>
                        </td>
                        <input type="hidden" class="sub_total_qty" name="sub_total_qty[{{$product->id}}]">
                        <td class="purchase_cost_section p-1" width="10%">
                            <input type="number" class="form-control form-control-sm purchase_cost prch_cost_{{$product->id}}" data-id="{{$product->id}}" name="purchase_cost[{{$product->id}}][]" id="purchase_cost_{{$key}}" value="{{ number_format($item->variant_purchase_cost, 2, '.', '') }}" min="0" step="0.01" required>
                            <input type="hidden" class="put_sub_toot" name="put_sub_toot[{{$product->id}}][]">
                        </td>
                        <input type="hidden" class="sub_total_purchase_cost">
                        <td class="sell_price_section p-1" width="10%">
                            <input type="number" class="form-control form-control-sm sell_price sell_prc_{{$product->id}}" data-id="{{$product->id}}" name="sell_price[{{$product->id}}][]" id="sell_price_{{$key}}" value="{{ isset($item->variant_price) ? number_format($item->variant_price, 2, '.', '') : 0 }}" min="0" step="0.01" required>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
        <input type="hidden" class="sub_total_sell_price">
        {{-- <td><input type="number" class="form-control form-control-sm discount" name="discount[{{$product->id}}][]" value="0"></td>
         <td><input type="number" class="form-control form-control-sm tax" name="tax[{{$product->id}}][]" value="0"></td>--}}
        <td><input type="number" class="form-control form-control-sm total" name="total[{{$product->id}}]" value="0" step="0.01" required></td>
        <td>
            <i style="cursor:pointer;" class="remove_btn bi bi-x-circle btn-lg"></i>
        </td>
    </tr>
@else
    <tr class="item-row-template product_row">
        <td>
            <input type="hidden" name="product_id[]" value="{{ $product->id }}">
            <input type="hidden" name="sku[{{ $product->id }}][]" value="{{ $product->sku }}">
            <small class="text-info">#{{ $product->sku }}</small> <br>{{ $product->name }}
        </td>
        <td class="qty_section">
            <input type="number" class="form-control form-control-sm quantity" name="quantity[{{$product->id}}][]" id="quantity" value="1" required>
        </td>
        <input type="hidden" class="sub_total_qty" name="sub_total_qty[{{$product->id}}]">
        <td class="purchase_cost_section">
            <input type="number" class="form-control form-control-sm purchase_cost" name="purchase_cost[{{$product->id}}][]" id="unit_cost" value="{{ sprintf('%.2f', $product->purchase_price ?? 0) }}" min="0" step="0.01" required>
            <input type="hidden" class="put_sub_toot" name="put_sub_toot[{{$product->id}}][]">
        </td>
        <input type="hidden" class="sub_total_purchase_cost">
        <td class="sell_price_section">
            <input type="number" class="form-control form-control-sm sell_price" name="sell_price[{{$product->id}}][]" value="{{ sprintf('%.2f', $product->sale_price ?? 0) }}" min="0" step="0.01" required>
        </td>
        <input type="hidden" class="sub_total_sell_price">
        {{--<td><input type="number" class="form-control form-control-sm discount" name="discount[{{$product->id}}][]" value="0"></td>
        <td><input type="number" class="form-control form-control-sm tax" name="tax[{{$product->id}}][]" value="0"></td>--}}
        <td><input type="number" class="form-control form-control-sm total" name="total[{{$product->id}}]" step="0.01" value="0" required></td>
        <td>
            <i style="cursor:pointer;" class="remove_btn bi bi-x-circle btn-lg"></i>
        </td>
    </tr>
@endif
