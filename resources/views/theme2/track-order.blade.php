<x-frontend-layout title="Track Order" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- site__body -->
    <div class="site__body">
        <div class="block-header block-header--has-breadcrumb block-header--has-title">
            <div class="container">
                <div class="block-header__body">
                    <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb__list">
                            @foreach($breadcrumb_list as $breadcrumb)
                                <li class="breadcrumb__item @if($loop->first) breadcrumb__item--parent breadcrumb__item--first @endif @if($loop->last) breadcrumb__item--current @endif">
                                    @if(!$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}" class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</a>
                                    @else
                                        <span class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                    <h1 class="block-header__title">Track Order</h1>
                </div>
            </div>
        </div>
        <div class="block">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <p>To track your order please enter your Order ID in the box below and press the "Track" button. This was given to you on your receipt and in the confirmation email you should have received.</p>
                                <form>
                                    <div class="form-group">
                                        <label for="track-order-id">Order ID</label>
                                        <input type="text" id="track-order-id" class="form-control" placeholder="Order ID">
                                    </div>
                                    <div class="form-group">
                                        <label for="track-email">Billing Email</label>
                                        <input type="email" id="track-email" class="form-control" placeholder="Email address">
                                    </div>
                                    <div class="form-button">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Track</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- site__body / end -->
</x-frontend-layout>
