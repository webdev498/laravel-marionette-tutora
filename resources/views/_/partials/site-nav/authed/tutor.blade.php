<?php 
    if ( ! isset($inactive)) {
        $inactive = isset($user->profile) ? ! in_array($user->profile->status, [App\UserProfile::LIVE, App\UserProfile::OFFLINE]) : false;
    }
?>

<nav class="site-nav">
    <a href="/" class="site-nav__home site-nav__home--compact graphic graphic--logo"></a>
    <a href="/" class="site-nav__home site-nav__home--full graphic graphic--logo--full"></a>

    <a href="#" class="site-nav__burger"><span class="icon icon--burger"></span></a>
    <a href="#" class="site-nav__cross">&times;</a>

    <div class="site-nav__wrapper">

        <ul class="site-nav__list site-nav__list--main"><!--
        --><li class="site-nav__item">
                <a
                        @if ( ! $inactive) href="{{ relroute('tutor.dashboard.index') }}" @endif
                class="
                site-nav__link
                site-nav__link--dashboard
                {{ $inactive ? 'site-nav__link--inactive' : '' }}
                        "
                        >Dashboard</a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        @if ( ! $inactive) href="{{ relroute('tutor.messages.index') }}" @endif
                class="
                site-nav__link
                site-nav__link--messages
                {{ $inactive ? 'site-nav__link--inactive' : '' }}
                        "
                        >
                    Messages @if (isset($unreadMessages) && $unreadMessages != 0)  ({{$unreadMessages}}) @endif
                </a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        @if ( ! $inactive) href="{{ relroute('tutor.students.index') }}" @endif
                class="
                site-nav__link
                site-nav__link--students
                {{ $inactive ? 'site-nav__link--inactive' : '' }}
                        "
                        >
                    Students
                </a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        @if ( ! $inactive) href="{{ relroute('tutor.lessons.index') }}" @endif
                class="
                site-nav__link
                site-nav__link--lessons
                {{ $inactive ? 'site-nav__link--inactive' : '' }}
                        "
                        >
                    Lessons
                </a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        href="{{ relroute('tutor.profile.show', ['uuid' => $user->uuid]) }}"
                        class="site-nav__link site-nav__link--profile"
                        >
                    Profile
                </a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        href="{{ relroute('tutor.account.index') }}"
                        class="
                site-nav__link
                site-nav__link--account
              "
                        >
                    Account
                </a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        @if ( ! $inactive) href="{{ relroute('tutor.jobs.index') }}" @endif
                class="
                site-nav__link
                site-nav__link--jobs
                {{ $inactive ? 'site-nav__link--inactive' : '' }}
                        "
                        >
                    Jobs @if (isset($jobCount) && $jobCount != 0)  ({{$jobCount}}) @endif
                </a>
            </li><!--

        --><li class="site-nav__item">
                <a
                        @if ( ! $inactive) href="{{ relroute('tutor.lessons.create') }}" @endif
                class="
                site-nav__link
                site-nav__link--book
                {{ $inactive ? 'site-nav__link--inactive' : '' }}
                        " data-js
                        >
                    Book a Lesson
                </a>
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
