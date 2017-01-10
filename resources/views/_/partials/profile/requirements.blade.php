@if ($tutor->requirements)

    <div class="u-mt [ js-live-region ]">
        <button class="[ btn btn--full btn--disabled ]" disabled>
            Loading...
        </button>
    </div>

    <div class="requirements [ js-requirements ]">
        <header class="u-mt progress-bar-section-margin-bottom">
            <h3 class="heading">Progress</h3>

            <div class="progress-bar progress-bar--{{ $tutor->requirements->meta->percent_complete }} u-mt- u-mb-">
                <div class="progress-bar__bar"></div>
            </div>
        </header>

        @if ($tutor->requirements->data->profile)
            @include('_.partials.profile.requirements-list', [
                'requirements' => $tutor->requirements->data->profile,
            ])
        @endif

        @if ($tutor->requirements->data->account)
            @include('_.partials.profile.requirements-list', [
                'requirements' => $tutor->requirements->data->account,
            ])
        @endif

        @if ($tutor->requirements->data->other)
            <header class="u-mt">
                <h5 class="heading">Optional</h5>
            </header>
            @include('_.partials.profile.requirements-list', [
                'requirements' => $tutor->requirements->data->other,
            ])
        @endif
    </div>

@endif
