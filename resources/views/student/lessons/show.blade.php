@extends('_.layouts.default', [
  'page_class' => 'page--student-lessons'
])

@section('body')
  @include('_.partials.how-it-works')

  <header class="page-head">
  <div class="wrapper">
    @include('_.partials.site-nav')
  </div>
  </header>

  @include('student._.partials.dashboard-nav', [
    'highlight' => 'lessons'
  ])

  <div class="wrapper">
    <h1>Confirm lesson booking</h1>

    <div class="layout"><!--
      --><div class="layout__item two-fifths">
        <div class="table">
          <div class="table__body">

            <div class="table__row layout"><!--
              --><div class="table__cell table__cell--header layout__item one-third">Tutor</div><!--
              --><div class="table__cell layout__item two-thirds">{{ $booking->tutor }}</div><!--
            --></div>

            <div class="table__row layout"><!--
              --><div class="table__cell table__cell--header layout__item one-third">Subject</div><!--
              --><div class="table__cell layout__item two-thirds">{{ $booking->subject }}</div><!--
            --></div>

            <div class="table__row layout"><!--
              --><div class="table__cell table__cell--header layout__item one-third">Date</div><!--
              --><div class="table__cell layout__item two-thirds">{{ $booking->date }}</div><!--
            --></div>

            <div class="table__row layout"><!--
              --><div class="table__cell table__cell--header layout__item one-third">Time</div><!--
              --><div class="table__cell layout__item two-thirds">
                <abbr title="{{ $booking->duration }} in duration">{{ $booking->timeframe }}</abbr>
              </div><!--
            --></div>

            <div class="table__row layout"><!--
              --><div class="table__cell table__cell--header layout__item one-third">Price</div><!--
              --><div class="table__cell layout__item two-thirds">{{ $booking->price }}</div><!--
            --></div>

            @if ($booking->repeats)
              <div class="table__row layout"><!--
                --><div class="table__cell table__cell--header layout__item one-third">Repeats</div><!--
                --><div class="table__cell layout__item two-thirds">{{ $booking->repeats }}</div><!--
              --></div>
            @endif

          </div>
        </div>
      </div><!--

      --><div class="layout__item three-fifths form-region">
        {{--
        <form class="confirm-form" data-stripe="{{ $has_card_on_file ? 'false' : 'true' }}">
          <fieldset class="fieldset">
            <header class="fieldset__header">
              <h5>Confirm</h5>
            </header>

            @if ($has_card_on_file === true)
              <div class="layout"><!--
                --><div class="layout__item one-whole">
                  <p>You've already added your payment details!</p>
                </div><!--

                --><div class="layout__item one-whole tar">
                  <button class="btn btn--primary btn--inline mb0">Confirm</button>
                </div><!--
              --></div>
            @else
              <div class="layout"><!--
                --><div class="layout__item one-whole">
                  <div class="field">
                    <input class="field__input input" type="text" data-name="number" placeholder="Card number">
                    <div class="field__error"></div>
                  </div>
                </div><!--

                --><div class="layout__item one-third">
                  <div class="field">
                    <input class="field__input input" type="text" data-name="cvc" placeholder="CVC">
                    <div class="field__error"></div>
                  </div>
                </div><!--

                --><div class="layout__item two-thirds">
                  <div class="field field--inline one-half">
                    <div class="field__select select select--full select--squared select--bordered">
                      <span class="select__placeholder">Exp month*</span>
                      <span class="select__value"></span>
                      <select class="select__field" data-name="exp_month">
                        <option value="">Please select one:</option>
                        @foreach ([
                          '01' => 'January',
                          '02' => 'Febuary',
                          '03' => 'March',
                          '04' => 'April',
                          '05' => 'May',
                          '06' => 'June',
                          '07' => 'July',
                          '08' => 'August',
                          '09' => 'September',
                          '10' => 'October',
                          '11' => 'November',
                          '12' => 'December',
                        ] as $value => $title)
                          <option value="{{ $value }}">{{ $value }} - {{ $title }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="field__error"></div>
                  </div><!--
                  --><div class="field field--inline one-half">
                    <div class="field__select select select--full select--squared select--bordered">
                      <span class="select__placeholder">Exp year*</span>
                      <span class="select__value"></span>
                      <select class="select__field" data-name="exp_year">
                        <option value="">Please select one:</option>
                        @for ($year = date('Y'), $end = $year + 15; $year <= $end; $year++)
                          <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                      </select>
                    </div>
                    <div class="field__error"></div>
                  </div>
                </div><!--

                --><div class="layout__item one-whole tar">
                  <button class="btn btn--primary btn--inline mb0">Confirm</button>
                </div><!--
              --></div>
            @endif
          </fieldset>
        </form>

      --}}</div><!--
    --></div>
  </div>
@stop
