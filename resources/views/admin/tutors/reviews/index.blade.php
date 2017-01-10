@extends('admin.tutors._.layouts.edit')

@section('show')
    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.reviews.index', ['uuid' => $tutor->uuid]) }}"
                           class="[ tabs__link @if ($status === 'active') tabs__link--active @endif ]"
                                >
                            Active
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.tutors.reviews.index', ['uuid' => $tutor->uuid, 'status' => 'deleted']) }}"
                           class="[ tabs__link @if ($status === 'deleted') tabs__link--active @endif ]"
                                >
                            Deleted
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="layout"><!--
        --><div class="layout__item">
            <div class="oxs list-region">

            </div>
        </div><!--
    --></div>
@stop