@extends('_.layouts.default', [
    'page_class' => 'page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Booking Score</h4>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout">
            <div class="layout__item">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tutor</th>
                            <th>Booking Score</th>
                            <th>View in Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tutors->meta->count > 0)
                            @foreach ($tutors->data as $tutor)
                                <tr>
                                    <td>
                                        <a href="{{ route('tutor.profile.show', ['uuid' => $tutor->uuid]) }}">
                                            {{ $tutor->private->name }}
                                        </a><br>
                                        {{ $tutor->uuid }}
                                    </td>
                                    <td>
                                        {{ $tutor->profile->booking_score }}
                                    </td>
                                    
                                    <td class="u-vam">
                                        <a href="{{ route('admin.tutors.show', ['uuid' => $tutor->uuid])}}">
                                            <span title="Show Tutor" class="icon icon--eye"></span>
                                        </a>
                                    </td>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
            </div>
        </div>
    </div>


@stop

