<div class="p-4">
    <form id="editSectionForm" action="{{ route('page-builder.admin.sections.update', $section) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <h6 class="fw-bold mb-1">Section Type: {{ ucfirst($section->type) }}</h6>
            <p class="text-muted small">Configure the content and settings for this section.</p>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-light">
                <span class="fw-semibold">Content</span>
            </div>
            <div class="card-body">
                @foreach($section->content as $key => $value)
                    <div class="mb-3">
                        <label class="form-label text-capitalize">{{ str_replace('_', ' ', $key) }}</label>
                        @if(is_array($value))
                            <div class="p-2 border rounded bg-light">
                                <small class="text-muted">Array content - complexity exceeds simple editor</small>
                            </div>
                        @elseif(strlen($value) > 100 || str_contains($value, '<'))
                            <textarea name="content[{{ $key }}]" class="form-control" rows="4">{{ $value }}</textarea>
                        @else
                            <input type="text" name="content[{{ $key }}]" class="form-control" value="{{ $value }}">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <span class="fw-semibold">Settings</span>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }}>
                    <label class="form-check-label">Visible on page</label>
                </div>
                <!-- Additional settings can be added here -->
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update Section</button>
        </div>
    </form>
</div>
