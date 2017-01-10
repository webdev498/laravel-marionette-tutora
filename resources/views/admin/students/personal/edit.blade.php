@extends('admin.students._.layouts.show')

@section('show')
    @if (count($errors) > 0)
        Errors:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="layout"><!--
        --><div class="[ layout__item ] one-third">
            <form
              method="post"
              action="{{ route('admin.students.personal.update', ['uuid' => $student->uuid]) }}"
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
                        <span class="select__value">{{ $student->status }}</span>
                        <select name="status" class="select__field">
                            <option value="">Please select..</option>
                            @foreach ([
                                'new',
                                'chatting',
                                'pending',
                                'confirmed',
                                'rebook',
                                'first',
                                'recurring',
                                'mismatched',
                                'no_message',
                                'expired',
                                'closed',
                            ] as $status)
                                <option
                                    {{ $student->status == $status ? 'selected="selected"' : '' }}
                                >
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field__error"></div>
                </div><!--

                Contact
                --><div class="[ layout__item ][ field field--label ]">
                    <label for="first_name" class="field__label">
                        Name &amp; Contact
                    </label>
                </div><!--

                --><div class="[ layout__item ][ field ] one-half">
                    <input type="text" name="first_name" id="first_name"
                        value="{{ $student->first_name }}" placeholder="First Name"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field ] one-half">
                    <input type="text" name="last_name" id="last_name"
                        value="{{ $student->private->last_name }}" placeholder="Last Name"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field ]">
                    <input type="text" name="email" id="email"
                        value="{{ $student->private->email }}" placeholder="Email"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field ]">
                    <input type="text" name="telephone" id="telephone"
                        value="{{ $student->private->telephone }}" placeholder="Telephone"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                Address
                --><div class="[ layout__item field field--label ]">
                    <label for="address_street" class="field__label">
                        Default Address
                    </label>
                </div><!--

                --><div class="[ layout__item field field--address-street ]">
                    <input type="text" name="addresses[default][line_1]"
                        value="{{ $student->addresses->default->line_1 }}" placeholder="Street"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-town ]">
                    <input type="text" name="addresses[default][line_2]"
                        value="{{ $student->addresses->default->line_2 }}" placeholder="Town"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-city ]">
                    <input type="text" name="addresses[default][line_3]"
                        value="{{ $student->addresses->default->line_3 }}" placeholder="County"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--postcode ]">
                    <input type="text" name="addresses[default][postcode]"
                        value="{{ $student->addresses->default->postcode }}" placeholder="Postcode"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                Billing Address
                --><div class="[ layout__item field field--label ]">
                    <label for="address_street" class="field__label">
                        Billing Address
                    </label>
                </div><!--

                --><div class="[ layout__item field field--address-street ]">
                    <input type="text" name="addresses[billing][line_1]"
                        value="{{ $student->addresses->billing->line_1 }}" placeholder="Street"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-town ]">
                    <input type="text" name="addresses[billing][line_2]"
                        value="{{ $student->addresses->billing->line_2 }}" placeholder="Town"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-city ]">
                    <input type="text" name="addresses[billing][line_3]"
                        value="{{ $student->addresses->billing->line_3 }}" placeholder="County"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--postcode ]">
                    <input type="text" name="addresses[billing][postcode]"
                        value="{{ $student->addresses->billing->postcode }}" placeholder="Postcode"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item ] tar u-mt u-mb">
                    <button class="btn">Save</button>
                </div><!--
            --></form>
        </div><!--
    --></div>
@stop

