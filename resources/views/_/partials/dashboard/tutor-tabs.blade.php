<div class="[ band band--ruled ]">
    <div class="wrapper">
        <div class="layout">
                <div class="layout__item">
                <div class="tal u-mt- ib">
                    <h4 class="delta u-m0 inline">
                        {{ $tutor->first_name }} {{ $tutor->private->last_name }}
                    </h4> - <h5 class="epsilon u-m0 inline">
                        {{ $tutor->status }}
                    </h5>
                    <a href="{{ route('badmin.tutors.edit', ['uuid' => $tutor->uuid ]) }}">
                        <h6 class="inline pill pill--small u-ml {{ $tab == 'personal' ? '' : 'pill--neutral' }}">Personal Info</h6>
                    </a>
                    <a href="{{ route('badmin.tutors.billing', ['uuid' => $tutor->uuid ]) }}">
                        <h6 class="inline pill pill--small u--mv-- {{ $tab == 'billing' ? '' : 'pill--neutral'}} ">Billing</h6>
                    </a>
                    <a href="{{ route('badmin.tutors.messages', ['uuid' => $tutor->uuid ]) }}">
                        <h6 class="inline pill pill--small  u--mv-- {{ $tab == 'messages' ? '' : 'pill--neutral' }} ">Messages</h6>
                    </a>
                    <a href="{{ route('badmin.tutors.lessons', ['uuid' => $tutor->uuid ]) }}">
                        <h6 class="inline pill pill--small u--mv-- {{ $tab == 'lessons' ? '' : 'pill--neutral' }}">Lessons</h6>
                    </a>
                    <a href="{{ route('badmin.tutors.students', ['uuid' => $tutor->uuid ]) }}">
                        <h6 class="inline pill pill--small u--mv-- {{ $tab == 'students' ? '' : 'pill--neutral' }}">Students</h6>
                    </a>
                </div>

                <div class="tar r inline">

                    <form action="{{ route('badmin.tutors.update', ['uuid' => $tutor->uuid]) }}" method="post" class="inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="patch">

                        @if ($tutor->profile->rejected === false)
                            <input type="hidden" name="rejected" value="1">
                            <button class="[ btn btn--error ]">Reject</button>
                        @else
                            <input type="hidden" name="rejected" value="0">
                            <button class="[ btn btn--hollow ]">Rejected</button>
                        @endif
                    </form>

                    @if ($tutor->profile->live === true)
                        <form action="{{ route('badmin.tutors.update', ['uuid' => $tutor->uuid]) }}" method="post" class="inline">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            @if ($tutor->profile->reviewed === false)
                                <input type="hidden" name="reviewed" value="1">
                                <button class="[ btn btn--success ]">Review</button>
                            @else
                                <input type="hidden" name="reviewed" value="0">
                                <button class="[ btn btn--hollow ]">Reviewed</button>
                            @endif
                        </form>
                    @endif

                    <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid ]) }}" class="btn">View profile</a>
                </div>
            </div><!--
            --></div>
    </div>
</div>
