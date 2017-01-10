@if ($user)
    @if ($user instanceof App\Admin)
        @include('_.partials.site-nav.authed.admin')
    @elseif ($user instanceof App\Tutor)
        @include('_.partials.site-nav.authed.tutor')
    @else
        @include('_.partials.site-nav.authed.student')
    @endif
@else
    @include('_.partials.site-nav.default.student')
@endif
