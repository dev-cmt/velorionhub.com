<x-backend-layout title="Setting">
    @push('css')

    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Website Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-7">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Frontend Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="is_loading" value="0">
                                    <input type="checkbox" name="is_loading" id="is_loading" value="1"
                                        class="form-check-input" role="switch" {{ $settings ? ($settings->is_loading ? 'checked' : '') : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_loading">Enable
                                        Pre-Loader</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="is_slider" value="0">
                                    <input type="checkbox" name="is_slider" id="is_slider" value="1"
                                        class="form-check-input" role="switch" {{ $settings ? ($settings->is_slider ? 'checked' : '') : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_slider">Enable Frontend
                                        Slider</label>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="active_theme" class="form-label">Frontend Theme</label>
                                <select name="active_theme" id="active_theme" class="form-select">
                                    <option value="frontend" {{ ($settings && $settings->active_theme == 'frontend') ? 'selected' : '' }}>Default Theme</option>
                                    <option value="theme1" {{ ($settings && $settings->active_theme == 'theme1') ? 'selected' : '' }}>Theme 1</option>
                                    <option value="theme2" {{ ($settings && $settings->active_theme == 'theme2') ? 'selected' : '' }}>Theme 2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Backend Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Backend settings content goes here -->
                    </div>
                </div>

                <div class="mt-0">
                    <button type="submit" class="btn btn-success w-100">Update</button>
                </div>
            </div>
        </div>
    </form>

    @push('js')

    @endpush
</x-backend-layout>
