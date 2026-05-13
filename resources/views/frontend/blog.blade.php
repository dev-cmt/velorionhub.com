<x-frontend-layout title="Blog" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <!-- site__body -->
    <div class="site__body">
        <div class="block-header block-header--has-breadcrumb block-header--has-title">
            <div class="container">
                <div class="block-header__body">
                    <nav class="breadcrumb block-header__breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb__list">
                            @foreach($breadcrumb_list as $breadcrumb)
                                @if($loop->last)
                                    <li class="breadcrumb__item breadcrumb__item--current" aria-current="page">
                                        <span class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</span>
                                    </li>
                                @else
                                    <li class="breadcrumb__item breadcrumb__item--parent {{ $loop->first ? 'breadcrumb__item--first' : '' }}">
                                        <a href="{{ $breadcrumb['url'] }}" class="breadcrumb__item-link">{{ $breadcrumb['name'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                    <h1 class="block-header__title">Lastest News</h1>
                </div>
            </div>
        </div>
        <div class="block blog-view blog-view--layout--grid">
            <div class="container">
                <div class="blog-view__body">
                    <div class="blog-view__item blog-view__item-sidebar">

                        <div class="card widget widget-about-us">
                            <h4 class="widget__header">About Blog</h4>
                            <div class="widget-about-us__body">
                                <div class="widget-about-us__text">
                                    {{ $settings->description ?? 'Welcome to our blog. Stay updated with the latest news and insights.' }}
                                </div>
                                <div class="widget-about-us__social-links social-links">
                                    <ul class="social-links__list">
                                        <li class="social-links__item social-links__item--instagram">
                                            <a href="https://instagram.com/" target="_blank">
                                                <i class="widget-social__icon fab fa-instagram"></i>
                                            </a>
                                        </li>
                                        <li class="social-links__item social-links__item--twitter">
                                            <a href="https://twitter.com/" target="_blank">
                                                <i class="widget-social__icon fab fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li class="social-links__item social-links__item--youtube">
                                            <a href="https://youtube.com/" target="_blank">
                                                <i class="widget-social__icon fab fa-youtube"></i>
                                            </a>
                                        </li>
                                        <li class="social-links__item social-links__item--facebook">
                                            <a href="https://facebook.com/" target="_blank">
                                                <i class="widget-social__icon fab fa-facebook"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card widget widget-categories">
                            <div class="widget__header">
                                <h4>Categories</h4>
                            </div>
                            <ul class="widget-categories__list widget-categories__list--root" data-collapse
                                data-collapse-opened-class="widget-categories__item--open">
                                @foreach($blog_categories as $blog_category)
                                <li class="widget-categories__item" data-collapse-item>
                                    <a class="widget-categories__link"
                                        href="{{ route('blog', ['category' => $blog_category->slug]) }}">{{ $blog_category->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card widget widget-posts">
                            <div class="widget__header">
                                <h4>Latest Posts</h4>
                            </div>
                            <ul class="widget-posts__list">
                                @foreach($latest_posts as $l_post)
                                <li class="widget-posts__item">
                                    <div class="widget-posts__image">
                                        <a href="{{ route('blog.show', $l_post->slug) }}">
                                            <img src="{{ $l_post->image_path ? asset($l_post->image_path) : asset('images/no-image.jpg') }}" alt="{{ $l_post->title }}" height="60" class="image__tag">
                                        </a>
                                    </div>
                                    <div class="widget-posts__info">
                                        <div class="widget-posts__name">
                                            <a href="{{ route('blog.show', $l_post->slug) }}">{{ $l_post->title }}</a>
                                        </div>
                                        <div class="widget-posts__date">{{ $l_post->created_at->format('F d, Y') }}</div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="widget-newsletter widget">
                            <h4 class="widget-newsletter__title">Newsletter</h4>
                            <div class="widget-newsletter__text">
                                Enter your email address below to subscribe to our newsletter and keep up to date
                                with the latest news, discounts and special offers.
                            </div>
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form class="widget-newsletter__form ajax_form" method="post" action="{{ route('subscribe') }}" name="subscribe">
                                @csrf
                                <input type="text" name="nospam:blank" value="" style="display:none;" />
                                <label for="widget-newsletter-email" class="sr-only">Email Address</label>
                                <input id="widget-newsletter__email" type="text" class="form-control" placeholder="Email Address" name="email" value="" required>
                                <input type="submit" class="widget-newsletter__button" type="submit" name="subscribe" value="Subscribe">
                            </form>
                        </div>

                        @if($blog_tags->count() > 0)
                        <div class="card widget-tags widget">
                            <div class="widget__header">
                                <h4>Tags Cloud</h4>
                            </div>
                            <div class="widget-tags__body tags">
                                <div class="tags__list">
                                    @foreach($blog_tags as $blog_tag)
                                        <a href="{{ route('blog', ['tag' => $blog_tag->slug]) }}">{{ $blog_tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                    <div class="blog-view__item blog-view__item-posts">
                        <div class="block posts-view" id="pdopage">
                            <div class="posts-view__list posts-list posts-list--layout--grid-2">
                                <div class="posts-list__body rows" id="pdopage">
                                    @foreach($blog as $post)
                                    <div class="posts-list__item">
                                        <div class="post-card post-card--layout--grid-sm">
                                            <div class="post-card__image">
                                                <a href="{{ route('blog.show', $post->slug) }}">
                                                    <img src="{{ $post->image_path ? asset($post->image_path) : asset('images/no-image.jpg') }}" class="img-fluid w-100" alt="">
                                                </a>
                                            </div>
                                            <div class="post-card__content">
                                                <div class="post-card__title">
                                                    <h2><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                                                </div>
                                                <div class="post-card__date">
                                                    By <a>{{ $post->author->name }}</a> on {{ $post->created_at->format('F d, Y') }}
                                                </div>
                                                <div class="post-card__excerpt">
                                                    <div class="typography">
                                                        {{ Str::limit(strip_tags($post->content), 100) }}
                                                    </div>
                                                </div>
                                                <div class="post-card__more">
                                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-secondary btn-sm">Read more</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="posts-view__pagination">
                                {{ $blog->appends(request()->query())->links('vendor.pagination.custom') }}
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
