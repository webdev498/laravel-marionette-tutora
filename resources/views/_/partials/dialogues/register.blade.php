<div class="dialogue @if (isset($open) && $open === true) dialogue--open @endif">
    <div class="dialogue__wrapper wrapper">
        <div class="layout layout--center"><!--
            --><div class="layout__item dialogue__container dialogue__container--tiny">
                <div class="dialogue__window">
                    <header class="dialogue__header">
                        <h4 class="heading">Sign Up</h4>
                        <a href="#" class="close icon icon--cross"></a>
                    </header>

                    <form class="layout"><!--
                        --><div class="layout__item field">
                            <input type="text" name="first_name" placeholder="First Name" class="field__input input input--full input--squared input--bordered">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item field">
                            <input type="text" name="last_name" placeholder="Last Name" class="field__input input input--full input--squared input--bordered">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item field">
                            <input type="email" name="email" placeholder="Email" class="field__input input input--full input--squared input--bordered">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item field">
                            <input type="tel" name="telephone" placeholder="Mobile Number" class="field__input input input--full input--squared input--bordered">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item field">
                            <input type="password" name="password" placeholder="Password" class="field__input input input--full input--squared input--bordered">
                            <div class="field__error"></div>
                        </div><!--
                        
                        --><div class="layout__item u-mt-">
                            <button class="btn btn--full btn--large">Create Account</button>
                        </div><!--

                        --><div class="layout__item u-mt tac">
                            <p class="tiny">By clicking this button you&#39;re agreeing to our <a href="#">Terms &amp; Conditions</a></>
                        </div><!--
                    --></form>

                    <nav class="dialogue__nav">
                        <a href="#" class="epsilon">Log In<span class="icon icon--nav-right--dark"></span></a>
                    </nav>
                </div>
            </div><!--
        --></div>
    </div>

    <div class="dialogue__overlay"></div>
</div>

