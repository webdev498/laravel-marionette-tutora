<footer class="page-foot">
    <div class="wrapper">
        <div class="layout"><!--
            --><div class="layout__item page-foot__links">
                <header>
                    <h4>Tutora</h4>
                </header>

                <ul class="list-bare">
                    <li><a href="{{ route('about.index') }}">About Us</a></li>
                    <li><a href="{{ route('about.index') }}#contact-us">Contact us</a></li>
                    {{-- <li><a href="{{ route('subjects.index') }}">Browse Subjects</a></li> --}}
                    <li><a href="#" class="js-how-it-works">How it works</a></li>
                    <li><a href="{{ route('locations.index') }}">Browse Cities</a></li>
                    <li><a href="{{ route('subjects.index') }}">Browse Subjects</a></li>
                    <li><a href="{{ route('tutoring.jobs.index') }}">Tutoring Jobs</a></li>
                </ul>
            </div><!--

            --><div class="layout__item page-foot__links">
                <header>
                    <h4>Students</h4>
                </header>

                <ul class="list-bare">
                    <li><a href="{{ relroute('register.student') }}" data-js>Sign Up</a></li>
                    <li><a href="{{ route('faqs.student') }}">Student FAQs</a></li>
                </ul>
                </br>
                <header>
                    <h4>Tutors</h4>
                </header>

                <ul class="list-bare">
                    <li><a href="{{ relroute('register.tutor') }}" data-js>Become a Tutor</a></li>
                    <li><a href="{{ route('faqs.tutor') }}">Tutor FAQs</a></li>
                </ul>
            </div><!--

            
            --><div class="layout__item page-foot__links">
                <header>
                    <h4>Popular Cities</h4>
                </header>

                <ul class="list-bare">
                    {{-- <li><a href="#">Refer your friends</a></li> --}}

                    <li><a href="{{ route('search.location', ['sheffield']) }}">Sheffield Tutors</a></li>
                    <li><a href="{{ route('search.location', ['london']) }}">London Tutors</a></li>
                    <li><a href="{{ route('search.location', ['nottingham']) }}">Nottingham Tutors</a></li>
                    <li><a href="{{ route('search.location', ['manchester']) }}">Manchester Tutors</a></li>
                    <li><a href="{{ route('search.location', ['birmingham']) }}">Birmingham Tutors</a></li>
                    <li><a href="{{ route('search.location', ['liverpool']) }}">Liverpool Tutors</a></li>
                    <li><a href="{{ route('search.location', ['glasgow']) }}">Glasgow Tutors</a></li>
                    <li><a href="{{ route('search.location', ['edinburgh']) }}">Edinburgh Tutors</a></li>
                    <li><a href="{{ route('search.location', ['newcastle']) }}">Newcastle Tutors</a></li>
                    <li><a href="{{ route('search.location', ['leeds']) }}">Leeds Tutors</a></li>
                </ul>
            </div><!--    

            --><div class="layout__item page-foot__links">
                <header>
                    <h4>Resources</h4>
                </header>

                <ul class="list-bare">
                    {{-- <li><a href="#">Refer your friends</a></li> --}}

                    <li><a href="{{ route('terms.index') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('policy.privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('articles.index') }}">Articles</a></li>

                </ul>
            </div><!--

            --><div class="layout__item page-foot__social">
                <header>
                    <h4>Connect with us</h4>
                </header>

                <ul class="list-inline">
                    <li>
                        <a href="https://www.facebook.com/tutorauk">
                            <span class="icon icon--disc icon--facebook"></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/tutorauk">
                            <span class="icon icon--disc icon--twitter"></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://plus.google.com/+TutoraUk/about">
                            <span class="icon icon--disc icon--gplus"></span>
                        </a>
                    </li>
                </ul>
            </div><!--
        --></div>
    </div>
</footer>

{{-- Adroll --}}
<script type="text/javascript">
    adroll_adv_id = "2RVA75QCVZA63K77TA2HJB";
    adroll_pix_id = "PZ3SPTQ5YFB3RJLMEMB7NR";
    /* OPTIONAL: provide email to improve user identification */
    /* adroll_email = "username@example.com"; */
    (function () {
        var _onload = function(){
            if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
            if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/j/roundtrip.js";
            ((document.getElementsByTagName('head') || [null])[0] ||
                document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        };
        if (window.addEventListener) {window.addEventListener('load', _onload, false);}
        else {window.attachEvent('onload', _onload)}
    }());
</script>
