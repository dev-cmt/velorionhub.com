<x-frontend-layout title="402 Payment Required">
    <!-- Breakcrumbs -->
    <div class="tf-sp-1 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="index.html" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">402 Payment Required</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- 402 Payment Required -->
    <section class="tf-sp-6">
        <div class="container">
            <div class="wg-404 text-center">
                <h1 class="text-primary">402</h1>
                <p class="notice title-normal fw-semibold"><span class="text-primary">Whoops!</span> {{ $exception->getMessage() ?: 'Payment is required to access this page.' }}</p>
                <div class="box-btn">
                    <a href="{{ url('/') }}" class="tf-btn text-white d-inline-flex">
                        Back To Home Page
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- /402 Payment Required -->
</x-frontend-layout>
