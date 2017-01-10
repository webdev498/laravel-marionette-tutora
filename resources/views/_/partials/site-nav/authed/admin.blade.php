<nav class="site-nav">
    <a href="/" class="site-nav__home site-nav__home--compact graphic graphic--logo"></a>
    <a href="/" class="site-nav__home site-nav__home--full graphic graphic--logo--full"></a>

    <a href="#" class="site-nav__burger">=</a>
    <a href="#" class="site-nav__cross">&times;</a>

    <div class="site-nav__wrapper">

        <ul class="site-nav__list site-nav__list--main"><!--
        --><li class="site-nav__item">
                <a href="{{ relroute('admin.dashboard.index') }}" class="site-nav__link site-nav__link--dashboard">Dashboard</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.relationships.index') }}" class="site-nav__link site-nav__link--relationships">Rels.</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.tutors.index') }}" class="site-nav__link site-nav__link--tutors">Tutors</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.students.index') }}" class="site-nav__link site-nav__link--students">Students</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.messages.index') }}" class="site-nav__link site-nav__link--messages">Messages</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.jobs.index') }}" class="site-nav__link site-nav__link--jobs">Jobs</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.lessons.index') }}" class="site-nav__link site-nav__link--lessons">Lessons</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('admin.blog.index') }}" class="site-nav__link site-nav__link--dashboard">Blog</a>
            </li><!--

        --><li class="site-nav__item">
                <a href="{{ relroute('auth.logout') }}" class="site-nav__link site-nav__link--logout">Logout</a>
            </li><!--
    --></ul>

        <input type="text"
               accesskey="/"
               style="float: right; margin-top: 12px; width: 300px;"
               placeholder="Search..."
               class="[ field__input ][ input input--squared input--small input--bordered ][ js-autocomplete ]"
               data-autocomplete="search">
    </div>
</nav>
