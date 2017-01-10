@if ($tutor->qualifications)
    @if ($is_editable)
        <a href="{{ relroute('tutor.profile.show', [
                'uuid'    => $tutor->uuid,
                'section' => 'qualifications'
        ]) }}" data-js class="[ box box--brand ]">
    @else
        <div class="[ box box--brand ]">
    @endif

        <h4 class="heading">
            Qualifications

            @if ($is_editable)
                <span class="edit-link edit-link--light u-mt-- r">Edit</span>
            @endif
        </h4>

        <dl class="[ dl dl--detailed ] [ js-qualifications ]">
            @if ($tutor->qualifications->universities)
                <dt class="dl__dt">University</dt>
                @foreach ($tutor->qualifications->universities as $university)
                    <dd class="dl__dd">
                        {{ $university->subject }} {{ $university->still_studying ? '*' : '' }}
                        <div class="dl__meta">
                            <span class="dl__meta__left">{{ $university->university }}</span>
                            <span class="dl__meta__right">{{ $university->level }}</span>
                        </div>
                    </dd>
                @endforeach
            @endif

            @if ($tutor->qualifications->alevels)
                <dt class="dl__dt">College</dt>
                @foreach ($tutor->qualifications->alevels as $alevel)
                    <dd class="dl__dd">
                        {{ $alevel->subject }} {{ $alevel->still_studying ? '*' : '' }}
                        <div class="dl__meta">
                            <span class="dl__meta__left">{{ $alevel->college }}</span>
                            <span class="dl__meta__right">{{ $alevel->grade }}</span>
                        </div>
                    </dd>
                @endforeach
            @endif

            @if ($tutor->qualifications->others)
                <dt class="dl__dt">Others</dt>
                @foreach ($tutor->qualifications->others as $other)
                    <dd class="dl__dd">
                        {{ $other->subject }} {{ $other->still_studying ? '*' : '' }}
                        <div class="dl__meta">
                            <span class="dl__meta__left">{{ $other->location }}</span>
                            <span class="dl__meta__right">{{ $other->grade }}</span>
                        </div>
                    </dd>
                @endforeach
            @endif

            @if (
                ! $tutor->qualifications->universities &&
                ! $tutor->qualifications->alevels      &&
                ! $tutor->qualifications->others
            )
                <dd class="dl__dd">
                    @if ($is_editable)
                        You haven&#39;t added any qualifications yet. <span class="[ brand brand--dark ]">Add some now?</span>
                    @else
                        This tutor hasn&#39;t added any qualifications yet.
                    @endif
                </dd>

            @endif
        </dl>

    @if ($is_editable)
        </a>
    @else
        </div>
    @endif
@endif
