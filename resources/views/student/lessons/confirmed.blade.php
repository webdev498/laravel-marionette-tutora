@extends('_.layouts.default', [
  'page_class' => 'page--booking-confirmed'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>
    </header>



    <div class="[ wrapper ]">
        <div class="[ layout layout--center ]">
            <div class="[ layout__item ] message">
                <h2 class="beta">Lesson Confirmed!</h2>
                <p class="large">
                  <b>Your booking is now confirmed. You will shortly receive a confirmation email containing the details of your lesson, and the contact number of your tutor.</b>
                </p>
                <h3 class="gamma u-mt">What happens now?</h3>
                <p class="large">
                  You can view and manage your upcoming lessons by clicking on the "Lessons" link below. Once you've completed your lesson, just ask your tutor to book in another lesson on the website.
                </p>
                <p class="large">Thanks for choosing to learn with Tutora!</p>
                
                <a href="{{ route('student.lessons.index') }}" class="[ btn btn--wide ] u-mt">View my lessons</a>
            </div>
        </div>
    </div>
@stop
