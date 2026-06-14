<x-frontend-layout title="405 Method Not Allowed">
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
                    <span class="body-small">405 Method Not Allowed</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->
    <!-- 405 Method Not Allowed -->
    <section class="tf-sp-6">
        <div class="container">
            <div class="wg-404 text-center">
                <h1 class="text-primary">405</h1>
                <p class="notice title-normal fw-semibold"><span class="text-primary">Whoops!</span> {{ $exception->getMessage() ?: 'The requested method is not allowed for this resource.' }}</p>
                <div class="box-btn">
                    <a href="{{ url('/') }}" class="tf-btn text-white d-inline-flex">
                        Back To Home Page
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- /405 Method Not Allowed -->
</x-frontend-layout>
