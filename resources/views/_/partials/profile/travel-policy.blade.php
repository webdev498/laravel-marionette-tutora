@if ($tutor->profile)
    @if ($is_editable)
        <a href="{{ relroute('tutor.profile.show', [
                'uuid'    => $tutor->uuid,
                'section' => 'travel_policy'
        ]) }}" data-js class="[ box box--dark ] [ js-travel-policy ]">
    @else
        <div class="[ box box--dark ] [ js-travel-policy ]">
    @endif
        <h4 class="heading">
            Travel Policy

            @if ($is_editable)
                <span class="edit-link edit-link--light u-mt-- r">Edit</span>
            @endif
        </h4>

        <p>@lang('user_profiles.travel_radius.'.$tutor->profile->travel_radius)</p>

    @if ($is_editable)
        </a>
    @else
        </div>
    @endif
@endif
