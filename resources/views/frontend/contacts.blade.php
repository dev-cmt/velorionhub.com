<x-frontend-layout title="Contact Us" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
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
                    <h1 class="block-header__title">Contact Us</h1>
                </div>
            </div>
        </div>
        <div class="block">
            <div class="container container--max--lg">
                <div class="card contacts">
                    <div class="contacts__map">
                        <iframe src='https://maps.google.com/maps?q=Holbrook-Palmer%20Park&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe>
                    </div>
                    <div class="card-body card-body--padding--2">
                        <div class="row">
                            <div class="col-12 col-lg-6 pb-4 pb-lg-0">
                                <div class="mr-1">
                                    <h4 class="contact-us__header card-title">Our Address</h4>
                                    <div class="contact-us__address">
                                        <p>
                                            715 Fake Ave, Apt. 34, New York, NY 10021 USA<br>
                                            Email Address red parts@example.com<br>
                                            Phone number +1 (800) 060-07-30
                                        </p>
                                        <p>
                                            <strong>Opening Hours</strong><br>
                                            <p>Monday to Friday: 8am-8pm<br />Saturday: 8am-6pm<br />Sunday: 10am-4pm</p>
                                        </p>
                                        <p>
                                            <strong>Comment</strong><br>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit suscipit mi, non
                                            tempor nulla finibus eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="ml-1">
                                    <h4 class="contact-us__header card-title">Leave us a Message</h4>
                                    <form class="ajax_form" method="post" action="http://redparts.webps.pp.ua/contact-us.html" name="contact">
                                        <input type="text" name="nospam:blank" value="" style="display:none;" />
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="form-name">Your Name <span class="text-danger">*</span></label>
                                                <input type="text" id="form-name" class="form-control" placeholder="Your Name" name="name" value="" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="form-email">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" id="form-email" class="form-control" placeholder="Email Address" name="email" value="" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="form-subject">Subject</label>
                                            <input type="text" id="form-subject" class="form-control" placeholder="Subject" name="subject" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="form-message">Message <span class="text-danger">*</span></label>
                                            <textarea id="form-message" class="form-control" rows="4" name="message" required></textarea>
                                        </div>
                                        <input type="submit" class="btn btn-primary" name="contact" value="Send Message">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--before-footer"></div>
    </div>
    <!-- site__body / end -->
</x-frontend-layout>
