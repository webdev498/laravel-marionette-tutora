@extends('_.layouts.default', [
    'page_class' => 'page--relationships page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Relationships</h4>
        </div>
    </div>

    <div class="wrapper u-mt">
        <form method="post" action="{{ route('admin.relationships.store') }}" class="layout layout--center"><!--
            --><div class="layout__item one-third">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="field field--label">
                    <label for="tutor" class="field__label">Tutor</label>
                </div>
                <div class="field field--tutor">
                    <input type="text" name="tutor" class="[ field__input ][ input input--full input--squared input--bordered ][ js-autocomplete ]" data-autocomplete="tutors">
                </div>

                <div class="field field--label">
                    <label for="tutor" class="field__label">Student</label>
                </div>
                <div class="field field--student">
                    <input type="text" name="student" class="[ field__input ][ input input--full input--squared input--bordered ][ js-autocomplete ]" data-autocomplete="students">
                </div>

                <div>
                    <button class="btn btn--small btn--full">Create Relationship</button>
                </div>
            </div><!--
        --></form>
    </div>
@stop
