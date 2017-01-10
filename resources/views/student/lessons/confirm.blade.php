@extends('_.layouts.default', [
  'page_class' => 'page--booking-confirm'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>

    <div class="[ band band--ruled ]">
        <div class="wrapper">
            <div class="layout"><!--
                --><div class="layout__item">
                    <h4 class="delta u-m0">Confirm lesson booking</h4>
                </div><!--
            --></div>
        </div>
    </div>

    <div class="wrapper [ js-confirm ]">
        <form class="[ js-form ]">
            <div class="layout lesson"><!--
                --><div class="layout__item lesson__details">
                    <h4 class="delta">Lesson details</h4>

                    <div class="lesson__details__subject">
                        <strong>{{ $booking->lesson->subject->title }}</strong>
                    </div>
                    <div class="lesson__details__date">
                        {{ $booking->date->long }}{{ $booking->lesson->schedule ? '*' : '' }}
                    </div>
                    <div class="lesson__details__time">
                        <em>
                            <abbr title="{{ $booking->duration }} in duration">
                                {{ $booking->time->start }} - {{ $booking->time->finish }}
                            </abbr>
                        </em>
                        with {{ $booking->lesson->relationship->tutor->name }}.
                    </div>

                    @if ($booking->lesson->schedule)
                        <div class="lesson__details__repeat">
                            * Repeating {{ $booking->lesson->schedule->description }}
                        </div>
                    @endif
                </div><!--

                --><div class="layout__item lesson__payment">
                    <h4 class="delta u-mb">Payment</h4>
                    <p>To confirm your booking, please enter your payment details.</p>

                    <div class="layout"><!--
                        @if ($user->last_four !== null)
                            --><div class="[ layout__item ] u-mb-">
                                <div class="flag flag--small">
                                    <div class="flag__img">
                                        <span class="icon icon--fancy-tick"></span>
                                    </div>
                                    <div class="flag__body">
                                        <p class="brand">Already added!</p>
                                    </div>
                                </div>
                            </div><!--

                            --><input type="hidden" value="1" class="js-card-exists"><!--
                        @else
                            --><input type="hidden" value="0" class="js-card-exists"><!--
                        @endif

                        --><div class="[ layout__item field field--card-number ]">
                            <input type="text"
                            placeholder="{{ $user->last_four ? 'XXXX XXXX XXXX '.$user->last_four : 'Card Number' }}"
                            class="[ input input--full input--squared input--bordered ] field__input [ js-card-number ]">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="[ layout__item field field--cvc ]">
                            <input type="text"
                            placeholder="{{ $user->last_four ? 'XXX' : '3 Digit Security Code' }}"
                            class="[ input input--full input--squared input--bordered ] field__input [ js-card-cvc ]">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="[ layout__item field field--exp-month ]">
                            <div class="field__input [ select select--full select--squared select--bordered ]">
                                <span class="select__placeholder">{{ $user->last_four ? 'XX' : 'Exp Month*' }}</span>
                                <span class="select__value"></span>
                                <select class="select__field [ js-card-expiry-month ]">
                                    <option value="">Please select one:</option>
                                    <option value="01">01 January</option>
                                    <option value="02">02 Febuary</option>
                                    <option value="03">03 March</option>
                                    <option value="04">04 April</option>
                                    <option value="05">05 May</option>
                                    <option value="06">06 June</option>
                                    <option value="07">07 July</option>
                                    <option value="08">08 August</option>
                                    <option value="09">09 September</option>
                                    <option value="10">10 October</option>
                                    <option value="11">11 November</option>
                                    <option value="12">12 December</option>
                                </select>
                            </div>

                            <div class="field__error"></div>
                        </div><!--

                        --><div class="[ layout__item field field--exp-year ]">
                            <div class="field__input [ select select--full select--squared select--bordered ]">
                                <span class="select__placeholder">{{ $user->last_four ? 'XX' : 'Exp Year*' }}</span>
                                <span class="select__value"></span>
                                <select class="select__field [ js-card-expiry-year ]">
                                    <option value="">Please select one:</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>

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
                                           class="[ input input--full input--squared input--bordered ] [ js-address-line-1 ] field__input">
                                    <div class="field__error"></div>
                                </div><!--

                        --><div class="[ layout__item field field--address-town ]">
                                    <input type="text" name="addresses[billing][line_2]"
                                           value="{{ $student->addresses->billing->line_2 }}" placeholder="Town"
                                           class="[ input input--full input--squared input--bordered ] [ js-address-line-2 ] field__input">
                                    <div class="field__error"></div>
                                </div><!--

                        --><div class="[ layout__item field field--address-city ]">
                                    <input type="text" name="addresses[billing][line_3]"
                                           value="{{ $student->addresses->billing->line_3 }}" placeholder="County"
                                           class="[ input input--full input--squared input--bordered ] [ js-address-line-3 ] field__input">
                                    <div class="field__error"></div>
                                </div><!--

                        --><div class="[ layout__item field field--postcode ]">
                                    <input type="text" name="addresses[billing][postcode]"
                                           value="{{ $student->addresses->billing->postcode }}" placeholder="Postcode"
                                           class="[ input input--full input--squared input--bordered ] [ js-address-postcode ] field__input">
                                    <div class="field__error"></div>
                        </div><!--
                    --></div>

                    <div class="[ box box--info ] u-mt">
                        <div class="[ flag flag--top ]">
                            <div class="flag__image">
                                <div class="icon icon--24h--large u-mr"></div>
                            </div>

                            <div class="flag__body">
                                <p>Your card will not be charged until 24 hours after your session has been completed, but taking your details now allows our tutors to teach with confidence. This is a secure payment system and your details are protected. Remember, all lessons are protected by our 100% money back guarantee.</p>
                            </div>
                        </div>
                    </div>
                </div><!--

                --><div class="layout__item lesson__price">
                    <h4 class="delta">Price</h4>
                    <div class="lesson__details__date">{{ $booking->price }}</div>

                    <button class="btn btn--wide u-mt [ js-submit ]">Confirm</button>
                </div><!--

            --></div>
        </form>
    </div>
@stop
