@extends('_.layouts.default', [
    'page_class' => 'page--messages page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0">Messages</h4>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="[ tabs tabs--full ]">
            <ul class="tabs__list">
                <li class="tabs__item">
                    <a href="{{ relroute('admin.messages.index') }}"
                        class="[ tabs__link @if ( ! $filter) tabs__link--active @endif ]"
                    >
                        All
                    </a>
                </li>
                @foreach ([
                    'flagged'    => 'Flagged',
                   
                ] as $key => $value)
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.messages.index', ['filter' => $key]) }}"
                        class="[ tabs__link @if ($filter === $key) tabs__link--active @endif ]"
                        >
                            {{ $value }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item">
                <div class="osx">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody class="[ js-messages-index-region ]">
                            @if ($lines->meta->count > 0)

                                @foreach ($lines->data as $line)
                                    <tr id="item-{{ $line->id }}">
                                        <td>
                                            <div class="layout">
                                                <div class="layout__item one-quarter">
                                                    <!-- Tutor -->
                                                    Tutor:
                                                    <a
                                                            href="{{ route('admin.tutors.show', [
                                                'uuid' => $line->tutor->uuid
                                              ]) }}"
                                                            >
                                                        {{ $line->tutor->private->name }}
                                                    </a>
                                                </div><!--
                                                --><div class="layout__item one-quarter">
                                                    Student:
                                                    <!-- Student -->
                                                    <a
                                                            href="{{ route('admin.students.show', [
                                                'uuid' => $line->student->uuid
                                              ]) }}"
                                                            >
                                                        {{ $line->student->private->name }}
                                                    </a>
                                                </div><!--
                                                --><div class="layout__item one-quarter">
                                                    <!-- Last message time sent -->
                                                    
                                                    <abbr title="{{ $line->time->long }}">
                                                        {{ $line->time->medium }}
                                                    </abbr>
                                                    
                                                </div><!--
                                                --><div class="layout__item one-quarter">
                                                    {{ @trans('relationships.statuses')[$line->relationship->status] }}
                                                </div>
                                            </div>

                                            <!-- Last message -->
                                            
                                                <a
                                              href="{{ route('admin.relationships.messages.show', [
                                                'id' => $line->relationship->id,
                                              ]) }}"
                                            >{!! $line->long_body !!}</a>
                                            
                                        </td>
                                        <!-- Flag -->
                                        <td class="u-vam">
                                            <i class="action__icon fa fa-flag [ js-flag ] {{ $line->flag ? 'active' : '' }}"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($lines->meta->count > 0)
                    {!! $lines->meta->pagination !!}
                @endif
            </div><!--
        --></div>
    </div>
@stop
