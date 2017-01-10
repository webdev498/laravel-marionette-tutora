<nav class="site-nav">
    <a href="/" class="site-nav__home site-nav__home--compact graphic graphic--logo"></a>
    <a href="/" class="site-nav__home site-nav__home--full graphic graphic--logo--full"></a>

    <a href="#" class="site-nav__burger"><span class="icon icon--burger"></span></a>
    <a href="#" class="site-nav__cross">&times;</a>

    <div class="site-nav__wrapper">
        <ul class="site-nav__list site-nav__list--main"><!--
        --><li class="site-nav__item">
                <a href="{{ relroute('student.dashboard.index') }}" class="site-nav__link site-nav__link--dashboard">Dashboard</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('student.messages.index') }}" class="site-nav__link site-nav__link--messages">Messages @if (isset($unreadMessages) && $unreadMessages != 0)  ({{$unreadMessages}}) @endif</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('student.tutors.index') }}" class="site-nav__link site-nav__link--tutors">Tutors</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('student.lessons.index') }}" class="site-nav__link site-nav__link--lessons">Lessons</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('student.account.index') }}" class="site-nav__link site-nav__link--account">Account</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ route('faqs.student') }}" class="site-nav__link site-nav__link--help">Help</a>
            </li><!--
    --></ul>

        <div class="site-nav__account">
            <div class="site-nav__account__handle">
                <figure class="site-nav__account__pic profile-pic profile-pic--small u-mr-">
                    <img src="/img/profile-pictures/{{ $user->uuid }}@80x80.jpg">
                </figure>
                <div class="site-nav__account__title">{{ str_name("{$user->first_name} {$user->last_name}") }}</div>
            </div>

            <ul class="site-nav__list site-nav__list--account"><!--
            @if ($user->isAdmin())
                        --><li class="site-nav__item">
                    <a href="{{ route('admin.dashboard.index') }}" class="site-nav__link">Admin</a>
                </li><!--
            @endif
                        --><li class="site-nav__item">
                    <a href="{{ route('auth.logout') }}" class="site-nav__link site-nav__link--logout">Logout</a>
                </li><!--
        --></ul>
        </div>
    </div>
</nav>
