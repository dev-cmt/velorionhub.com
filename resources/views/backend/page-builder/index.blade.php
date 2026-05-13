<x-backend-layout title="Page Builder">

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Page Builder</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pages</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">All Pages</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPageModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Create New Page
                    </button>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Layout</th>
                                    <th>Status</th>
                                    <th>Sections</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pages as $key => $page)
                                <tr>
                                    <td>{{ $pages->firstItem() + $key }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $page->title }}</div>
                                        <small class="text-muted">Updated: {{ $page->updated_at->format('d M, Y') }}</small>
                                    </td>
                                    <td><code>/{{ $page->slug }}</code></td>
                                    <td><span class="badge bg-light text-dark border">{{ ucfirst($page->layout) }}</span></td>
                                    <td>
                                        @if($page->status)
                                            <span class="badge bg-success-transparent">Published</span>
                                        @else
                                            <span class="badge bg-danger-transparent">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info-transparent">{{ $page->sections_count ?? $page->sections()->count() }} Sections</span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="{{ route('page-builder.admin.pages.builder', $page) }}" 
                                               class="btn btn-sm btn-primary-light btn-icon" title="Open Builder">
                                                <i class="ri-layout-masonry-line"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning-light btn-icon edit-page-btn" 
                                                    title="Edit Settings"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editPageModal"
                                                    data-id="{{ $page->id }}"
                                                    data-title="{{ $page->title }}"
                                                    data-slug="{{ $page->slug }}"
                                                    data-layout="{{ $page->layout }}"
                                                    data-status="{{ $page->status }}"
                                                    data-update_url="{{ route('page-builder.admin.pages.update', $page) }}">
                                                <i class="ri-settings-4-line"></i>
                                            </button>
                                            <a href="{{ route('home') }}/{{ $page->slug }}" target="_blank"
                                               class="btn btn-sm btn-info-light btn-icon" title="Preview">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <form action="{{ route('page-builder.admin.pages.destroy', $page) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this page and all its sections?')">
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
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No pages found. Create your first page to start building.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pages->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $pages->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Page Modal -->
    <div class="modal fade" id="createPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('page-builder.admin.pages.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Create New Page</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Page Title</label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="e.g. About Us">
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">URL Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="e.g. about-us (leave blank for auto)">
                        </div>
                        <div class="mb-3">
                            <label for="layout" class="form-label">Layout</label>
                            <select class="form-select" id="layout" name="layout">
                                <option value="default">Default (Full Width)</option>
                                <option value="sidebar">With Sidebar</option>
                                <option value="landing">Landing Page</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                                <label class="form-check-label">Published</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create & Open Builder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Page Modal -->
    <div class="modal fade" id="editPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editPageForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h6 class="modal-title">Page Settings</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Page Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_slug" class="form-label">URL Slug</label>
                            <input type="text" class="form-control" id="edit_slug" name="slug" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_layout" class="form-label">Layout</label>
                            <select class="form-select" id="edit_layout" name="layout">
                                <option value="default">Default (Full Width)</option>
                                <option value="sidebar">With Sidebar</option>
                                <option value="landing">Landing Page</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_status" name="status" value="1">
                                <label class="form-check-label">Published</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-page-btn');
            const editForm    = document.getElementById('editPageForm');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const data = this.dataset;
                    editForm.action = data.update_url;
                    document.getElementById('edit_title').value = data.title;
                    document.getElementById('edit_slug').value = data.slug;
                    document.getElementById('edit_layout').value = data.layout;
                    document.getElementById('edit_status').checked = data.status == 1;
                });
            });
        });
    </script>
    @endpush

</x-backend-layout>
