@extends('_.layouts.default', [
    'page_class' => 'page--terms'
])

@section('body')
    <header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav')
        </div>

        <div class="wrapper">
            <div class="[ layout layout--center ] page-head__body"><!--
                --><div class="[ layout__item ] page-head__main">
                    <h1 class="heading beta">Terms &amp; Conditions</h1>
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
                        <a href="#introduction">
                            1. Introduction
                        </a>
                    </li>
                    <li>
                        <a href="#tutora-responsibilities">
                            2. Tutora Rights & Responsibilities
                        </a>
                    </li>
                    <li>
                        <a href="#user-responsibilities">
                            3. User Responsibilities
                        </a>
                    </li>
                    <li>
                        <a href="#student-responsibilities">
                            4. Student Responsibilities
                        </a>
                    </li>
                    <li>
                        <a href="#tutor-responsibilities">
                            5. Tutor Responsibilities
                        </a>
                    </li>
                    <li>
                        <a href="#payment-terms">
                            6. Payment Terms
                        </a>
                    </li>
                    <li>
                        <a href="#cancellations">
                            7. Cancellations
                        </a>
                    </li>
                    <li>
                        <a href="#missed-lesson">
                            8. Missed Lessons
                        </a>
                    </li>
                    <li>
                        <a href="#complaints">
                            9. Complaints
                        </a>
                    </li>
                    <li>
                        <a href="#guarantee">
                            10. 100% Satisfaction Guarantee
                        </a>
                    </li>
                    <li>
                        <a href="#child-protection">
                            11. Child Protection
                        </a>
                    </li>
                    <li>
                        <a href="#disclaimers-and-limitations-of-liability">
                            12. Disclaimers and Limitation of Liability
                        </a>
                    </li>
                    <li>
                        <a href="#intellectual-property">
                            13. Intellectual Property
                        </a>
                    </li>
                    <li>
                        <a href="#changes-to-the-terms-and-conditions">
                            14. Changes to the terms and conditions
                        </a>
                    </li>
                    <li>
                        <a href="#governing-law-and-jurisdiction">
                            15. Governing Law &amp; Jurisdiction
                        </a>
                    </li>
                    <li>
                        <a href="#definitions">
                            16. Definitions
                        </a>
                    </li>
                </ul>
            </div><!--

            --><div class="[ layout__item ] kb__content">
                <h5 class="epsilon u-mb-" id="introduction">1. Introduction</h5>

                <p>
                    <strong>1.1</strong> Tutora is a Website that helps Students find Tutors, and to book and pay for Lessons.
                </p>

                <p>
                    <strong>1.2</strong> This Website is owned and operated by Tutora Ltd. We are a limited company registered in England and Wales under company number 9370702. Our registered office is at 52 Joshua Road, Sheffield, South Yorkshire, S7 1GY. Our other contact details are specified on our Website.
                </p>

                <p>
                    <strong>1.3</strong> These terms and conditions cancel and replace any previous versions.
                </p>

                <p>
                    <strong>1.4</strong> By registering on, or using our Website, you agree to be bound by these terms and conditions. (Please print or save these terms for future use as we will not keep a file copy specifically for the transaction with you and we cannot guarantee that they will remain accessible on our Website in future.) If you do not wish to be bound by these terms, you should not use the Website.

                </p>

                <p>
                    <strong>1.5</strong> Where you communicate on behalf of a company/organisation, you promise that you have authority to act on behalf of that entity.
                </p>

                <p>
                    <strong>1.6</strong> Particular terms of importance are defined at the end of this document and are capitalised throughout.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="tutora-responsibilities">2. Tutora Rights & Responsibilities</h5>

                <p>
                    <strong>2.1</strong> To help Tutors and Students meet, by allowing Tutors to advertise their professional tuition services.
                </p>

                <p>
                    <strong>2.2</strong> To maintain a functioning Website, including communication systems, a booking platform and payment processing.
                </p>

                <p>
                    <strong>2.3</strong> To communicate any planned maintenance of the Website or downtime.
                </p>

                <p>
                    <strong>2.4</strong> To reasonably endeavour to keep the site free from viruses and worms.
                </p>

                <p>
                    <strong>2.5</strong> To reasonably endeavour to check the identity of all Tutors, and the DBS disclosures of Tutors with DBS Checked Status.
                </p>

                <p>
                    <strong>2.6</strong> To decide when a Tutor’s profile is sufficiently complete to be set live and be accessible by other Users.
                </p>

                <p>
                    <strong>2.7</strong> To communicate new Bookings, confirmation of Bookings, and Cancellations, to both Students and Tutors via email.
                </p>

                <p>
                    <strong>2.8</strong> To process each Student’s payments on behalf of the Tutor promptly, and usually within 7 days of a lesson, when there is no Complaint.
                </p>

                <p>
                    <strong>2.9</strong> To investigate Complaints to a reasonable degree by promptly informing Students and Tutors of issues raised and allowing them to represent themselves by email.
                </p>

                <p>
                    <strong>2.10</strong> To process refunds promptly in the event that lessons do not take place, a Complaint is upheld and under the terms of our Money Back Guarantee.

                </p>

                <p>
                    <strong>2.11</strong> To take precautions we consider reasonable to protect Student’s and Tutor’s information. Tutora cannot be held liable for unauthorised access to information by Students or Tutors.

                </p>

                <p>
                    <strong>2.12</strong> To monitor postings made on the Website and messages sent between Students and Tutors. We do so in the hope of helping Students and Tutors meet, but also to stop mis-use of the site, as set out in these terms and conditions.
                    
                </p>

                <h5 class="epsilon u-mb- u-mt" id="user-responsibilities">3. User Responsibilities</h5>

                <p>
                    As defined at the end of this document, a “User” means a person who uses 
                    our Website (whether or not registered with us).
                </p>

                <p>
                    <strong>3.1</strong> Users are responsible for their own security in conjunction with our services, both online and offline.

                </p>

                <p>
                    <strong>3.2</strong> Users are solely responsible for the material they post on the Website, including messages sent, and must not post defamatory, offensive or illegal material.

                </p>

                <p>
                    <strong>3.3</strong> Users must immediately report any defamatory, offensive or illegal material they view on our Website.

                </p>

                <p>
                    <strong>3.4</strong> Users must exercise their own judgement regarding the accuracy of information provided on the Website. Tutora cannot guarantee that all of the content on the Website is complete, accurate or up-to-date.

                </p>
                <p>
                    <strong>3.5</strong> Users are responsible for their own internet security when using the Website.
                </p>

                <p>
                    <strong>3.6</strong> Users must contact Tutora immediately if they believe their password has been compromised. Users will be responsible for the actions of any interactions conducted in their name until they have notified Tutora.

                </p>

                <p>
                    <strong>3.7</strong> Users must not use the site with the intention of disintermediating Tutora in any way.  Users must not use the Website with the intention of disintermediating Tutora in any way.  Users must not promote opportunities or services of any company other than Tutora.
                </p>

                <p>
                    <strong>3.8</strong> Users agree to abide by the Privacy Policy provided through the Website. 

                    <strong>3.9</strong> Any User who fails to meet these terms and conditions may be barred from using the Website and related services.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="student-responsibilities">4. Student Responsibilities</h5>

                <p>
                    As defined at the end of this document, a “Student” means a User who has registered a Student account on the Website.

                </p>

                <p>
                    <strong>4.1</strong> Tutora takes no responsibility for the actions of Students or Tutors and is solely responsible for its own actions.

                </p>

                <p>
                    <strong>4.2</strong> Students must be 18 years old to book a Tutor for themselves, or be represented by a parent or legal guardian who gives consent for them to receive tuition.  Students must ensure that all of their personal details and contact information are accurate and up-to-date. Tutora is not responsible for any dispute regarding parental consent.

                </p>

                <p>
                    <strong>4.3</strong> Students have up to 12 hours before their first Lesson with a new Tutor is due to commence to confirm the booking. To confirm a booking, Students must enter valid payment details on our Website. In confirming their first lesson with a Tutor, the Student authorises the Tutor to book future sessions on their behalf and Tutora to take payment for each Lesson, in accordance with the rest of these Terms and Conditions.

                </p>

                <p>
                    <strong>4.4</strong> When a booking is confirmed, Students enter directly into a contract with the Tutor. The terms and conditions within this document cover all interaction between the Students and Tutors.

                </p>

                <p>
                    <strong>4.5</strong> When a confirmed booking exists, Students must ensure that they have a valid debit/credit card registered on our Website, with sufficient funds to cover the bookings they have confirmed.

                </p>

                <p>
                    <strong>4.6</strong> Students must not pay the Tutor directly. All payments must be made through the Tutora Website.

                </p>

                <p>
                    <strong>4.7</strong> Students agree that there will be no attempts to disintermediate Tutora in any way, either at the time of booking or at any point in future and that all future Lessons with a Tutor found through the Website will be booked through Tutora.

                </p>

                <p>
                    <strong>4.8</strong> Students must ensure that they have given enough information to the Tutor, including their correct address, to allow the Lesson to take place. If a Lesson is to take place at the Student’s home, the Student must ensure a suitable setting for tuition.

                </p>

                <p>
                    <strong>4.9</strong> Students must use their own judgement about the services of Tutors detailed on the Website. Students are responsible for checking the credentials, expertise, references, qualifications and insurance policies of any Tutor with whom they confirm a booking.

                </p>

                <p>
                    <strong>4.10</strong> Students are responsible for checking that Lessons booked by Tutors on their behalf are done so correctly and understand they will be informed of these Lessons by email and through the Website only.
                </p>

                <p>
                    <strong>4.11</strong> Any Student who fails to meet these Terms and Conditions may be immediately barred from using the Website and related services. Tutora reserves the right to cancel any existing bookings.
                </p>

         

                <h5 class="epsilon u-mb- u-mt" id="tutor-responsibilities">5. Tutor Responsibilities</h5>

                <p>
                    As defined at the end of this document, “Tutor” means a User who has registered a Tutor account on the Website.

                </p>

                <p>
                    <strong>5.1</strong> Tutors are not employees of Tutora and are solely responsible for their own actions both on and off the Website.
                </p>

                <p>
                    <strong>5.2</strong> Tutors must be at least 18 years old.

                </p>

                <p>
                    <strong>5.3</strong> Tutors must be legally entitled to work in the UK.

                </p>

                <p>
                    <strong>5.4</strong> If Tutors contact Students who are under 18, they must ensure that these learners are represented by a parent or legal guardian who gives consent for the Student to receive tuition.
                </p>

                <p>
                    <strong>5.5</strong> Tutors are responsible for ensuring that the personal information they provide and their personal statements on the Website are accurate and in no way misleading. They must update this information to maintain its accuracy.
                </p>
                
                <p>
                    <strong>5.6</strong> Tutors must disclose any criminal convictions or cautions they may have to Tutora.
                </p>
                
                <p>
                    <strong>5.7</strong> Tutors claiming DBS Checked Status must meet the requirements set out in term 14.6 of this document.

                </p>

                <p>
                    <strong>5.8</strong> Tutors must use their own judgement about whether they wish to offer their tuition services to each individual Student. Tutors should take every precaution to ensure that they work in a safe environment and are responsible for their own insurance policies to cover the work they undertake.
                </p>

                <p>
                    <strong>5.9</strong> Tutors are responsible for setting their own fee. They must not undercut Tutora and must, therefore, offer a fee which is as good as that offered to other Students outside of Tutora. Tutors must not knowingly charge a fee which is not in line with their level of expertise or experience.
                </p>

                <p>
                    <strong>5.10</strong> Tutors agree that they will be ranked based on a mixture of profile data, Student ratings and number of lessons taught. Positive Student ratings, prompt messaging, repeat bookings and a greater amount of lessons taught will contribute to tutors being ranked higher on our search engine results.
                </p>

                <p>
                    <strong>5.11</strong> Tutors should confirm bookings and respond to messages received from Tutora and Users promptly.

                </p>

                <p>
                    <strong>5.12</strong> Any lessons given to a Student found via Tutora must be booked on and paid for using the Tutora website. Tutors should make no attempts to solicit Students or disintermediate Tutora in any way, either at the time of booking or at any point in future. Tutors will be charged £50 for every student with whom they have a lesson which is not booked through the Tutora platform, regardless of the cost of lessons or the numbers of lessons given.

                </p>

                <p>
                    <strong>5.13</strong> Tutors must only book Lessons in accordance with the instructions of their Students. If Tutors book additional Lessons which they fail to deliver, they will be liable to repay any funds received as a result.
                </p>

                <p>
                    <strong>5.14</strong> Tutors must not complete coursework, or any similar assignments, on behalf of Students.
                </p>

                <p>
                    <strong>5.15</strong> Tutors must ensure that all written communication with Students takes place on the Website.
                </p>

                <p>
                    <strong>5.16</strong> Tutors shall indemnify Tutora for all claims and liabilities arising out of any use by the Tutor of the Website, including costs and expenses incurred.
                </p>

                <p>
                    <strong>5.17</strong> Any Tutor who fails to meet these Terms and Conditions may be immediately barred from using the Website and related services. Tutora reserves the right to cancel any existing bookings, at any time.

                </p>

                <h5 class="epsilon u-mb- u-mt" id="payment-terms">6. Payment Terms</h5>

                <p>
                    <strong>6.1</strong>  The fee payable by a Student to a Tutor for a Lesson will be as set out on the Tutor’s profile page on our Website and be confirmed at the time of confirming a Booking. 
                </p>

                <p>
                    <strong>6.2</strong> Prices include any applicable VAT or other sales tax, unless otherwise stated.
                </p>

                <p>
                    <strong>6.3</strong> Students authorise us to charge their credit/debit card 24 hours after the scheduled end time of each Lesson.
                </p>

                <p>
                    <strong>6.4</strong> Students agree that Tutora will take a commission of between 15-25%. The specific rate will be set based on the number of lessons taught and the commission structure agreed with the Tutor.
                </p>

                <p>
                    <strong>6.5</strong> Payment processing services for Students on Tutora are provided by Stripe and are subject to the Stripe Connected Account Agreement, which includes the Stripe Terms of Service (collectively, the “Stripe Services Agreement”). By agreeing to these terms or continuing to operate as a Student on Tutora, you agree to be bound by the Stripe Services Agreement, as the same may be modified by Stripe from time to time. As a condition of Tutora enabling payment processing services through Stripe, you agree to provide Tutora accurate and complete information about you and your business, and you authorize Tutora to share it and transaction information related to your use of the payment processing services provided by Stripe.

                </p>

                <p>
                    <strong>6.6</strong> Tutors are normally paid within 8 days of a Lesson.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="cancellations">7. Cancellations</h5>

                <p>
                    <strong>7.1</strong> A Student may cancel a Booking up to 12 hours before a lesson takes place. Cancellations made more than 12 hours before a Booking is due to commence will incur no charge.
                </p>

                <p>
                    <strong>7.2</strong> To cancel a lesson less than 12 hours before a booking is due to commence, the Student must inform the Tutor giving the Lesson. We leave it to the Tutor's discretion whether to oblige the cancellation request, or to charge the lesson at 50% of the Tutor's hourly rate, as advertised at the time of booking. Tutora will automatically apply a 50% charge, unless instructed otherwise.
                </p>

                <p>
                    <strong>7.3</strong> Tutors may cancel a Booking at any time prior to a Lesson taking place. To cancel a Lesson, Tutors must inform the Student directly via the Website. In the event of a Tutor cancelling, the Student will receive a full refund.
                </p>

                <p>
                    <strong>7.4</strong> Cancellations must be made through the Tutora Website. Cancellations made through direct contact, text, or phone, and not through the Website, may still result in charges being made to a Student’s account.

                </p>

                <p>
                    <strong>7.5</strong> Bookings cannot be cancelled after the Lesson has started.
                </p>

                <p>
                    <strong>7.6</strong> If a Lesson does not take because a Student does not show up, the Student will be charged the full Lesson price. 
                </p>

                <p>
                    <strong>7.7</strong> If a Lesson does not take place because a Tutor does not show up, Students are responsible for informing Tutora of this fact within 48 hours of the scheduled finish time. Once this has been confirmed, the Student will be fully refunded for the price of the Lesson.
                </p>

                <h5 class="epsilon u-mb- u-mt" id="missed-lesson">8. Missed Lesson</h5>

                <p>
                    <strong>8.1</strong> As defined at the end of this document, a “Missed Lesson” means a claim by a Student that they have not received a lesson booked on the Website.

                </p>

                <p>
                    <strong>8.2</strong> A Student must inform Tutora of a Missed Lesson by phone or email within 48 hours of the scheduled finish time of a Lesson, to be entitled to a refund. 


                </p>

                <p>
                    <strong>8.3</strong> When a Student informs Tutora of a Missed Lesson, Tutora will contact the Tutor within 72 hours by email and text message to inform them that a Missed Lesson claim has been raised. 


                </p>

                <p>
                    <strong>8.4</strong> To contest a Missed Lesson claim, Tutors must contact Tutora by phone or email within 48 hours of being informed of the claim.


                </p>
                <p>
                    <strong>8.5</strong> If the Tutor agrees that there has been a Missed Lesson, or fails to contest a claim raised by the Student within 48 hours of the scheduled finish time of the Lesson, Tutora will process a full refund for the cost of the Missed Lesson.

                </p>
                <p>
                    <strong>8.6</strong> If a Tutor contests a Missed Lesson claim within 48 hours of being informed, Tutora will seek to resolve the claim with both the Student and Tutor within 7 days, and has the right to withhold payment to the Tutor for up to 7 days to do so.

                </p>
                <p>
                    <strong>8.7</strong> If a Student raises a Missed Lesson claim after 48 hours of the scheduled finish time but within 30 days, refunds will be awarded solely at the discretion of Tutora.

                </p>
                <p>
                    <strong>8.8</strong> Any Missed Lesson claim made after 30 days of the scheduled finish time of the Lesson will not be processed by Tutora.

                </p>
                
                <h5 class="epsilon u-mb- u-mt" id="complaints">9. Complaints</h5>

                <p>
                    <strong>9.1</strong> As defined at the end of this document, “Complaint” means a claim by a Student that the service provided by a Tutor falls below the expected standards of the terms and conditions governing them.
                </p>
                <p>
                    <strong>9.2</strong> If a Student wishes to make a Complaint, they must contact Tutora within 48 hours of the scheduled finish time of the Lesson.
                </p>
                <p>
                    <strong>9.3</strong> Tutora will contact the Tutor to inform them of a Complaint being raised and seek to ensure the highest level of service is provided.
                </p>
                <p>
                    <strong>9.4</strong> Refunds will be awarded solely at the discretion of Tutora. Tutora always seeks to make sure that Students are fully satisfied with the service provided.
                </p>

                <h5 class="epsilon u-mb- u-mt" id="guarantee">10. 100% Satisfaction Guarantee</h5>

                <p>
                    <strong>10.1</strong> To qualify for this offer, Clients must notify Tutora that they are unhappy with a Tutor after their first session by email or phone and within 48 hours of the Lesson’s scheduled finish time. 


                </p>

                <p>
                    <strong>10.2</strong> Clients are entitled to credit against further Lessons booked through Tutora, to match the amount paid for the first hour of tuition only, up to a maximum of £100. If the initial lesson was booked for less than one hour, the Client will receive credit to match the price of this lesson, and no more.
                </p>

                <p>
                    <strong>10.3</strong> Credits must be used within 12 months of being awarded and are only redeemable against the next hour of tuition.
                </p>

                <p>
                    <strong>10.4</strong>  In the event that we provide credit to the Client, the Tutor will still receive their normal rate of pay for the initial lesson, minus our commission.
                </p>

                <p>
                    <strong>10.5</strong> Tutora reserves the right not to offer credit, if any other Term and Condition is broken by the Student or Tutor.
                </p>

                <h5 class="epsilon u-mb- u-mt" id="child-protection">11. Child Protection</h5>

                <p>
                    <strong>11.1</strong> Tutora seeks to provide the best service possible and provide a safe experience in which children can learn.

                </p>

                <p>
                    <strong>11.2</strong> Tutors should not be left in sole care of children.

                </p>

                <p>
                    <strong>11.3</strong> All Users must comply with the TTA Child Protection Policy and all relevant legislation and government guidance.

                </p>

                <p>
                    <strong>11.4</strong>  If any User has a concern regarding child protection, they should contact Tutora immediately.

                </p>

                <p>
                    <strong>11.5</strong> The designated Child Protection Officer is Scott Woodley.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="disclaimers-and-limitations-of-liability">12. Disclaimers and Limitation of Liability</h5>

                <p>
                    <strong>12.1</strong> Nothing in this agreement in any way limits or excludes our liability for negligence causing death or personal injury or for anything which may not legally be excluded or limited.

                </p>

                <p>
                    <strong>12.2</strong> You must give us a reasonable opportunity to remedy any matter for which we are potentially liable before you incur any costs remedying the matter yourself.

                </p>

                <p>
                    <strong>12.3</strong> If you are a User, we shall not be liable for any loss or damage caused by us or our employees or agents in circumstances where:


                    <ul class="u-m0">
                        <li>
                            there is no breach of a legal duty of care owed to you by us or by any of our
                            employees or agents;
                        </li>
                        <li>
                            such loss or damage was not reasonably foreseeable by both parties;
                        </li>
                        <li>
                            such loss or damage is caused by you, for example by not complying
                            with this agreement;
                        </li>
                    </ul>
                </p>

                <p>
                    <strong>12.4</strong> If you are a User, you will be liable for any reasonably foreseeable loss 
                    or damage we suffer arising from your breach of this agreement or misuse of 
                    our Website (subject of course to our obligation to mitigate any losses).
                </p>

                <p>
                    <strong>12.5</strong> The following clauses apply only if you are not a Consumer:

                    <ul class="u-m0">
                        <li>
                            To the extent allowed by law, you and we exclude all terms, whether 
                            imposed by statute or by law or otherwise, that are not expressly stated 
                            in this agreement. In this clause, any reference to us includes our 
                            employees and agents.
                        </li>

                        <li>
                            Our liability of any kind (including our own negligence) with respect to 
                            our Website for any one event or series of related events is limited to 
                            £100 or the total fees payable by you in the 12 months before the 
                            event(s) complained of, whichever is higher.
                        </li>

                        <li>
                            In no event (including our own negligence) will we be liable for any:
                            <ul>
                                <li>
                                    economic losses (including, without limit, loss of revenues, 
                                </li>

                                <li>
                                    profits, contracts, business or anticipated savings);
                                </li>

                                <li>
                                    loss of goodwill or reputation;
                                </li>

                                <li>
                                    special, indirect or consequential losses; or
                                </li>

                                <li>
                                    damage to or loss of data (even if we have been advised of the 
                                    possibility of such losses).
                                </li>
                            </ul>
                        </li>

                        <li>
                            You will indemnify us against all claims and liabilities directly or 
                            indirectly related to your use of the Website and/or breach of this 
                            agreement.
                        </li>

                        <li>
                            This agreement constitutes the entire agreement between us with 
                            respect to its subject matter and supercedes any previous 
                            communications or agreements between us. We both acknowledge 
                            that there have been no misrepresentations and that neither of us has 
                            relied on any pre-contractual statements. Liability for misrepresentation 
                            (excluding fraudulent misrepresentation) relating to the terms of this 
                            agreement is excluded.
                        </li>
                    </ul>
                </p>

                <h5 class="epsilon u-mb- u-mt" id="intellectual-property">13. Intellectual Property</h5>

                <p>
                    <strong>13.1</strong> When submitting material to Tutora, Students and Tutors also grant a non-exclusive, royalty-free, non-terminable licence to copy, modify, distribute, show in public and create derivative works from that material.

                </p>


                <h5 class="epsilon u-mb- u-mt" id="changes-to-the-terms-and-conditions">14. Changes to the terms and conditions</h5>

                <p>
                    <strong>14.1</strong> We may change these terms and conditions by posting the revised version on our Website at least 14 days before they become effective. Please check our Website from time to time. You will be bound by the revised agreement, if you continue to use our Website or the Services following the effective date shown.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="governing-law-and-jurisdiction">15. Governing Law &amp; Jurisdiction</h5>

                <p>
                    <strong>15.1</strong> These terms and conditions shall be governed by English law and any 
                    disputes will be decided only by the courts of the United Kingdom.
                </p>

                <p>
                    <strong>15.2</strong> If any clause or part of these terms and conditions are found to be 
                    unenforceable in law, the other terms and conditions will remain in force.
                </p>


                <h5 class="epsilon u-mb- u-mt" id="definitions">16. Definitions</h5>

                <p>
                    <strong>16.1</strong> “Tutora” means the company Tutora Ltd and its website. Any reference to ‘we’, ‘us’, ‘our’, etc. refers to the company Tutora Ltd.

                </p>

                <p>
                    <strong>16.2</strong> “Website” means the website on the domain https://tutora.co.uk.

                </p>

                <p>
                    <strong>16.3</strong> “User” means a person who uses our Website (whether or not they have registered an account with us). 

                </p>

                <p>
                    <strong>16.4</strong> “Student” means a User who has registered a Student account on the Website.

                </p>

                <p>
                    <strong>16.5</strong> “Tutor” means a User who has registered a Tutor account on the Website.

                </p>

                <p>
                    <strong>16.6</strong> “DBS Checked Status” means a Tutor displaying the ‘I have a DBS check’ badge on their profile page, DBS standing for Disclosure and Barring Service. This status has no relation to any claim by Tutors elsewhere on their profile or in the messages they send. DBS checks must have been awarded within 2 years of the date at which a Tutor creates an account on the Website, and within 3 years at any time thereafter. Within these time frames, DBS checks will be accepted if they are an enhanced check provided by the Disclosure and Barring Service, Disclosure Scotland or Access Northern Ireland.
 
                </p>

                <p>
                    <strong>16.7</strong> “Lesson” means a one-to-one tuition lesson between a Student and a Tutor.

                </p>

                <p>
                    <strong>16.8</strong> “Review” means any review, comment or rating.

                </p>

                <p>
                    <strong>16.9</strong> “Content” means all information of whatever kind (including information, Service Provider listings, profiles, reviews), published, stored or sent on or in connection with our Website.

                </p>

                <p>
                    <strong>16.10</strong> “Content” means all information of whatever kind (including information, 
                    Service Provider listings, profiles, reviews), published, stored or sent on or in 
                    connection with our Website.
                </p>

                <p>
                    <strong>16.11</strong> “Complaint” means a claim by a Student that the service provided by a Tutor falls below the expected standards of the terms and conditions governing them.
                </p>
            </div><!--
        --></div>
    </div>
@stop
