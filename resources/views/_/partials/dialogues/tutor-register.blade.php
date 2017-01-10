<div class="dialogue dialogue--register @if (isset($open) && $open === true) dialogue--open @endif">
    <div class="dialogue__container">
        <div class="dialogue__window">
            <header class="dialogue__header">
                <h5 class="dialogue__title">Sign Up</h5>
                <a href="{{ route('home') }}" class="icon icon--cross dialogue__close"></a>
            </header>

            <form method="post" action="/api/users" class="dialogue__content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input class="input" type="text" name="first_name" placeholder="First Name" @if (App::environment('local')) value="Aaron" @endif>
                <input class="input" type="text" name="last_name" placeholder="Last Name" @if (App::environment('local')) value="Lord" @endif>
                <input class="input" type="email" name="email" placeholder="Email" @if (App::environment('local')) value="aaronlord1@gmail.com" @endif>
                <input class="input" type="password" name="password" placeholder="Password" @if (App::environment('local')) value="secret" @endif>
                <div class="radios"><!--
                    --><div class="radios__item">
                        Student
                    </div><!--
                    --><div class="radios__item  radios__item--checked">
                        Tutor
                    </div><!--
                --></div>

                <input type="hidden" name="account_type" value="tutor">

                <button class="btn btn--primary btn--large mt">Create Account</button>

                <p class="small-print">By clicking this button you&#39;re agreeing to our <a href="#">Terms &amp; Conditions</a></p> 
            </form>

            <nav class="dialogue__nav">
                <a href="{{ route('login.create') }}">Login<span class="icon icon--nav-right--dark"></span></a>
            </nav>
        </div>
    </div>

    <div class="dialogue__overlay"></div>
</div>

