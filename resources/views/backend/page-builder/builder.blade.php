<x-backend-layout title="Page Builder - {{ $page->title }}">

    @push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.css">
    <style>
        .builder-container { display: flex; height: calc(100vh - 150px); background: #f0f2f5; margin: -1rem; }
        .builder-sidebar { width: 300px; background: #fff; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
        .builder-canvas { flex: 1; overflow-y: auto; padding: 40px; position: relative; }
        .builder-header { padding: 15px 20px; background: #fff; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
        
        .section-item { 
            background: #fff; border: 1px solid #eee; margin-bottom: 15px; border-radius: 8px;
            position: relative; transition: all 0.2s; cursor: move;
        }
        .section-item:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #6c5ce7; }
        .section-header { padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; border-radius: 8px 8px 0 0; }
        .section-body { padding: 0; min-height: 50px; background: #fff; position: relative; overflow: hidden; }
        .section-preview-overlay { 
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(255,255,255,0.7); display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.2s; z-index: 5;
        }
        .section-item:hover .section-preview-overlay { opacity: 1; }

        .section-type-list { padding: 15px; overflow-y: auto; }
        .section-type-btn { 
            display: flex; align-items: center; padding: 10px; border: 1px solid #eee; 
            border-radius: 6px; margin-bottom: 10px; cursor: pointer; transition: background 0.2s;
            width: 100%; text-align: left; background: #fff;
        }
        .section-type-btn:hover { background: #f0f7ff; border-color: #007bff; }
        .section-type-icon { width: 32px; height: 32px; background: #eef2ff; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 12px; color: #6366f1; }

        .empty-canvas { 
            height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;
            border: 2px dashed #ccc; border-radius: 12px; color: #888;
        }
    </style>
    @endpush

    <div class="builder-header">
        <div>
            <h5 class="mb-0 fw-semibold">{{ $page->title }}</h5>
            <small class="text-muted"><code>/{{ $page->slug }}</code> • {{ ucfirst($page->layout) }} Layout</small>
        </div>
        <div class="btn-list">
            <a href="{{ route('page-builder.admin.pages.index') }}" class="btn btn-light btn-sm">Back to List</a>
            <button class="btn btn-info btn-sm" onclick="window.open('{{ route('home') }}/{{ $page->slug }}', '_blank')">Preview</button>
            <button class="btn btn-primary btn-sm" id="save-builder">Save All Changes</button>
        </div>
    </div>

    <div class="builder-container">
        <div class="builder-sidebar">
            <div class="p-3 border-bottom bg-light fw-bold">Add Sections</div>
            <div class="section-type-list">
                @foreach($sectionTypes as $type => $info)
                <button class="section-type-btn" data-type="{{ $type }}">
                    <div class="section-type-icon">
                        <i class="ri-{{ $info['icon'] }}-line"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">{{ $info['name'] }}</div>
                        <div class="text-muted" style="font-size: 10px;">{{ Str::limit($info['description'], 30) }}</div>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        <div class="builder-canvas" id="builder-canvas">
            @if($page->sections->count() > 0)
                <div id="sections-list">
                    @foreach($page->sections as $section)
                    <div class="section-item" data-id="{{ $section->id }}">
                        <div class="section-header">
                            <span>
                                <i class="ri-drag-move-fill me-2 text-muted"></i>
                                <span class="fw-bold">{{ ucfirst($section->type) }}</span>
                            </span>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-light edit-section" data-id="{{ $section->id }}"><i class="ri-edit-line"></i></button>
                                <button class="btn btn-light duplicate-section" data-id="{{ $section->id }}"><i class="ri-file-copy-line"></i></button>
                                <button class="btn btn-light toggle-section {{ $section->status ? '' : 'text-danger' }}" data-id="{{ $section->id }}">
                                    <i class="ri-eye-{{ $section->status ? 'line' : 'off-line' }}"></i>
                                </button>
                                <button class="btn btn-light delete-section text-danger" data-id="{{ $section->id }}"><i class="ri-delete-bin-line"></i></button>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="section-preview-overlay">
                                <button class="btn btn-primary btn-sm edit-section" data-id="{{ $section->id }}">Edit Content</button>
                            </div>
                            <div class="p-4 text-center text-muted">
                                <i class="{{ $sectionTypes[$section->type]['icon'] ?? 'ri-layout-line' }} fs-24 mb-2"></i>
                                <p class="mb-0">Content: {{ count($section->content ?? []) }} items</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-canvas">
                    <i class="ri-layout-masonry-line fs-1 mb-3"></i>
                    <h4>Your page is empty</h4>
                    <p>Select a section from the sidebar to start building.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Section Sidebar/Modal (Simplified) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sectionEditSidebar" style="width: 500px;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="sidebarTitle">Edit Section</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" id="sidebarContent">
            <!-- Content loaded via AJAX -->
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        </div>
    </div>

    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Add Section
            document.querySelectorAll('.section-type-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const pageId = "{{ $page->id }}";
                    
                    fetch("{{ route('page-builder.admin.sections.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            page_id: pageId,
                            type: type
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // If it's the first section, we might need to remove the empty-canvas message
                            const emptyCanvas = document.querySelector('.empty-canvas');
                            if (emptyCanvas) {
                                emptyCanvas.remove();
                                const listContainer = document.createElement('div');
                                listContainer.id = 'sections-list';
                                document.getElementById('builder-canvas').appendChild(listContainer);
                                location.reload(); // Simplest way to re-initialize Sortable and UI
                            } else {
                                location.reload();
                            }
                        } else {
                            alert('Error adding section');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong');
                    });
                });
            });

            // Edit Section
            document.querySelectorAll('.edit-section').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const sidebar = new bootstrap.Offcanvas(document.getElementById('sectionEditSidebar'));
                    const content = document.getElementById('sidebarContent');
                    
                    content.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100"><div class="spinner-border text-primary"></div></div>';
                    sidebar.show();
                    
                    fetch(`/page-builder/sections/${id}/edit`)
                        .then(response => response.text())
                        .then(html => {
                            content.innerHTML = html;
                        });
                });
            });

            // Toggle Section
            document.querySelectorAll('.toggle-section').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const icon = this.querySelector('i');
                    
                    fetch(`/page-builder/sections/${id}/toggle`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.status) {
                                icon.className = 'ri-eye-line';
                                this.classList.remove('text-danger');
                            } else {
                                icon.className = 'ri-eye-off-line';
                                this.classList.add('text-danger');
                            }
                        }
                    });
                });
            });

            // Duplicate Section
            document.querySelectorAll('.duplicate-section').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    fetch(`/page-builder/sections/${id}/duplicate`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                    });
                });
            });

            // Delete Section
            document.querySelectorAll('.delete-section').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!confirm('Are you sure you want to delete this section?')) return;
                    
                    const id = this.dataset.id;
                    const item = this.closest('.section-item');
                    
                    fetch(`/page-builder/sections/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.remove();
                            if (document.querySelectorAll('.section-item').length === 0) {
                                location.reload();
                            }
                        }
                    });
                });
            });

            // Save Order
            function saveOrder() {
                const canvas = document.getElementById('sections-list');
                const sections = Array.from(canvas.children).map(el => el.dataset.id);
                const pageId = "{{ $page->id }}";

                fetch("{{ route('page-builder.admin.sections.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        page_id: pageId,
                        sections: sections
                    })
                });
            }

            // Update Sortable to save order
            const sortableCanvas = document.getElementById('sections-list');
            if (sortableCanvas) {
                new Sortable(sortableCanvas, {
                    animation: 150,
                    handle: '.ri-drag-move-fill',
                    onEnd: saveOrder
                });
            }
        });
    </script>
    @endpush

</x-backend-layout>
