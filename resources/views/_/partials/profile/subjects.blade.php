@if ($tutor->subjects)
    @if ($is_editable)
        <a href="{{ relroute('tutor.profile.show', [
                'uuid'    => $tutor->uuid,
                'section' => 'subjects'
        ]) }}" data-js class="[ box box--dark ]">
    @else
        <div class="[ box box--dark ]">
    @endif

        <h4 class="heading">
            Subjects

            @if ($is_editable)
                <span class="edit-link edit-link--light u-mt-- r">Edit</span>
            @endif
        </h4>

        <dl class="[ dl dl--brand ] [ js-subjects ]">
            @if (count($tutor->subjects) > 0)
                @foreach ($tutor->subjects as $subject)
                    <dt class="dl__dt">{{ $subject->name }}</dt>
                    <dd class="dl__dd">
                        <ul class="list-inline list-inline--delimited"><!--
                            @if ($subject->children)
                                @foreach ($subject->children as $child)
                                    --><li><!--
                                        --><strong>{{ $child->name }}</strong><!--

                                        @if ($child->children)
                                            --> ({{ implode(', ', array_pluck($child->children, 'name')) }})<!--
                                        @endif
                                    --></li><!--
                                @endforeach
                            @endif
                        --></ul>
                    </dd>
                @endforeach
            @else
                <dd class="dl__dd">
                    @if ($is_editable)
                        You haven&#39;t added any subjects yet. <span class="brand">Add some now?</span>
                    @else
                        This tutor hasn&#39;t added any subjects yet.
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
