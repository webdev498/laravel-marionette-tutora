@extends('admin.tutors._.layouts.edit')

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
        --><div class="[ layout__item ] two-thirds">
            <form
              method="post"
              action="{{ route('admin.tutors.personal.update', ['uuid' => $tutor->uuid]) }}"
              class="[ layout ]"
            ><!--
            --><input type="hidden" name="_token" value="{{ csrf_token() }}"><!--
                Statuses
                --><div class="[ layout__item ][ field field--label ]">
                    <label for="status" class="field__label">
                        Statuses
                    </label>
                </div><!--

                --><div class="[ layout__item one-half ] field">
                    <div class="field__input [ select select--full select--squared select--bordered select--show ]">
                        <span class="select__placeholder">Status*</span>
                        <span class="select__value">{{ $tutor->profile->status }}</span>
                        <select name="profile[status]" class="select__field">
                            <option value="">Please select..</option>
                            @foreach ([
                                App\UserProfile::LIVE,
                                App\UserProfile::OFFLINE,
                                App\UserProfile::EXPIRED,
                            ] as $status)
                                <option @if ($tutor->profile->status === $status) selected @endif>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item one-half ] field">
                    <div class="field__input [ select select--full select--squared select--bordered select--show ]">
                        <span class="select__placeholder">Admin status*</span>
                        <span class="select__value">{{ $tutor->profile->admin_status }}</span>
                        <select name="profile[admin_status]" class="select__field">
                            <option value="">Please select..</option>
                            @foreach ([
                                App\UserProfile::PENDING,
                                App\UserProfile::OK,
                                App\UserProfile::REJECTED,
                            ] as $status)
                                <option @if ($tutor->profile->admin_status === $status) selected @endif>
                                    {{ $status }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="field__error"></div>
                </div><!--
                
                Summary (for SEO)
                --><div class="[ layout__item field field--label ]">
                    <label for="address_street" class="field__label">
                        Summary (for SEO)
                    </label>
                </div><!--

                --><div class="[ layout__item field ]">
                    <input type="text" name="profile[summary]" id="summary"
                        value="{{ $tutor->profile->summary }}" placeholder="Summary"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--
                --><div class="layout__item">
                    <div class="layout"><!--
                        --><div class="layout__item one-half">
                            <div class="layout"><!--
                                Quality
                                --><div class="[ layout__item ][ field field--label ]">
                                    <label for="quality" class="field__label">
                                        Quality
                                    </label>
                                </div><!--

                                --><div class="[ layout__item ][ field ]">
                                    <input type="text" name="profile[quality]" id="quality"
                                        value="{{ $tutor->profile->quality }}" placeholder="Quality"
                                        class="[ input input--full input--squared input--bordered ] field__input">
                                    <div class="field__error"></div>
                                </div><!--
                            --></div>
                        </div><!--

                        --><div class="layout__item one-half">
                            <div class="layout"><!--
                                Featured
                                --><div class="[ layout__item ][ field field--label ]">
                                    <label for="quality" class="field__label">
                                        Featured
                                    </label>
                                </div><!--

                                --><div class="[ layout__item field ] one-half">
                                    <div class="field__checkbox checkbox {{ $tutor->profile->is_featured ? 'checkbox--checked' : '' }}">
                                        <div class="checkbox__box">
                                            <div class="checkbox__tick"></div>
                                            <input type="hidden" name="profile[featured]" value="0">
                                            <input type="checkbox" name="profile[featured]" value="1" class="checkbox__input" {{ $tutor->profile->is_featured ? 'checked=checked' : '' }}">
                                        </div>
                                        <label class="checkbox__label">Featured</label>
                                    </div>
                                    <div class="field__error"></div>
                                </div><!--
                            --></div>
                        </div><!--
                    --></div>
                </div><!--

                Contact
                --><div class="[ layout__item ][ field field--label ]">
                    <label for="first_name" class="field__label">
                        Name &amp; Contact
                    </label>
                </div><!--

                --><div class="[ layout__item ][ field ] one-half">
                    <input type="text" name="first_name" id="first_name"
                        value="{{ $tutor->first_name }}" placeholder="First Name"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field ] one-half">
                    <input type="text" name="last_name" id="last_name"
                        value="{{ $tutor->private->last_name }}" placeholder="Last Name"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field ]">
                    <input type="text" name="email" id="email"
                        value="{{ $tutor->private->email }}" placeholder="Email"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field ]">
                    <input type="text" name="telephone" id="telephone"
                        value="{{ $tutor->private->telephone }}" placeholder="Telephone"
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
                        value="{{ $tutor->addresses->default->line_1 }}" placeholder="Street"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-town ]">
                    <input type="text" name="addresses[default][line_2]"
                        value="{{ $tutor->addresses->default->line_2 }}" placeholder="Town"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-city ]">
                    <input type="text" name="addresses[default][line_3]"
                        value="{{ $tutor->addresses->default->line_3 }}" placeholder="County"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--postcode ]">
                    <input type="text" name="addresses[default][postcode]"
                        value="{{ $tutor->addresses->default->postcode }}" placeholder="Postcode"
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
                        value="{{ $tutor->addresses->billing->line_1 }}" placeholder="Street"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-town ]">
                    <input type="text" name="addresses[billing][line_2]"
                        value="{{ $tutor->addresses->billing->line_2 }}" placeholder="Town"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--address-city ]">
                    <input type="text" name="addresses[billing][line_3]"
                        value="{{ $tutor->addresses->billing->line_3 }}" placeholder="County"
                        class="[ input input--full input--squared input--bordered ] field__input">
                    <div class="field__error"></div>
                </div><!--

                --><div class="[ layout__item field field--postcode ]">
                    <input type="text" name="addresses[billing][postcode]"
                        value="{{ $tutor->addresses->billing->postcode }}" placeholder="Postcode"
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
