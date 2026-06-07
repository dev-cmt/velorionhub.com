<x-frontend-layout title="FAQ" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                </div>
            </div>
        </div>
        <div class="block faq">
            <div class="container container--max--xl">
                <div class="faq__header">
                    <h1 class="faq__header-title">Frequently asked questions</h1>
                </div>
                <div class="faq__section">
                    <div class="faq__section-title">
                        <h3>Shipping Information</h3>
                    </div>
                    <div class="faq__section-body">
                        <div class="row">
                            <div class="faq__section-column col-12 col-lg-6">
                                <div class="typography">
                                    <h6>What shipping methods are available?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                    <h6>How might I obtain an estimated date of delivery?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                </div>
                            </div>
                            <div class="faq__section-column col-12 col-lg-6">
                                <div class="typography">
                                    <h6>Do you ship internationally?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                    <h6>Can I split my order to ship to different locations?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="faq__section">
                    <div class="faq__section-title">
                        <h3>Payment Information</h3>
                    </div>
                    <div class="faq__section-body">
                        <div class="row">
                            <div class="faq__section-column col-12 col-lg-6">
                                <div class="typography">
                                    <h6>What payments methods are available?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                </div>
                            </div>
                            <div class="faq__section-column col-12 col-lg-6">
                                <div class="typography">
                                    <h6>Can I split my payment?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="faq__section">
                    <div class="faq__section-title">
                        <h3>Orders and Returns</h3>
                    </div>
                    <div class="faq__section-body">
                        <div class="row">
                            <div class="faq__section-column col-12 col-lg-6">
                                <div class="typography">
                                    <h6>How do I return or exchange an item?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                </div>
                            </div>
                            <div class="faq__section-column col-12 col-lg-6">
                                <div class="typography">
                                    <h6>How do I cancel an order?</h6>
                                    <p>Lorem ipsum dolor sit amet conse ctetur adipisicing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                        cillum dolore eu fugiat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="faq__footer">
                    <div class="faq__footer-title">Still Have A Questions?</div>
                    <div class="faq__footer-subtitle">We will be happy to answer any questions you may have.</div>
                    <a href="{{ route('contacts') }}" class="btn btn-primary">Contact Us</a>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--before-footer"></div>
    </div>
    <!-- site__body / end -->
</x-frontend-layout>
