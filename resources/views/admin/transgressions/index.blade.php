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
            <h4 class="delta u-m0">Transgressions</h4>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout">
            <div class="layout__item">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Message</th>
                            <th>Attempt</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach ($transgressions->data as $transgression)
                            <tr>
                                <td>
                                    
                                @if($transgression->user->roles[0]->name == \App\Role::STUDENT)
                                    <a href="{{ route('admin.students.show', ['uuid' => $transgression->user->uuid]) }}">
                                        {{ $transgression->user->private->name }}
                                    </a>
                                @endif
                                @if($transgression->user->roles[0]->name == \App\Role::TUTOR)
                                    <a href="{{ route('admin.tutors.show', ['uuid' => $transgression->user->uuid]) }}">
                                        {{ $transgression->user->private->name }}
                                    </a>
                                @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.relationships.messages.show', ['uuid' => $transgression->message->relationship->id]) }}">
                                        View Message Stream
                                    </a>
                                </td>
                                
                                <td>
                                    {{ $transgression->body }}
                                </td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                    {!! $transgressions->meta->pagination !!}
                
            </div>
        </div>
    </div>


@stop

