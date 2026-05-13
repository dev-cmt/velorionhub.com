<x-backend-layout title="Home Slides">

    @push('css')
    <style>
        .img-preview-box {
            width: 100%;
            height: 120px;
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
        .img-preview-box .placeholder-icon { font-size: 1.5rem; color: #adb5bd; }
        .img-remove-btn {
            position: absolute;
            top: 4px; right: 4px;
            background: rgba(220,53,69,0.85);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 22px; height: 22px;
            font-size: 11px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            z-index: 10;
        }
    </style>
    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Home Slides</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Home Slides</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">All Home Slides</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSlideModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Slide
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
                                    <th>Desktop Image</th>
                                    <th>Mobile Image</th>
                                    <th>Title / Offer Text</th>
                                    <th>Button</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slides as $key => $slide)
                                <tr>
                                    <td>{{ $slides->firstItem() + $key }}</td>
                                    <td>
                                        @if($slide->desktop_image)
                                            <img src="{{ asset($slide->desktop_image) }}" alt="desktop"
                                                 class="rounded" style="width:80px;height:50px;object-fit:cover;">
                                        @else
                                            <span class="badge bg-secondary-transparent">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slide->mobile_image)
                                            <img src="{{ asset($slide->mobile_image) }}" alt="mobile"
                                                 class="rounded" style="width:50px;height:50px;object-fit:cover;">
                                        @else
                                            <span class="badge bg-secondary-transparent">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $slide->title ?: '—' }}</div>
                                        @if($slide->offer_text)
                                            <small class="text-muted">{{ $slide->offer_text }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slide->button_text)
                                            <a href="{{ $slide->button_url }}" target="_blank"
                                               class="badge bg-primary-transparent text-decoration-none">
                                                {{ $slide->button_text }}
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $slide->sort_order }}</span>
                                    </td>
                                    <td>
                                        @if($slide->status)
                                            <span class="badge bg-success-transparent">Active</span>
                                        @else
                                            <span class="badge bg-danger-transparent">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning-light btn-icon edit-slide-btn" 
                                                    title="Edit"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editSlideModal"
                                                    data-id="{{ $slide->id }}"
                                                    data-title="{{ $slide->title }}"
                                                    data-offer_text="{{ $slide->offer_text }}"
                                                    data-details="{{ $slide->details }}"
                                                    data-button_text="{{ $slide->button_text }}"
                                                    data-button_url="{{ $slide->button_url }}"
                                                    data-sort_order="{{ $slide->sort_order }}"
                                                    data-status="{{ $slide->status }}"
                                                    data-desktop_image="{{ asset($slide->desktop_image) }}"
                                                    data-mobile_image="{{ asset($slide->mobile_image) }}"
                                                    data-has_desktop="{{ $slide->desktop_image ? 1 : 0 }}"
                                                    data-has_mobile="{{ $slide->mobile_image ? 1 : 0 }}"
                                                    data-update_url="{{ route('home-slides.update', $slide) }}">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <form action="{{ route('home-slides.destroy', $slide) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this slide?')">
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
                                        <i class="ri-image-line fs-24 d-block mb-2"></i>
                                        No home slides found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($slides->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $slides->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Slide Modal -->
    <div class="modal fade" id="createSlideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('home-slides.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Add New Home Slide</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="offer_text" class="form-label">Offer Text</label>
                                <input type="text" class="form-control" id="offer_text" name="offer_text" placeholder="e.g. UP TO 50% OFF">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Main Headline">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="details" class="form-label">Description</label>
                                <textarea class="form-control" id="details" name="details" rows="2" placeholder="Short description..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_text" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="button_text" name="button_text" placeholder="Shop Now">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="button_url" class="form-label">Button URL</label>
                                <input type="text" class="form-control" id="button_url" name="button_url" placeholder="https://...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Desktop Image <small class="text-muted">(1920x700)</small></label>
                                <div class="img-preview-box" onclick="document.getElementById('create_desktop_image').click()">
                                    <img id="create_desktop_preview" src="" class="d-none" alt="preview">
                                    <i class="ri-image-add-line placeholder-icon" id="create_desktop_icon"></i>
                                </div>
                                <input type="file" id="create_desktop_image" name="desktop_image" class="d-none" accept="image/*" onchange="previewImg(this, 'create_desktop_preview', 'create_desktop_icon')">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Image <small class="text-muted">(768x500)</small></label>
                                <div class="img-preview-box" onclick="document.getElementById('create_mobile_image').click()">
                                    <img id="create_mobile_preview" src="" class="d-none" alt="preview">
                                    <i class="ri-smartphone-line placeholder-icon" id="create_mobile_icon"></i>
                                </div>
                                <input type="file" id="create_mobile_image" name="mobile_image" class="d-none" accept="image/*" onchange="previewImg(this, 'create_mobile_preview', 'create_mobile_icon')">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Slide Modal -->
    <div class="modal fade" id="editSlideModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editSlideForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title">Edit Home Slide</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_offer_text" class="form-label">Offer Text</label>
                                <input type="text" class="form-control" id="edit_offer_text" name="offer_text">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit_title" name="title">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_details" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_details" name="details" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_button_text" class="form-label">Button Text</label>
                                <input type="text" class="form-control" id="edit_button_text" name="button_text">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_button_url" class="form-label">Button URL</label>
                                <input type="text" class="form-control" id="edit_button_url" name="button_url">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Desktop Image</label>
                                <div class="img-preview-box" id="edit_desktop_box">
                                    <img id="edit_desktop_preview" src="" class="d-none" alt="preview">
                                    <i class="ri-image-add-line placeholder-icon" id="edit_desktop_icon"></i>
                                    <button type="button" class="img-remove-btn d-none" id="edit_desktop_remove" onclick="removeImage('edit_desktop_image', 'edit_desktop_preview', 'edit_desktop_icon', 'remove_desktop_image', 'edit_desktop_remove')">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_desktop_image" id="remove_desktop_image" value="0">
                                <div class="mt-2">
                                    <label for="edit_desktop_image" class="btn btn-sm btn-outline-secondary">Change Image</label>
                                    <input type="file" id="edit_desktop_image" name="desktop_image" class="d-none" accept="image/*" onchange="previewImg(this, 'edit_desktop_preview', 'edit_desktop_icon', 'edit_desktop_remove')">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Image</label>
                                <div class="img-preview-box" id="edit_mobile_box">
                                    <img id="edit_mobile_preview" src="" class="d-none" alt="preview">
                                    <i class="ri-smartphone-line placeholder-icon" id="edit_mobile_icon"></i>
                                    <button type="button" class="img-remove-btn d-none" id="edit_mobile_remove" onclick="removeImage('edit_mobile_image', 'edit_mobile_preview', 'edit_mobile_icon', 'remove_mobile_image', 'edit_mobile_remove')">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_mobile_image" id="remove_mobile_image" value="0">
                                <div class="mt-2">
                                    <label for="edit_mobile_image" class="btn btn-sm btn-outline-secondary">Change Image</label>
                                    <input type="file" id="edit_mobile_image" name="mobile_image" class="d-none" accept="image/*" onchange="previewImg(this, 'edit_mobile_preview', 'edit_mobile_icon', 'edit_mobile_remove')">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control" id="edit_sort_order" name="sort_order" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="edit_status" name="status" value="1">
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Slide</button>
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
            const editButtons = document.querySelectorAll('.edit-slide-btn');
            const editForm    = document.getElementById('editSlideForm');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = this.dataset;
                    
                    editForm.action = data.update_url;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_offer_text').value = data.offer_text;
                    document.getElementById('edit_details').value = data.details;
                    document.getElementById('edit_button_text').value = data.button_text;
                    document.getElementById('edit_button_url').value = data.button_url;
                    document.getElementById('edit_sort_order').value = data.sort_order;
                    document.getElementById('edit_status').checked = data.status == 1;

                    // Reset hidden removal fields
                    document.getElementById('remove_desktop_image').value = '0';
                    document.getElementById('remove_mobile_image').value = '0';

                    // Desktop Image Preview
                    const desktopPreview = document.getElementById('edit_desktop_preview');
                    const desktopIcon    = document.getElementById('edit_desktop_icon');
                    const desktopRemove  = document.getElementById('edit_desktop_remove');
                    if (data.has_desktop == 1) {
                        desktopPreview.src = data.desktop_image;
                        desktopPreview.classList.remove('d-none');
                        desktopIcon.classList.add('d-none');
                        desktopRemove.classList.remove('d-none');
                    } else {
                        desktopPreview.classList.add('d-none');
                        desktopIcon.classList.remove('d-none');
                        desktopRemove.classList.add('d-none');
                    }

                    // Mobile Image Preview
                    const mobilePreview = document.getElementById('edit_mobile_preview');
                    const mobileIcon    = document.getElementById('edit_mobile_icon');
                    const mobileRemove  = document.getElementById('edit_mobile_remove');
                    if (data.has_mobile == 1) {
                        mobilePreview.src = data.mobile_image;
                        mobilePreview.classList.remove('d-none');
                        mobileIcon.classList.add('d-none');
                        mobileRemove.classList.remove('d-none');
                    } else {
                        mobilePreview.classList.add('d-none');
                        mobileIcon.classList.remove('d-none');
                        mobileRemove.classList.add('d-none');
                    }
                });
            });

            // Re-open modal if there are validation errors
            @if($errors->any())
                @if(old('_method') == 'PUT')
                    // For edit modal, we'd need the ID to re-populate it properly.
                    // This is a bit complex without more state. 
                    // For now, let's just trigger the create modal if it was a POST, 
                    // or let the user re-click edit.
                @else
                    var createModal = new bootstrap.Modal(document.getElementById('createSlideModal'));
                    createModal.show();
                @endif
            @endif
        });
    </script>
    @endpush

</x-backend-layout>
