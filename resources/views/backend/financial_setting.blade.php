<x-backend-layout title="Financial Settings">
    @push('css')

    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Financial Settings</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Financial Settings</li>
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
                            Currency Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Currency</th>
                                    <th>Symbol</th>
                                    <th>Rate (BDT)</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $currencies = ['BDT','USD','EUR'];
                                @endphp

                                @foreach($currencies as $code)
                                    <tr>
                                        <td>{{ $code }}</td>

                                        <td>
                                            <input type="text"
                                                name="currency_symbols[{{ $code }}]"
                                                class="form-control"
                                                value="{{ $settings->currency_symbols[$code] ?? '' }}">
                                        </td>

                                        <td>
                                            <input type="text"
                                                name="currency_rates[{{ $code }}]"
                                                class="form-control"
                                                value="{{ $settings->currency_rates[$code] ?? '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="col-xl-5">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Shipping Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_inside" class="form-label">Inside Dhaka</label>
                                <input type="text" name="shipping_inside" class="form-control" id="shipping_inside" value="{{ $settings ? $settings->shipping_inside : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_outside" class="form-label">Outside Dhaka</label>
                                <input type="text" name="shipping_outside" class="form-control" id="shipping_outside" value="{{ $settings ? $settings->shipping_outside : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="shipping_active" value="0">
                                    <input type="checkbox" name="shipping_active" id="shipping_active" value="1" class="form-check-input" role="switch" {{ $settings ? ($settings->shipping_active ? 'checked' : '') : '' }}>
                                    <label class="form-check-label fw-semibold" for="shipping_active">Enable Shipping</label>
                                </div>
                            </div>
                        </div>
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
