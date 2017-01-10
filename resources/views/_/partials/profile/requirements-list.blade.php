<ul class="list-bare">
    @foreach ($requirements as $requirement)
        <li
          class="
            requirements__item
          "
          data-requirement="{{ $requirement->section }}:{{ $requirement->name }}"
          {{ $requirement->is_optional ? 'data-requirement-optional' : '' }}
        >
            @if ($requirement->url)
                <a href="{{ $requirement->url }}" {{ $requirement->is_js ? 'data-js' : ''}}
                  class="requirements__title {{ $requirement->is_completed ? 'requirements__title--completed' : '' }}"
                >
                    {{ $requirement->title }}
{{--                    {{ $requirement->is_optional ? '*' : '' }}--}}
                </a>
            @else
                {{ $requirement->title }}
            @endif
        </li>
    @endforeach
</ul>
