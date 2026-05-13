<x-backend-layout title="Promotion Banners">

    @push('css')
    <style>
        .img-preview-box {
            width: 100%;
            height: 140px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            overflow: hidden;
            background: #f8f9fa;
            transition: border-color 0.2s;
            position: relative;
        }
        .img-preview-box:hover { border-color: #6c5ce7; }
        .img-preview-box img { width: 100%; height: 100%; object-fit: cover; }
        .img-preview-box .placeholder-icon { font-size: 2rem; color: #adb5bd; }
        .img-remove-btn {
            position: absolute;
            top: 5px; right: 5px;
            background: rgba(220,53,69,0.85);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 24px; height: 24px;
            font-size: 12px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            z-index: 10;
        }
    </style>
    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Promotion Banners</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Promotion Banners</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">All Promotion Banners</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBannerModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Banner
                    </button>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Details</th>
                                    <th>Button / URL</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banners as $key => $banner)
                                <tr>
                                    <td>{{ $banners->firstItem() + $key }}</td>
                                    <td>
                                        @if($banner->image)
                                            <img src="{{ asset($banner->image) }}" alt="banner"
                                                 class="rounded" style="width:80px;height:50px;object-fit:cover;">
                                        @else
                                            <span class="badge bg-secondary-transparent">No Image</span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $banner->title ?: '—' }}</td>
                                    <td>
                                        <span class="text-muted" style="max-width:200px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $banner->details ?: '—' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($banner->button_text)
                                            <a href="{{ $banner->url }}" target="_blank"
                                               class="badge bg-primary-transparent text-decoration-none">
                                                {{ $banner->button_text }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $banner->sort_order }}</span>
                                    </td>
                                    <td>
                                        @if($banner->status)
                                            <span class="badge bg-success-transparent">Active</span>
                                        @else
                                            <span class="badge bg-danger-transparent">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning-light btn-icon edit-banner-btn" 
                                                    title="Edit"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editBannerModal"
                                                    data-id="{{ $banner->id }}"
                                                    data-title="{{ $banner->title }}"
                                                    data-details="{{ $banner->details }}"
                                                    data-button_text="{{ $banner->button_text }}"
                                                    data-url="{{ $banner->url }}"
                                                    data-sort_order="{{ $banner->sort_order }}"
                                                    data-status="{{ $banner->status }}"
                                                    data-image="{{ asset($banner->image) }}"
                                                    data-has_image="{{ $banner->image ? 1 : 0 }}"
                                                    data-update_url="{{ route('promotion-banners.update', $banner) }}">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <form action="{{ route('promotion-banners.destroy', $banner) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this banner?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger-light btn-icon"
                                                        title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ri-image-2-line fs-24 d-block mb-2"></i>
                                        No promotion banners found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($banners->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $banners->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Banner Modal -->
    <div class="modal fade" id="createBannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('promotion-banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Add New Promotion Banner</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="e.g. New Arrivals">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="details" class="form-label">Details / Caption</label>
                                <textarea class="form-control" id="details" name="details" rows="2" placeholder="Short description..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_text" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Shop Now">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="url" class="form-label">URL / Link</label>
                                <input type="text" class="form-control" id="url" name="url" placeholder="https://...">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Banner Image <small class="text-muted">(600x400)</small></label>
                                <div class="img-preview-box" onclick="document.getElementById('create_image').click()">
                                    <img id="create_preview" src="" class="d-none" alt="preview">
                                    <i class="ri-image-add-line placeholder-icon" id="create_icon"></i>
                                </div>
                                <input type="file" id="create_image" name="image" class="d-none" accept="image/*" onchange="previewImg(this, 'create_preview', 'create_icon')">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label d-block">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Banner Modal -->
    <div class="modal fade" id="editBannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editBannerForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title">Edit Promotion Banner</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="edit_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit_title" name="title">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_details" class="form-label">Details / Caption</label>
                                <textarea class="form-control" id="edit_details" name="details" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_button_text" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="edit_button_text" name="button_text">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_url" class="form-label">URL / Link</label>
                                <input type="text" class="form-control" id="edit_url" name="url">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Banner Image</label>
                                <div class="img-preview-box" id="edit_image_box">
                                    <img id="edit_preview" src="" class="d-none" alt="preview">
                                    <i class="ri-image-add-line placeholder-icon" id="edit_icon"></i>
                                    <button type="button" class="img-remove-btn d-none" id="edit_remove_btn" onclick="removeImage('edit_image', 'edit_preview', 'edit_icon', 'remove_image', 'edit_remove_btn')">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_image" id="remove_image" value="0">
                                <div class="mt-2">
                                    <label for="edit_image" class="btn btn-sm btn-outline-secondary">Change Image</label>
                                    <input type="file" id="edit_image" name="image" class="d-none" accept="image/*" onchange="previewImg(this, 'edit_preview', 'edit_icon', 'edit_remove_btn')">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="mb-3">
                                    <label for="edit_sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="edit_sort_order" name="sort_order" min="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label d-block">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="edit_status" name="status" value="1">
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function previewImg(input, previewId, iconId, removeBtnId) {
            const preview = document.getElementById(previewId);
            const icon    = document.getElementById(iconId);
            const removeBtn = removeBtnId ? document.getElementById(removeBtnId) : null;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (icon) icon.classList.add('d-none');
                    if (removeBtn) removeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage(inputId, previewId, iconId, hiddenId, removeBtnId) {
            const preview = document.getElementById(previewId);
            const icon    = document.getElementById(iconId);
            const hidden  = document.getElementById(hiddenId);
            const fileInput = document.getElementById(inputId);
            const removeBtn = document.getElementById(removeBtnId);

            if (fileInput) fileInput.value = '';
            if (preview) { preview.src = ''; preview.classList.add('d-none'); }
            if (icon)    { icon.classList.remove('d-none'); }
            if (hidden)  hidden.value = '1';
            if (removeBtn) removeBtn.classList.add('d-none');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-banner-btn');
            const editForm    = document.getElementById('editBannerForm');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = this.dataset;
                    
                    editForm.action = data.update_url;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_details').value = data.details;
                    document.getElementById('edit_button_text').value = data.button_text;
                    document.getElementById('edit_url').value = data.url;
                    document.getElementById('edit_sort_order').value = data.sort_order;
                    document.getElementById('edit_status').checked = data.status == 1;

                    // Reset hidden removal fields
                    document.getElementById('remove_image').value = '0';

                    // Image Preview
                    const preview = document.getElementById('edit_preview');
                    const icon    = document.getElementById('edit_icon');
                    const remove  = document.getElementById('edit_remove_btn');
                    if (data.has_image == 1) {
                        preview.src = data.image;
                        preview.classList.remove('d-none');
                        icon.classList.add('d-none');
                        remove.classList.remove('d-none');
                    } else {
                        preview.classList.add('d-none');
                        icon.classList.remove('d-none');
                        remove.classList.add('d-none');
                    }
                });
            });

            // Re-open modal if there are validation errors
            @if($errors->any())
                @if(old('_method') == 'PUT')
                    // Same as home slides, complex to re-open edit modal without state.
                @else
                    var createModal = new bootstrap.Modal(document.getElementById('createBannerModal'));
                    createModal.show();
                @endif
            @endif
        });
    </script>
    @endpush

</x-backend-layout>
