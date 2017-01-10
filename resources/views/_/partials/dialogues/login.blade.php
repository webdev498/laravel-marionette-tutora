<div class="dialogue @if (isset($open) && $open === true) dialogue--open @endif">
    <div class="dialogue__wrapper wrapper">
        <div class="layout layout--center"><!--
            --><div class="layout__item dialogue__container dialogue__container--tiny">
                <div class="dialogue__window">
                    <header class="dialogue__header">
                        <h4 class="heading">Log In</h4>
                        <a href="#" class="close icon icon--cross"></a>
                    </header>

                    <form class="layout"><!--
                        --><div class="layout__item field">
                            <input type="email" class="field__input input input--full input--squared input--bordered" name="email" placeholder="Email">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item field">
                            <input type="password" class="field__input input input--full input--squared input--bordered" name="password" placeholder="Password">
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item field u-mt-">
                            <div class="field__checkbox checkbox checkbox--checked">
                                <div class="checkbox__box">
                                    <div class="checkbox__tick"></div>
                                    <input type="checkbox" name="remember_me" id="remember_me" value="1" class="checkbox__input" checked="checked">
                                </div>
                                <label class="checkbox__label">Remember me</label>
                            </div>
                            <div class="field__error"></div>
                        </div><!--

                        --><div class="layout__item u-mt-">
                            <button class="btn btn--full btn--large">Log In</button>
                        </div><!--

                        --><div class="layout__item u-mt tac">
                            <a href="#" class="tiny">Lost Password?</a>
                        </div><!--
                    --></form>

                    <nav class="dialogue__nav">
                        <a href="#" class="epsilon">Sign Up<span class="icon icon--nav-right--dark"></span></a>
                    </nav>
                </div>
            </div><!--
        --></div>
    </div>

    <div class="dialogue__overlay"></div>
</div>

