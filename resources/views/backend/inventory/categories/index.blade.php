<x-backend-layout title="Category">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Categories</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Categories List</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="ri-add-line me-1"></i>Add Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Image</th>
                                    <th>Category Name</th>
                                    <th>Parent</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $renderCategory = function($category, $level = 0) use (&$i, &$renderCategory, $data) {
                                        $padding = $level * 20;
                                        $parentName = $category->parent ? $category->parent->name : '-';
                                @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset($category->image) }}" width="50" class="img-thumbnail">
                                                @else
                                                    <span class="badge bg-secondary">No Image</span>
                                                @endif
                                            </td>
                                            <td style="padding-left: {{ $padding }}px;">
                                                {{ $level > 0 ? str_repeat('— ', $level) . '↳ ' : '' }}{{ $category->name }}
                                            </td>
                                            <td>{{ $parentName }}</td>
                                            <td>
                                                <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-light edit_cat_btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editCategoryModal"
                                                        data-id="{{ $category->id }}"
                                                        data-name="{{ $category->name }}"
                                                        data-status="{{ $category->status }}"
                                                        data-is_menu="{{ $category->is_menu }}"
                                                        data-is_home="{{ $category->is_home }}"
                                                        data-is_section="{{ $category->is_section }}"
                                                        data-is_footer="{{ $category->is_footer }}"
                                                        data-parent="{{ $category->parent_id ?? '' }}"
                                                        data-image="{{ $category->image ?? '' }}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light" onclick="return confirm('Are you sure?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                @php
                                        foreach($data->where('parent_id', $category->id) as $child) {
                                            $renderCategory($child, $level + 1);
                                        }
                                    };
                                @endphp

                                @foreach($data->where('parent_id', null) as $category)
                                    @php $renderCategory($category); @endphp
                                @endforeach

                                @if($data->where('parent_id', null)->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No categories found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category Image</label>
                            <div class="d-flex align-items-center gap-3">
                                <label for="create_category_image" class="image-preview-box mb-0" title="Click to upload">
                                    <img id="createCategoryImagePreview" src="" alt="" style="display:none;">
                                    <i class="ri-image-add-line" id="createCategoryImageIcon"></i>
                                    <div class="delete-overlay">
                                        <button type="button" class="btn-delete-small" id="createCategoryImageRemove" title="Remove">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </label>
                                <div class="flex-grow-1">
                                    <input type="file" name="image" id="create_category_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Recommended: square image.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="parent_cat_group_add">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" id="parent_id_add" class="form-control select2">
                                <option value="">-- Select --</option>
                                @php
                                    $renderParentOptions = function($categories, $parentId = null, $level = 0) use (&$renderParentOptions) {
                                        $html = '';

                                        foreach ($categories->where('parent_id', $parentId) as $cat) {
                                            $prefix = $level > 0 ? str_repeat('— ', $level) . '↳ ' : '';
                                            $html .= '<option value="' . $cat->id . '">' . $prefix . e($cat->name) . '</option>';
                                            $html .= $renderParentOptions($categories, $cat->id, $level + 1);
                                        }

                                        return $html;
                                    };
                                @endphp
                                {!! $renderParentOptions($data) !!}
                            </select>
                            <small class="text-muted">Leave empty to create a root category.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="border rounded p-3 mb-3 bg-light">
                            <label class="form-label fw-semibold mb-2">Display Placement</label>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_menu" id="is_menu_add" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_menu_add">Show in Menu</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_home" id="is_home_add" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_home_add">Show on Home</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_section" id="is_section_add" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_section_add">Show as Section</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_footer" id="is_footer_add" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_footer_add">Show in Footer</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categories.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category Image</label>
                            <div class="d-flex align-items-center gap-3">
                                <label for="edit_category_image" class="image-preview-box mb-0" title="Click to upload">
                                    <img id="editCategoryImagePreview" src="" alt="" style="display:none;">
                                    <i class="ri-image-add-line" id="editCategoryImageIcon"></i>
                                    <div class="delete-overlay">
                                        <button type="button" class="btn-delete-small" id="editCategoryImageRemove" title="Remove">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </label>
                                <div class="flex-grow-1">
                                    <input type="file" name="image" id="edit_category_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Upload a new image to replace current.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="parent_cat_group_edit">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" id="parent_id_edit" class="form-control select2">
                                <option value="">-- Select --</option>
                            </select>
                            <small class="text-muted">Leave empty to keep it as root category.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="border rounded p-3 mb-3 bg-light">
                            <label class="form-label fw-semibold mb-2">Display Placement</label>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_menu" id="is_menu_edit" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_menu_edit">Show in Menu</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_home" id="is_home_edit" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_home_edit">Show on Home</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_section" id="is_section_edit" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_section_edit">Show as Section</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_footer" id="is_footer_edit" class="form-check-input" value="1">
                                <label class="form-check-label" for="is_footer_edit">Show in Footer</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            const categories = @json($categoriesForJs);

            function setPreview($img, $icon, src) {
                if (src) {
                    $img.attr('src', src).show();
                    $icon.hide();
                } else {
                    $img.attr('src', '').hide();
                    $icon.show();
                }
            }

            function bindFilePreview(inputSelector, imgSelector, iconSelector) {
                $(document).on('change', inputSelector, function() {
                    const file = this.files && this.files[0] ? this.files[0] : null;
                    if (!file) {
                        return;
                    }

                    const url = URL.createObjectURL(file);
                    setPreview($(imgSelector), $(iconSelector), url);
                });
            }

            bindFilePreview('#create_category_image', '#createCategoryImagePreview', '#createCategoryImageIcon');
            bindFilePreview('#edit_category_image', '#editCategoryImagePreview', '#editCategoryImageIcon');

            $('#createCategoryImageRemove').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#create_category_image').val('');
                setPreview($('#createCategoryImagePreview'), $('#createCategoryImageIcon'), null);
            });

            $('#editCategoryImageRemove').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#edit_category_image').val('');
                const existing = $('#editCategoryImagePreview').data('existing') || '';
                setPreview($('#editCategoryImagePreview'), $('#editCategoryImageIcon'), existing);
            });

            function initParentSelects() {
                if (!$.fn.select2) {
                    return;
                }

                ['#parent_id_add', '#parent_id_edit'].forEach(selector => {
                    const $select = $(selector);

                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('destroy');
                    }

                    $select.select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: '-- Select --',
                        dropdownParent: $select.closest('.modal')
                    });
                });
            }

            function getDescendantIds(categoryId, allCategories) {
                const descendants = [];
                const stack = [Number(categoryId)];

                while (stack.length > 0) {
                    const currentId = stack.pop();
                    const children = allCategories.filter(cat => Number(cat.parent_id) === Number(currentId));

                    children.forEach(child => {
                        descendants.push(Number(child.id));
                        stack.push(Number(child.id));
                    });
                }

                return descendants;
            }

            function buildParentOptions(currentId = null, selectedParentId = null) {
                const excludedIds = [];

                if (currentId !== null) {
                    excludedIds.push(Number(currentId), ...getDescendantIds(currentId, categories));
                }

                function renderOptions(parentId = null, level = 0) {
                    let html = '';
                    const siblings = categories
                        .filter(cat => (cat.parent_id === null && parentId === null) || Number(cat.parent_id) === Number(parentId))
                        .sort((a, b) => String(a.name).localeCompare(String(b.name)));

                    siblings.forEach(cat => {
                        if (excludedIds.includes(Number(cat.id))) {
                            return;
                        }

                        const selected = Number(cat.id) === Number(selectedParentId) ? 'selected' : '';
                        const prefix = level > 0 ? '&nbsp;&nbsp;&nbsp;&nbsp;'.repeat(level) + '↳ ' : '';
                        html += `<option value="${cat.id}" ${selected}>${prefix}${cat.name}</option>`;
                        html += renderOptions(cat.id, level + 1);
                    });

                    return html;
                }

                return '<option value="">-- Select --</option>' + renderOptions();
            }

            $('#parent_id_add').html(buildParentOptions());
            initParentSelects();

            // Edit category handler
            $('.edit_cat_btn').on('click', function() {
                const $this = $(this);
                const data = $this.data();
                const currentId = data.id;

                $('#edit_id').val(currentId);
                $('#edit_name').val(data.name);
                $('#edit_status').val(data.status);
                $('#is_menu_edit').prop('checked', data.is_menu == 1 || data.is_menu === true);
                $('#is_home_edit').prop('checked', data.is_home == 1 || data.is_home === true);
                $('#is_section_edit').prop('checked', data.is_section == 1 || data.is_section === true);
                $('#is_footer_edit').prop('checked', data.is_footer == 1 || data.is_footer === true);

                const selectedParent = data.parent ? Number(data.parent) : null;
                $('#parent_id_edit').html(buildParentOptions(currentId, selectedParent));
                initParentSelects();
                $('#parent_id_edit').val(selectedParent).trigger('change');

                // Set current image in preview box (and keep it as "existing" for remove)
                const existingSrc = data.image ? `{{ asset('') }}${data.image}` : '';
                $('#editCategoryImagePreview').data('existing', existingSrc);
                setPreview($('#editCategoryImagePreview'), $('#editCategoryImageIcon'), existingSrc);
                $('#edit_category_image').val('');
            });

            // Reset modals when closed
            $('#createCategoryModal, #editCategoryModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                setPreview($('#createCategoryImagePreview'), $('#createCategoryImageIcon'), null);
                $('#create_category_image').val('');

                $('#editCategoryImagePreview').data('existing', '');
                setPreview($('#editCategoryImagePreview'), $('#editCategoryImageIcon'), null);
                $('#edit_category_image').val('');

                $('#parent_id_add').html(buildParentOptions());
                $('#parent_id_edit').html('<option value="">-- Select --</option>');
                initParentSelects();
            });
        });
    </script>
    @endpush
</x-backend-layout>
