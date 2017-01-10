<!doctype html>
<html class="no-js">
    <head>

        @if(environment('production') && empty($ab) && Auth::user() == null)
            @yield('ab')
        @endif

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        @include('_.partials.seo')
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#232f49">
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900'>
        <link rel="stylesheet" href="/css/style.min.css">
        <link rel="stylesheet" href="/fonts/font-awesome/css/font-awesome.min.css">

        @if(isset($css))
            @foreach($css as $stylesheet)
                <link rel="stylesheet" href="{{$stylesheet}}">
            @endforeach

        @endif

        @if(isset($js))
            @foreach($js as $javascript)
                <script src="{{$javascript}}"></script>
            @endforeach
        @endif

        @if (environment('production'))


            <script>


                // GA
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', 'UA-63732179-1', 'auto');
                ga('send', 'pageview');
                // Hotjar
                (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:80195,hjsv:5};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
                })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
                
                // Facebook
                
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,'script','https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '108393442857866', {
                em: 'insert_email_variable,'
                });
                fbq('track', 'PageView');

            </script>
            <!-- Facebook -->
            <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=108393442857866&ev=PageView&noscript=1"/></noscript>
        @endif
    </head>



    <body class="page {{ $page_class or "" }}">
        <div class="page__body">
            @include('_.partials.how-it-works')
            @yield('body')
        </div>

        <div class="page__footer">
            @include('_.partials.page-foot')
        </div>

        <div class="dialogue-region">
            @include('_.partials.dialogues.login')
        </div>

        <div class="toast-region"></div>
        <script src="{{ config('app.debug') ? '/js/modernizr.js' : '/js/modernizr.min.js' }}"></script>
        <script type="application/json" id="preload">{!! json_encode(array_extend($__preload, $_preload, $preload)) !!}</script>
        <script src="https://js.stripe.com/v2"></script>
        <script src="/vendor/ziggeo/ziggeo-latest.js"></script>
        @yield('scripts')
        <script src="/js/require.min.js" data-main="{{ config('app.debug') ? '/js/main.js' : '/js/main.min.v2.js' }}"></script>
        @yield('scripts-after')

    </body>
</html>
