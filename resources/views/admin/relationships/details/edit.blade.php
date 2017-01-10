@extends('admin.relationships._.layouts.show')

@section('show')
    <div class="layout"><!--
        --><div class="[ layout__item ] one-third">
            <form
              method="post"
              action="{{ route('admin.relationships.details.update', ['id' => $relationship->id]) }}"
              class="[ layout ]"
            ><!--
                --><input type="hidden" name="_token" value="{{ csrf_token() }}"><!--
                Statuses
                --><div class="[ layout__item ][ field field--label ]">
                    <label for="status" class="field__label">
                        Statuses
                    </label>
                </div><!--

                --><div class="[ layout__item ] field">
                    <div class="field__input [ select select--full select--squared select--bordered select--show ]">
                        <span class="select__placeholder">Status*</span>
                        <span class="select__value">{{ $relationship->status }}</span>
                        <select name="status" class="select__field">
                            <option value="">Please select..</option>
                            @foreach ([
                                'chatting',
                                'problem',
                                'pending',
                                'confirmed',
                                'rebook',
                                'recurring',
                                'closed',
                            ] as $status)
                                <option
                                    {{ $relationship->status == $status ? 'selected="selected"' : '' }}
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item ] tar u-mt u-mb">
                    <button class="btn">Save</button>
                </div><!--
            --></form>
        </div><!--
    --></div>
@stop
