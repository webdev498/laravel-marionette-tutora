@extends('_.layouts.default', [
    'page_class' => 'page--blog'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout ]">
            <div class="[ layout__item layout__item--narrow ] post u-mt">
                <a href="{{ route('articles.index') }}"> < Back to Blog</a>
                <h2 class="heading beta u-mb--">{{ $article->title }}</h2>
                <h4>{{ $article->published_at->long}}</h4>
                <div class="author">
                    <figure class="profile-pic profile-pic--small" ><img src="/img/profile-pictures/{{ $article->user->uuid }}@180x180.jpg" ></figure>
                
                    <span>By {{ $article->user->name }}</span>
                </div>
            </div>
            <div class="[ layout__item layout__item--narrow ]">
                {!! $article->body !!}
            
                <div id="disqus_thread"></div>
                <script>
                    /**
                     *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                     *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
                     */
                    /*
                    var disqus_config = function () {
                        this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                        this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                    };
                    */
                    (function() {  // DON'T EDIT BELOW THIS LINE
                        var d = document, s = d.createElement('script');
                        
                        s.src = '//tutorauk.disqus.com/embed.js';
                        
                        s.setAttribute('data-timestamp', +new Date());
                        (d.head || d.body).appendChild(s);
                    })();
                </script>
                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
            </div>
        </div>
    </div>
@stop
