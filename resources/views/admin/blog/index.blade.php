@extends('_.layouts.default', [
    'page_class' => 'page--blog page--admin'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <h4 class="delta u-m0" style="display: inline-block">Blog</h4>
            <a
                          href="{{ route('admin.blog.article.create') }}"
                          class="[ btn btn--small ] u--mt--" style="float:right;"
                        >
                            Create
                        </a>
                    </div>
        </div>
    </div>

    <div class="[ band band--ruled band--flush ]">
        <div class="wrapper">
            <div class="[ tabs tabs--full ]">
                <ul class="tabs__list">
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.blog.index') }}"
                          class="[ tabs__link tabs__link--active ]">
                            All articles
                        </a>
                    </li>
                    <li class="tabs__item">
                        <a href="{{ relroute('admin.blog.index') }}" class="[ tabs__link--active ]"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="wrapper u-mt">
        <div class="layout"><!--
            --><div class="layout__item">
                <table class="table">
                    <thead>
                        <tr>
                            <th >Title</th>
                            <th>Body</th>
                            <th>Author</th>
                            <th>Date of publish</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($articles->meta->count > 0)
                            @foreach ($articles->data as $article)
                                <tr>
                                    <!-- Title -->
                                    <td>{{ $article->title }}</td>
                                    <!-- Body of article -->
                                    <td>{{ substr (strip_tags($article->body), 0, 100) }}...</td>
                                    <!-- Author -->
                                    <td>
                                        {{ $article->user->private->name }}
                                        <br> {{$article->user->uuid}}
                                    </td>
                                    <!-- Date of publish -->
                                    <td>{{ $article->published_at }}</td>
                                    <!-- Status  -->
                                    @if($article->published==1 )
                                        <td>published</td>
                                    @else
                                     <td>not published</td>
                                    @endif
                                    <!-- Edit -->
                                     <td class="u-vam">
                                        <a href="{{ route('admin.blog.article.show', ['id' => $article->id])}}">
                                            <span title="Show article" class="icon icon--edit"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="8">
                                No results
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div><!--
        --></div>
    </div>
@stop
