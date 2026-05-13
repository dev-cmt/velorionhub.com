<!-- backend.products.partisals._variant_table.blade.php -->
@php
    $variants = $variants ?? [
        ['name' => '', 'variant_sku' => '', 'variant_price' => 0, 'purchase_cost' => 0, 'variant_stock' => 0]
    ];
@endphp

@if(count($variants))
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Variant</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Purchase Price</th>
            <th>Stock</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($variants as $variant)
        <tr>
            <td  class="text-nowrap"> {{ $variant['name'] }}</td>
            <td><input type="text" name="variants[variant_sku][]" class="form-control form-control-sm" value="{{ $variant['variant_sku'] }}"></td>
            <td><input type="number" name="variants[variant_price][]" class="form-control form-control-sm" value="{{ $variant['variant_price'] }}"></td>
            <td><input type="number" name="variants[purchase_cost][]" class="form-control form-control-sm" value="{{ $variant['purchase_cost'] }}"></td>
            <td><input type="number" name="variants[variant_stock][]" class="form-control form-control-sm" value="{{ $variant['variant_stock'] }}"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-variant">X</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
