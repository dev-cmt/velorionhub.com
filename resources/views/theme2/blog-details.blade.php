<x-frontend-layout title="{{ $post->title }}" :breadcrumbs="$breadcrumbs" :seotags="$seotags">
    <div class="site__body">
        <div class="block post-view">
            <div class="post-view__header post-header post-header--has-image">
                <div class="post-header__image" style="background-image: url('{{ $post->image_path ? asset($post->image_path) : asset('frontend/images/posts/post-1-1903x500.jpg') }}');"></div>
                <div class="post-header__body">
                    <div class="post-header__categories">
                        <ul class="post-header__categories-list">
                            <li class="post-header__categories-item">
                                <a href="#" class="post-header__categories-link">{{ $post->category->name ?? 'Latest News' }}</a>
                            </li>
                        </ul>
                    </div>
                    <h1 class="post-header__title">{{ $post->title }}</h1>
                    <div class="post-header__meta">
                        <ul class="post-header__meta-list">
                            <li class="post-header__meta-item">By <a href="#" class="post-header__meta-link">{{ $post->author->name ?? 'Admin' }}</a></li>
                            <li class="post-header__meta-item">{{ $post->published_date ? \Carbon\Carbon::parse($post->published_date)->format('F d, Y') : 'Unknown' }}</li>
                        </ul>
                    </div>
                </div>
                <div class="decor post-header__decor decor--type--bottom">
                    <div class="decor__body">
                        <div class="decor__start"></div>
                        <div class="decor__end"></div>
                        <div class="decor__center"></div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="post-view__body">
                    <div class="post-view__item post-view__item-sidebar">
                        <div class="card widget widget-about-us">
                                <h4 class="widget__header">About Blog</h4>
                                <div class="widget-about-us__body">
                                    <div class="widget-about-us__text">
                                        Velorion Hub Blog provides the latest news, updates and guides about automotive parts and vehicle maintenance.
                                    </div>
                                    <div class="widget-about-us__social-links social-links">
                                        <ul class="social-links__list">
                                            <li class="social-links__item social-links__item--instagram">
                                                <a href="https://instagram.com/" target="_blank"><i class="widget-social__icon fab fa-instagram"></i></a>
                                            </li><li class="social-links__item social-links__item--twitter">
                                                <a href="https://twitter.com/" target="_blank"><i class="widget-social__icon fab fa-twitter"></i></a>
                                            </li><li class="social-links__item social-links__item--youtube">
                                                <a href="https://youtube.com/" target="_blank"><i class="widget-social__icon fab fa-youtube"></i></a>
                                            </li><li class="social-links__item social-links__item--facebook">
                                                <a href="https://facebook.com/" target="_blank"><i class="widget-social__icon fab fa-facebook"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="card widget widget-categories">
                            <div class="widget__header"><h4>Categories</h4></div>
                            <ul class="widget-categories__list widget-categories__list--root">
                                @foreach($categories as $cat)
                                    <li class="widget-categories__item">
                                        <a class="widget-categories__link" href="{{ route('shop', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card widget widget-posts">
                            <div class="widget__header"><h4>Latest Posts</h4></div>
                            <ul class="widget-posts__list">
                                @php
                                    $latest_posts = \App\Models\BlogPost::published()->latest()->take(4)->get();
                                @endphp
                                @foreach($latest_posts as $lp)
                                <li class="widget-posts__item">
                                    <div class="widget-posts__image">
                                        <a href="{{ route('blog.show', $lp->slug) }}">
                                            <img src="{{ $lp->image_path ? asset($lp->image_path) : asset('images/no-image.jpg') }}" height="60" class="image__tag" alt="">
                                        </a>
                                    </div>
                                    <div class="widget-posts__info">
                                        <div class="widget-posts__name">
                                            <a href="{{ route('blog.show', $lp->slug) }}">{{ $lp->title }}</a>
                                        </div>
                                        <div class="widget-posts__date">{{ $lp->published_date ? \Carbon\Carbon::parse($lp->published_date)->format('F d, Y') : 'Unknown' }}</div>
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
                        
                        @if($post->tags->count() > 0)
                        <div class="card widget-tags widget">
                            <div class="widget__header"><h4>Tags Cloud</h4></div>
                            <div class="widget-tags__body tags">
                                <div class="tags__list">
                                    @foreach($post->tags as $tag)
                                        <a href="#">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="post-view__item post-view__item-post">
                        <div class="post-view__card post">
                            <div class="post__body typography">
                                {!! $post->content !!}
                            </div>
                            <div class="post__footer">
                                <div class="post__tags tags tags--sm">
                                    <div class="tags__list">
                                        @foreach($post->tags as $tag)
                                            <a href="#">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="post-view__card">
                                <h2 class="post-view__card-title">Comments ({{ $post->comments->count() }})</h2>
                                    <div class="post-view__card-body comments-view">
                                        <ol class="comments-list comments-list--level--0 comments-view__list">
                                            @foreach($post->comments as $comment)
                                                <li class="comments-list__item">
                                                    <div class="comment">
                                                        <div class="comment__avatar">
                                                            <img src="{{ asset('frontend/images/avatars/avatar-1.jpg') }}" alt="">
                                                        </div>
                                                        <div class="comment__content">
                                                            <div class="comment__header">
                                                                <div class="comment__author">{{ $comment->user->name ?? 'Guest' }}</div>
                                                                <div class="comment__date">{{ $comment->created_at->format('F d, Y') }}</div>
                                                            </div>
                                                            <div class="comment__text typography">
                                                                {{ $comment->content }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                        <div class="post-view__card">
                            <h2 class="post-view__card-title">Write A Comment</h2>
                            <form class="post-view__card-body" id="comment-form" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Your Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ Auth::user()->name ?? '' }}" placeholder="Your Name" {{ Auth::check() ? 'readonly' : '' }}>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{ Auth::user()->email ?? '' }}" {{ Auth::check() ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Comment</label>
                                    <textarea class="form-control" rows="6" name="text"></textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-primary mt-md-4 mt-2">Post Comment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-space block-space--layout--before-footer"></div>
    </div>
</x-frontend-layout>
