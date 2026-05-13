<x-backend-layout title="Tags Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div>
            <h1 class="page-title fw-semibold fs-18 mb-0">Tags Management</h1>
            <p class="text-muted mb-0">Organize and label your content with custom tags and SEO-friendly slugs.</p>
        </div>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tags</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card shadow-sm border-0">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        <i class='bx bx-purchase-tag-alt me-2 text-primary fs-20'></i> Tags List
                    </div>
                    <button type="button" class="btn btn-primary btn-sm btn-wave d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createTagModal">
                        <i class='bx bx-plus-circle fs-18 me-1'></i> Add New Tag
                    </button>
                </div>
                
                <div class="card-body p-0">
                    {{-- Error Messaging --}}
                    @if($errors->any())
                        <div class="mx-4 mt-3 alert alert-danger alert-dismissible fade show border-0">
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Modern Table Layout --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-4" style="width: 80px;">SL</th>
                                    <th>Tag Name</th>
                                    <th>Slug</th>
                                    <th class="text-center pe-4" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $key => $tag)
                                <tr>
                                    <td class="ps-4 text-muted">{{ ++$key }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle me-2">
                                                <i class='bx bx-hash'></i>
                                            </div>
                                            <span class="fw-semibold">{{ $tag->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-muted border py-2 px-3">
                                            <i class='bx bx-link me-1'></i>{{ $tag->slug }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-list justify-content-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-info-light edit-tag"
                                                data-id="{{ $tag->id }}"
                                                data-name="{{ $tag->name }}"
                                                data-slug="{{ $tag->slug }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTagModal"
                                                title="Edit Tag">
                                                <i class='bx bx-edit-alt'></i>
                                            </button>
                                            
                                            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-danger-light" 
                                                        onclick="return confirm('Are you sure you want to delete this tag?')"
                                                        title="Delete Tag">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class='bx bx-tag-x display-4 opacity-25'></i>
                                            <p class="mt-2">No tags available at the moment.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer border-top-0 d-flex justify-content-center">
                        {{ $tags->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createTagModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-semibold"><i class='bx bx-plus-circle me-1 text-primary'></i> Create New Tag</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-500">Tag Name</label>
                            <input type="text" class="form-control form-control-lg" id="tag_name" name="name" placeholder="Enter tag name..." required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-500">URL Slug</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class='bx bx-link'></i></span>
                                <input type="text" class="form-control" id="tag_slug" name="slug" readonly placeholder="auto-generated-slug">
                            </div>
                            <div class="form-text mt-2">The slug is used for search-engine friendly URLs.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTagModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-semibold"><i class='bx bx-edit text-info me-1'></i> Update Tag</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('tags.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-500 text-muted small text-uppercase">Tag Name</label>
                            <input type="text" class="form-control" id="edit_tag_name" name="name" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-500 text-muted small text-uppercase">URL Slug</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class='bx bx-link-external'></i></span>
                                <input type="text" class="form-control" id="edit_tag_slug" name="slug" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('js')
    <script>
        // Efficient slug generation
        const slugify = (text) => {
            return text.toString().toLowerCase().trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        };

        $('#tag_name').on('input', function() {
            $('#tag_slug').val(slugify($(this).val()));
        });

        $('#edit_tag_name').on('input', function() {
            $('#edit_tag_slug').val(slugify($(this).val()));
        });

        $(document).on('click', '.edit-tag', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_tag_name').val($(this).data('name'));
            $('#edit_tag_slug').val($(this).data('slug'));
        });
    </script>
@endpush
</x-backend-layout>