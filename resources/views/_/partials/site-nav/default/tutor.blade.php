<nav class="site-nav site-nav--full">
    <a href="/" class="site-nav__home site-nav__home--compact graphic graphic--logo"></a>
    <a href="/" class="site-nav__home site-nav__home--full graphic graphic--logo--full"></a>

    <a href="#" class="site-nav__burger">=</a>
    <a href="#" class="site-nav__cross">&times;</a>

    <div class="site-nav__wrapper">

        <ul class="site-nav__list site-nav__list--main r"><!--
        --><li class="site-nav__item">
                <a href="{{ relroute('home') }}" class="site-nav__link site-nav__become-student">Become a student</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('register.tutor') }}" class="site-nav__link site-nav__register" data-js>Sign up</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('auth.login') }}" class="site-nav__link site-nav__login" data-js>Login</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ route('faqs.tutor') }}" class="site-nav__link site-nav__help">Help</a>
            </li><!--

        --><li class="site-nav__item show-mobile">
                <a href="#" class="site-nav__how-it-works site-nav__link js-how-it-works">How It works</a>
            </li><!--
        --><li class="site-nav__item show-desktop">
                <a href="#" class="site-nav__how-it-works btn btn--hollow btn--neutral btn--squared js-how-it-works">How It works</a>
            </li><!--
    --></ul>
    </div>
</nav>
