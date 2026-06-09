@php
    $hasVariant = old('has_variant', $hasVariant ?? false);
    $productId = $productId ?? null;
    $selectedAttributeIds = $selectedAttributeIds ?? [];
    $selectedItems = $selectedItems ?? [];
    $existingImages = $existingImages ?? [];
    $attributes = $attributes ?? collect();
@endphp

<!-- Product Variants -->
<div class="card custom-card">
    <div class="card-header justify-content-between">
        <div class="card-title">Product Variants Preview</div>
        <div class="custom-toggle-switch d-flex align-items-center">
            <input type="hidden" name="has_variant" value="0">
            <input id="hasVariantToggle" name="has_variant" type="checkbox" value="1" {{ $hasVariant ? 'checked' : '' }}>
            <label for="hasVariantToggle" class="label-primary"></label>
        </div>
    </div>

    <div class="card-body" id="variant_card_body" style="{{ $hasVariant ? '' : 'display:none;' }}">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Attributes</label>
                <select name="attribute_id[]" id="attribute_id" class="form-select searchable" multiple>
                    @foreach($attributes as $attribute)
                        <option value="{{ $attribute->id }}" {{ in_array($attribute->id, $selectedAttributeIds) ? 'selected' : '' }}>
                            {{ $attribute->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="attribute_items_container" class="row"></div>
        <div id="variant_combinations_container"></div>
    </div>
</div>

@push('js')
<script>
    $(function() {
        const productId = @json($productId);
        const attributeItemsUrl = @json(route('attributes.getItems'));
        const variantCombinationsUrl = @json(route('products.getItemsCombo'));

        function initChoices() {
            $('.attribute-item:not([data-choices-initialized])').each(function() {
                new Choices(this, { removeItemButton: true, searchEnabled: true, placeholderValue: 'Select Items' });
                $(this).data('choices-initialized', true);
            });
        }

        function loadAttributeItems(shouldLoadVariants = true) {
            const selected = $('#attribute_id').val();
            if (!selected?.length) {
                $('#attribute_items_container, #variant_combinations_container').html('');
                return;
            }

            $.get(attributeItemsUrl, { attribute_ids: selected, ...(productId && { product_id: productId }) }, function(html) {
                $('#attribute_items_container').html(html);
                initChoices();
                if (shouldLoadVariants) {
                    setTimeout(() => { syncImageUploadFields(); loadVariantCombinations(); }, 150);
                }
            });
        }

        function loadVariantCombinations() {
            const attrs = [];
            $('.attribute-item').each(function() {
                const val = $(this).val();
                if (val?.length) attrs.push({ id: $(this).data('id'), items: val });
            });

            if (!attrs.length) return $('#variant_combinations_container').html('');

            const currentVariants = $('#variant_combinations_container table tbody tr').map(function() {
                const $tr = $(this);
                const itemIdsStr = $tr.find('input[name="variants[attribute_item_ids][]"]').val();
                return itemIdsStr ? {
                    id: $tr.find('input[name="variants[id][]"]').val() || null,
                    items: itemIdsStr.split(',').map(Number),
                    variant_sku: $tr.find('input[name="variants[variant_sku][]"]').val(),
                    variant_price: $tr.find('input[name="variants[variant_price][]"]').val(),
                    purchase_cost: $tr.find('input[name="variants[purchase_cost][]"]').val(),
                    variant_stock: $tr.find('input[name="variants[variant_stock][]"]').val()
                } : null;
            }).get().filter(Boolean);

            $.get(variantCombinationsUrl, {
                sku: $('#sku').val(),
                sale_price: $('#sale_price').val(),
                purchase_price: $('#purchase_price').val(),
                total_stock: $('#total_stock').val(),
                attributes: attrs,
                product_id: productId,
                current_variants: currentVariants
            }, html => $('#variant_combinations_container').html(html));
        }

        function syncImageUploadFields() {
            $('.attribute-item').each(function() {
                if (!$(this).data('has-image')) return;

                const attrId = $(this).data('id');
                const container = $(`.image-upload-container[data-attr-id="${attrId}"] .image-upload-fields`);
                const selectedIds = ($(this).val() || []).map(String);

                container.find('.single-upload-field').each(function() {
                    const fieldItemId = String($(this).data('item-id'));
                    if (!$(this).data('existing') && !selectedIds.includes(fieldItemId)) $(this).remove();
                });

                selectedIds.forEach(itemId => {
                    if (container.find(`[data-item-id="${itemId}"]`).length) return;
                    const itemName = $(`.attribute-item[data-id="${attrId}"] option[value="${itemId}"]`).text();
                    container.append(`
                        <div class="d-flex align-items-center mb-2 single-upload-field" data-item-id="${itemId}">
                            <span class="me-2 fw-semibold text-secondary attribute-image-label">${itemName}</span>
                            <input type="file" name="attribute_images[${attrId}][${itemId}]" class="form-control form-control-sm attribute-image-input" accept="image/*">
                            <img src="" alt="${itemName}" class="attribute-image-preview ms-2 d-none">
                        </div>
                    `);
                });
            });
        }

        function setAttributeImagePreview(fileInput) {
            const $field = $(fileInput).closest('.single-upload-field');
            let $img = $field.find('.attribute-image-preview');
            if (!$img.length) {
                $img = $('<img class="attribute-image-preview ms-2 d-none" alt="Preview">');
                $field.append($img);
            }

            const file = fileInput.files?.[0];
            const previousUrl = $img.data('object-url');
            if (previousUrl) URL.revokeObjectURL(previousUrl);

            if (!file) return $img.attr('src', '').addClass('d-none').removeData('object-url');

            const url = URL.createObjectURL(file);
            $img.attr('src', url).removeClass('d-none').data('object-url', url);
        }

        $('#hasVariantToggle').on('change', function() {
            $('#variant_card_body').toggle(this.checked);
        });

        $('#attribute_id').on('change', () => {
            loadAttributeItems(true);
            $('#variant_combinations_container').html('');
        });

        $(document)
            .on('change', '.attribute-item', function() {
                syncImageUploadFields();
                loadVariantCombinations();
            })
            .on('change', '.attribute-image-input', function() {
                setAttributeImagePreview(this);
            })
            .on('keyup change', '#sku, #sale_price, #purchase_price, #total_stock', loadVariantCombinations)
            .on('click', '.remove-variant', function() {
                $(this).closest('tr').remove();
            })
            .on('click', '.remove-spec-row', function() {
                $(this).closest('tr').remove();
            });

        $('#add_spec_row').on('click', () => {
            $('#specifications_table tbody').append(`<tr>
                <td><input type="text" name="spec_keys[]" class="form-control form-control-sm" placeholder="Name"></td>
                <td><input type="text" name="spec_values[]" class="form-control form-control-sm" placeholder="Value"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-spec-row"><i class="ri-delete-bin-line"></i></button>
                </td>
            </tr>`);
        });

        new Choices('#attribute_id', { removeItemButton: true, searchEnabled: true });
        loadAttributeItems(true);
    });
</script>
@endpush
