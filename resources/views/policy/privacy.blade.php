@extends('_.layouts.default', [
    'page_class' => 'page--policy'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Privacy Policy</h1>
                </div><!--

                --><div class="[ layout__item ] page-head__aside">
                    <a href="{{ route('home') }}" class="btn btn--full">Find a tutor</a>
                </div><!--
            --></div>
        </div>
    </header>

    <div class="wrapper">
        <div class="[ layout layout--center ] kb"><!--
            --><div class="[ layout__item ] kb__links">
                <ul class="[ list-ui list-ui--bare list-ui--compact ]">
                    <li>
                        <a href="#information-that-we-collect">
                            Information That We Collect
                        </a>
                    </li>
                    <li>
                        <a href="#use-of-cookies">
                            Use of Cookies
                        </a>
                    </li>
                    <li>
                        <a href="#use-of-your-information">
                            Use of Your Information
                        </a>
                    </li>
                    <li>
                        <a href="#storing-your-personal-data">
                            Storing Your Personal Data
                        </a>
                    </li>
                    <li>
                        <a href="#disclosing-your-information">
                            Disclosing Your Information
                        </a>
                    </li>
                    <li>
                        <a href="#third-party-links">
                            Third Party Links
                        </a>
                    </li>
                    <li>
                        <a href="#access-to-information">
                            Access to Information
                        </a>
                    </li>
                    <li>
                        <a href="#contacting-us">
                            Contacting Us
                        </a>
                   </li>
                </ul>
            </div><!--

            --><div class="[ layout__item ] kb__content">
                
                <p>
                    This privacy policy is for this website Tutora.co.uk and served by Tutora Ltd and governs the privacy of its users who choose to use it.  The policy sets out the different areas where user privacy is concerned and outlines the obligations and requirements of the users, the website and website owners. Furthermore the way this website processes, stores and protects user data and information will also be detailed within this policy.
                </p>
                
                <h5 class="epsilon u-mb-" id="information-that-we-collect">Information That We Collect</h5>

                <p>
                    In running and maintaining our website we may collect and process the following data about you:
                    <ul class="u-m0">
                        <li>
                            Information about your use of our site including details of your visits such as pages viewed and the resources that you access. Such information includes traffic data, location data and other communication data.
                        </li>
                        <li>
                            Information provided voluntarily by you. For example, when you register for information or book a lesson, communicate through the site, or when you contact us by email or telephone.  
                        </li>
                        <li>
                            Technical information used to identify you, such as login information, your IP address and the browser used to access the site.  
                        </li>
                        <li>
                            We also work closely with third parties (including, for example, business partners, sub-contractors in technical, payment and delivery services, advertising networks, analytics providers, search information providers, credit reference agencies) and may receive information about you from them.
                        </li>
                    </ul>
                </p>    
                <p>
                    All payment card information is handled by a third party.  We do not handle any payment card information ourselves, and are not PCI compliant.  
                </p>

                <h5 class="epsilon u-mb- u-mt" id="use-of-cookies">Use of Cookies</h5>

                <p>
                    Cookies are small bits of text that are downloaded to your computer or mobile device when you visit a website. Your browser sends these cookies back to the website every time you visit the site again, so it can recognise you and can then tailor what you see on the screen.  We use cookies for two main purposes:
                    
                    <ul class="u-m0">
                        <li>
                            For the site to operate we use cookies to, for example, save login information, record when you have returned to the site, and during the booking and payment processes.
                        </li>
                        <li>
                            For analytical purposes to record visits to the site, to see how visitors navigate through the site, and to record which pages have been visited.  We may use third party cookies for this purpose. 
                        </li>
                    </ul>
                </p>

                <p>
                    You may block cookies by activating a setting on your browser that allows you to refuse the setting of all or some cookies. By doing so, you may not be able to access all or parts of our site.
                </p>
                <p>
                    For more information please read the advice at AboutCookies.org.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="use-of-your-information">Use of Your Information</h5>

                <p>
                    We will use the information you give us:
                    <ul class="u-m0">
                        <li>
                            to carry out our obligations arising from any contracts entered into between you and us and to provide you with the information, products and/or services that you request from us;
                        </li>
                        <li>
                            to provide you with information about us (including other products and services we offer) and to send you newsletters;
                        </li>
                        <li>
                            to notify you about changes to our service;
                        </li>
                        <li>
                            to help us communicate with you effectively should you try to contact us via the site, including verification of any claims you may make; and
                        </li>
                        <li>
                            to ensure that content from the site is presented in the most effective manner for you and for your computer or device.
                        </li>
                    </ul>
                </p>

                <p>
                    We may use your personal data to send you emails (or other communications) with details of our or third party products or services which may be of interest to you, including information about special offers or promotions. If you would prefer not to receive any such emails or other communications, please tell us by emailing us info@tutora.co.uk.
                </p>

                <p>
                    We will use any information collected about you automatically when you visit the site: 
                    <ul class="u-m0">
                        <li>
                            to administer the site and for internal operations, including troubleshooting, data analysis, testing, research, statistical and survey purposes;
                        </li>
                        <li>
                            to improve the site and to ensure that content is presented in the most effective manner for you and for your computer or device;

                        </li>
                        <li>
                            to allow you to participate in any interactive features of our service, when you choose to do so;
                        </li>
                        <li>
                            as part of our efforts to keep the site safe and secure; 
                        </li>
                        <li>
                            to make suggestions and recommendations to you and other users of the site about products or services that may interest you or them;
                        </li>
                        <li>
                            to provide information to you relating to other products that may be of interest to you. Such additional information will only be provided where you have consented to receive such information; and
                        </li>
                        <li>
                            to inform you of any changes to our website, services or goods and products.
                        </li>
                    </ul>
                </p>

                <p>
                    We may combine information we receive from other sources with information you give to us and information we collect about you. We may use this information and the combined information for the purposes set out above (depending on the types of information we receive).
                </p>

                <p>
                   We retain personal data from closed accounts in order to comply with legal obligations, enforce our terms and conditions, prevent fraud, collect any fees owed, resolve disputes, troubleshoot problems, assist with any investigations and take other actions as permitted by law.
                </p>

                <p>
                    <strong>We never give your details to third parties to use your data to enable them to provide you with information regarding unrelated goods or services.</strong>
                </p>


                <h5 class="epsilon u-mb- u-mt" id="storing-your-personal-data">Storing Your Personal Data</h5>

                <p>
                    In operating our website it may become necessary to transfer data that we collect from you to locations outside of the European Union for processing and storing. By providing your personal data to us, you agree to this transfer, storing and processing. We do our utmost to ensure that all reasonable steps are taken to make sure that your data is stored securely.
                </p>

                <p>
                    Unfortunately the sending of information via the internet is not totally secure and on occasion such information can be intercepted. We cannot guarantee the security of data that you choose to send us electronically, sending such information is entirely at your own risk.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="disclosing-your-information">Disclosing Your Information</h5>

                <p>
                    We will not disclose your personal information to any other party other than in accordance with this Privacy Policy and in the circumstances detailed below:
                    
                    <ul class="u-m0">
                        <li>
                            In the event that we sell any or all of our business to a buyer.
                        </li>
                        <li>
                            Where we are legally required by law to disclose your personal information.
                        </li>
                        <li>
                            To further fraud protection and reduce the risk of fraud.
                        </li>
                    </ul>
                
                </p>


                <h5 class="epsilon u-mb- u-mt" id="third-party-links">Third Party Links</h5>

                <p>
                    On occasion we include links to third parties on this website. Where we provide a link it does not mean that we endorse or approve that site’s policy towards visitor privacy. You should review their privacy policy before sending them any personal data.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="access-to-information">Access to Information</h5>

                <p>
                    In accordance with the Data Protection Act 1998 you have the right to access any information that we hold relating to you. Please note that we reserve the right to charge a fee of £10 to cover costs incurred by us in providing you with the information.
                </p>

              
                <h5 class="epsilon u-mb- u-mt" id="contacting-us">Contacting Us</h5>

                <p>
                    Please do not hesitate to contact us regarding any matter relating to this Privacy and Cookies Policy via email at info@tutora.co.uk.
                </p>

            </div><!--
        --></div>
    </div>
@stop
