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
            <div class="row">
                <div class="col-md-12">

                    @forelse($couriers as $courier)
                        @php
                            $setting = $courier->setting;
                        @endphp

                        <div class="card mt-3 border">
                            <div class="card-header d-flex align-items-center">
                                <b>{{ $courier->name }}</b>

                                {{-- optional logo --}}
                                @if($courier->slug == 'pathao')
                                    <img style="height:25px;margin-left:10px" src="{{ asset('backEnd/assets/images/pathao.png') }}" alt="Pathao">
                                @elseif($courier->slug == 'steadfast')
                                    <img style="height:25px;margin-left:10px" src="{{ asset('backEnd/assets/images/steadfast_new.png') }}" alt="Steadfast">
                                @elseif($courier->slug == 'carrybee')
                                    <img style="height:25px;margin-left:10px" src="{{ asset('backEnd/assets/images/carrybee.png') }}" alt="Carrybee">
                                @endif

                                {{-- Active/Inactive badge --}}
                                <span class="ms-auto badge {{ $courier->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $courier->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    {{-- LEFT SIDE (FORM) --}}
                                    <div class="col-md-7">
                                        <form action="{{ route('courier-settings.update') }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="courier_id" value="{{ $courier->id }}">
                                            <input type="hidden" name="store_id"   value="{{ $storeId }}">

                                            {{-- Active --}}
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="status"
                                                    id="status_{{ $courier->id }}"
                                                    {{ $setting && $setting->status ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold" for="status_{{ $courier->id }}">
                                                    Enable This Courier
                                                </label>
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

                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ $setting->email ?? '' }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label">Password</label>
                                                    <input type="text" class="form-control" name="password"
                                                        value="{{ $setting->password ?? '' }}"
                                                        autocomplete="new-password">
                                                </div>

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
                                            </div>

                                            <div class="mt-3">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ri-save-line me-1"></i> Save Settings
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- RIGHT SIDE (TOKENS) --}}
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Access Token</label>
                                            <textarea readonly class="form-control bg-light font-monospace" rows="5"
                                                style="font-size:0.78rem">{{ $setting->access_token ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Refresh Token</label>
                                            <textarea readonly class="form-control bg-light font-monospace" rows="3"
                                                style="font-size:0.78rem">{{ $setting->refresh_token ?? '' }}</textarea>
                                        </div>

                                        {{-- Optional token generate --}}
                                        @if($courier->slug == 'pathao')
                                            <form action="{{ route('courier.token.generate') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="courier_id" value="{{ $courier->id }}">
                                                <input type="hidden" name="store_id"   value="{{ $storeId }}">
                                                <button class="btn btn-info w-100">
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
