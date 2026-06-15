<x-backend-layout title="Courier Settings">
    @push('css')
    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Courier Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('system-settings.index') }}">System Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Courier Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Store Selector --}}
    @if($stores->count() > 1)
    <div class="card custom-card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('courier-settings.index') }}" class="d-flex align-items-center gap-3">
                <label class="fw-semibold mb-0 text-nowrap">Select Store:</label>
                <select name="store_id" class="form-select w-auto" onchange="this.form.submit()">
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ $store->id == $storeId ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
    @endif

    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Store Couriers</div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-12">

                    @forelse($couriers as $courier)
                        @php
                            $setting = $courier->setting;
                        @endphp

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header d-flex align-items-center flex-wrap gap-2 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <b class="fs-15">{{ $courier->name }}</b>
                                    {{-- optional logo --}}
                                    @if($courier->slug == 'pathao')
                                        <img style="height:45px" src="{{ asset('backend/images/courier-logo/pathao.jpg') }}" alt="Pathao">
                                    @elseif($courier->slug == 'steadfast')
                                        <img style="height:45px" src="{{ asset('backend/images/courier-logo/steadfast.jpg') }}" alt="Steadfast">
                                    @elseif($courier->slug == 'carrybee')
                                        <img style="height:45px" src="{{ asset('backend/images/courier-logo/carrybee.png') }}" alt="Carrybee">
                                    @elseif($courier->slug == 'redx')
                                        <img style="height:45px" src="{{ asset('backend/images/courier-logo/redx.jpg') }}" alt="RedX">
                                    @endif
                                </div>

                                {{-- Active/Inactive badge --}}
                                <span class="ms-auto badge {{ $courier->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $courier->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="card-body p-4">
                                <div class="row g-4">

                                    {{-- LEFT SIDE (FORM) --}}
                                    <div class="col-lg-7">
                                        <form action="{{ route('courier-settings.update') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="courier_id" value="{{ $courier->id }}">
                                            <input type="hidden" name="store_id"   value="{{ $storeId }}">

                                            <div class="mb-4 p-3 rounded-3 bg-light border">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        name="status"
                                                        id="status_{{ $courier->id }}"
                                                        {{ $setting && $setting->status ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="status_{{ $courier->id }}">
                                                        Enable This Courier
                                                    </label>
                                                </div>
                                            </div>

                                            {{-- Common Fields --}}
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Store Code</label>
                                                    <input type="text" class="form-control" name="store_code"
                                                        value="{{ $setting->store_code ?? '' }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ $setting->phone ?? '' }}">
                                                </div>
                                                @if ($courier->slug != 'carrybee')
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ $setting->email ?? '' }}">
                                                </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <label class="form-label">Password</label>
                                                    <input type="text" class="form-control" name="password"
                                                        value="{{ $setting->password ?? '' }}"
                                                        autocomplete="new-password">
                                                </div>

                                                @if ( $courier->slug == 'steadfast' )
                                                    <div class="col-md-6">
                                                        <label class="form-label">API Key</label>
                                                        <input type="text" class="form-control" name="api_key"
                                                            value="{{ $setting->api_key ?? '' }}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Secret Key</label>
                                                        <input type="text" class="form-control" name="secret_key"
                                                            value="{{ $setting->secret_key ?? '' }}">
                                                    </div>
                                                @else
                                                    @if ($courier->slug == 'carrybee')
                                                    <div class="col-md-6">
                                                        <label class="form-label">Client Context</label>
                                                        <input type="text" class="form-control" name="client_context"
                                                            value="{{ $setting->client_context ?? '' }}">
                                                    </div>
                                                    @endif
                                                    <div class="col-md-6">
                                                        <label class="form-label">Client ID</label>
                                                        <input type="text" class="form-control" name="client_id"
                                                            value="{{ $setting->client_id ?? '' }}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Client Secret</label>
                                                        <input type="text" class="form-control" name="client_secret"
                                                            value="{{ $setting->client_secret ?? '' }}">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mt-4 d-flex flex-wrap gap-2">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ri-save-line me-1"></i> Save Settings
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- RIGHT SIDE (TOKENS) --}}
                                    <div class="col-lg-5">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Access Token</label>
                                            <textarea readonly class="form-control bg-light font-monospace" rows="6"
                                                style="font-size:0.78rem">{{ $setting->access_token ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Refresh Token</label>
                                            <textarea readonly class="form-control bg-light font-monospace" rows="4"
                                                style="font-size:0.78rem">{{ $setting->refresh_token ?? '' }}</textarea>
                                        </div>

                                        {{-- Optional token generate --}}
                                        @if($courier->slug == 'pathao')
                                            <form action="{{ route('courier.token.generate') }}" method="POST" class="mt-4">
                                                @csrf
                                                <input type="hidden" name="courier_id" value="{{ $courier->id }}">
                                                <input type="hidden" name="store_id"   value="{{ $storeId }}">
                                                <button class="btn btn-info w-100 py-2">
                                                    <i class="ri-refresh-line me-1"></i> Generate Pathao Tokens
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">
                            <i class="ri-truck-line fs-2 d-block mb-2"></i>
                            No couriers found. <a href="{{ route('system-settings.index') }}">Add a courier</a> first.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    @push('js')
    @endpush
</x-backend-layout>
