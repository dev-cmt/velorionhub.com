<x-frontend-layout title="About Us" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- site__body -->
    <div class="site__body">
        <div class="block-header block-header--has-breadcrumb">
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
                    <h1 class="block-header__title">About Us</h1>
                </div>
            </div>
        </div>
        <div class="about">
            <div class="about__body">
                <div class="about__image">
                    <div class="about__image-bg" style="background-image: url('frontend/images/about-1903x1903.jpg');"></div>
                    <div class="decor about__image-decor decor--type--bottom">
                        <div class="decor__body">
                            <div class="decor__start"></div>
                            <div class="decor__end"></div>
                            <div class="decor__center"></div>
                        </div>
                    </div>
                </div>
                <div class="about__card">
                    <div class="about__card-title">About Us</div>
                    <div class="about__card-text">RedParts is an international company with 30 years of history selling spare parts for cars, trucks and motorcycles. During our work we managed to create a unique service for the sale and delivery of spare parts around the world.</div>
<div class="about__card-author">Ryan Ford, CEO RedParts</div>
<div class="about__card-signature"><img src="frontend/images/signature.jpg" alt="" width="160" height="55" /></div>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--divider-xl"></div>
        <div class="block block-teammates">
            <div class="container container--max--xl">
                <div class="block-teammates__title">Professional Team</div>
                <div class="block-teammates__subtitle">Meet this is our professional team.</div>
                <div class="block-teammates__list">
                    <div class="owl-carousel">
                        <div class="block-teammates__item teammate">
                            <div class="teammate__avatar">
                                <img src="frontend/images/cache/teammate1-206x206.1e2ebe78921cc7509750b1dc2d936b597.jpg" alt="Michael Russo">
                            </div>
                            <div class="teammate__info">
                                <div class="teammate__name">Michael Russo</div>
                                <div class="teammate__position">Cheaf executive officer</div>
                            </div>
                        </div><div class="block-teammates__item teammate">
                            <div class="teammate__avatar">
                                <img src="frontend/images/cache/teammate2-206x206.1e2ebe78921cc7509750b1dc2d936b597.jpg" alt="Samanta Smith">
                            </div>
                            <div class="teammate__info">
                                <div class="teammate__name">Samanta Smith</div>
                                <div class="teammate__position">Account manager</div>
                            </div>
                        </div><div class="block-teammates__item teammate">
                            <div class="teammate__avatar">
                                <img src="frontend/images/cache/teammate3-206x206.1e2ebe78921cc7509750b1dc2d936b597.jpg" alt="Antony Harris">
                            </div>
                            <div class="teammate__info">
                                <div class="teammate__name">Antony Harris</div>
                                <div class="teammate__position">Finance director</div>
                            </div>
                        </div><div class="block-teammates__item teammate">
                            <div class="teammate__avatar">
                                <img src="frontend/images/cache/teammate4-206x206.1e2ebe78921cc7509750b1dc2d936b597.jpg" alt="Katerine Miller">
                            </div>
                            <div class="teammate__info">
                                <div class="teammate__name">Katerine Miller</div>
                                <div class="teammate__position">Marketing officer</div>
                            </div>
                        </div><div class="block-teammates__item teammate">
                            <div class="teammate__avatar">
                                <img src="frontend/images/cache/teammate5-206x206.1e2ebe78921cc7509750b1dc2d936b597.jpg" alt="Boris Gilmore">
                            </div>
                            <div class="teammate__info">
                                <div class="teammate__name">Boris Gilmore</div>
                                <div class="teammate__position">Engineer</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--divider-xl"></div>
        <div class="block-space block-space--layout--before-footer"></div>
    </div>
    <!-- site__body / end -->
</x-frontend-layout>
