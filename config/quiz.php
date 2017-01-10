<?php

return
[
    [
        'question' => 'How can I find new students?',
        'key' => 'q1',
        'answers' => 
        [
            ['title' => 'With a magnifying glass.', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'I\'m afraid not!'],
            ['title' => 'Receiving new enquiries through my profile.', 'key' => 'ans2', 'is_correct' => true],
            ['title' => 'With the assistance of penguins (we love penguins).', 'key' => 'ans3', 'is_correct' => false, 'if_wrong' => 'Penguins aren\'t that helpful'],
        ]
    ],

    [
        'question' => 'Why are you not allowed to exchange contact details until a lesson is booked and confirmed?',
        'key' => 'q2',
        'answers' => 
        [
            ['title' => 'Because Tutora’s penguins cannot operate phones.', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'Unfortunately not!'],
            ['title' => 'Because this helps us match more students and tutors by stepping in when it’s not a good fit - we can’t do that if the conversation goes off the site.', 'key' => 'ans2', 'is_correct' => true],
            ['title' => 'Because students ask too many tricky questions and it scares us.', 'key' => 'ans3', 'is_correct' => false, 'if_wrong' => 'We don\'t scare that easily'],
        ]
    ],
    [
        'question' => 'When you have agreed a lesson with a student or parent, who is responsible for booking the lesson?',
        'key' => 'q3',
        'answers' => 
        [
            ['title' => 'The Parent or Student - they can demand lessons at any time they please.', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'Student\'s cannot book lessons, so it is up to you the tutor to book the lessons in'],
            ['title' => 'The Tutor - because you know when you are free to teach.', 'key' => 'ans2', 'is_correct' => true],
            ['title' => 'Tutora - just give us a call!', 'key' => 'ans3', 'is_correct' => false, 'if_wrong' => 'We can certainly book lessons, but it is easier if you book them in yourself'],
        ]
    ],
    [
        'question' => 'How will I know if a student has confirmed a lesson and what happens if they don’t?',
        'key' => 'q4',
        'answers' => 
        [
            ['title' => 'A penguin will be left on your doorstep. You should welcome them in and they will explain all.', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'Penguins cannot speak'],
            ['title' => 'We will text and email you to let you know 12 hours in advance. If it is not confirmed, you should go to the lesson anyway.', 'key' => 'ans2', 'is_correct' => false, 'if_wrong' => 'You should not go to lessons that have been cancelled, as you will not be paid'],
            ['title' => 'We will text and email you to let you know 12 hours in advance. If it is not confirmed, you should not go to the lesson because you will not get paid.', 'key' => 'ans3', 'is_correct' => true],
        ]
    ],
    [
        'question' => 'Why don’t we allow you to organise free initial lessons or meetings?',
        'key' => 'q5',
        'answers' => 
        [
            ['title' => 'Because parents can view your profile, message you to ask any questions and we offer a 100% satisfaction guarantee.', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'True, but not the correct answer'],
            ['title' => 'Because preparing and travelling to lessons takes time. You are working professionals and should be paid for the valuable service you provide.', 'key' => 'ans2', 'is_correct' => false, 'if_wrong' => 'True, but not the correct answer'],
            ['title' => 'Both of the above (‘Hooray!’ shout the penguins).', 'key' => 'ans3', 'is_correct' => true],
        ]
    ],
    [
        'question' => 'How must students or parents pay for lessons?',
        'key' => 'q6',
        'answers' => 
        [
            ['title' => 'Only by credit/debit card through our secure online payment system.', 'key' => 'ans1', 'is_correct' => true],
            ['title' => 'With cash (or fish).', 'key' => 'ans2', 'is_correct' => false, 'if_wrong' => 'Our tutors do not accept payment by cash'],
            ['title' => 'However the parent or student prefers.', 'key' => 'ans3', 'is_correct' => false, 'if_wrong' => 'Our tutors do not accept payment by cash'],
        ]
    ],
    [
        'question' => 'What happens if you or the student needs to cancel a lesson?',
        'key' => 'q7',
        'answers' => 
        [
            ['title' => 'Let the other person know by messaging them. Cancel the lesson on the site or ask us to cancel it for you.', 'key' => 'ans1', 'is_correct' => true],
            ['title' => 'You can’t cancel lessons, the penguins get angry.', 'key' => 'ans2', 'is_correct' => false, 'if_wrong' => 'Just not true'],
            ['title' => 'Just let each other know.', 'key' => 'ans3', 'is_correct' => false, 'if_wrong' => 'You must cancel lessons on the system, otherwise the student will still be charged.'],
        ]
    ],
    [
        'question' => 'How do you know when you have been paid?',
        'key' => 'q8',
        'answers' => 
        [
            ['title' => 'Check on your completed lessons.', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'Try again.'],
            ['title' => 'We will send you an email to let you know you’ve been paid', 'key' => 'ans2', 'is_correct' => false, 'if_wrong' => 'Try again.'],
            ['title' => 'Both of the above', 'key' => 'ans3', 'is_correct' => true],
        ]
    ],
    [
        'question' => 'How do I get more students?',
        'key' => 'q9',
        'answers' => 
        [
            ['title' => 'By booking in more lessons - the more you book, the more enquiries we send your way', 'key' => 'ans1', 'is_correct' => false, 'if_wrong' => 'Not quite!'],
            ['title' => 'By responding to enquiries quickly.', 'key' => 'ans2', 'is_correct' => false, 'if_wrong' => 'Not quite!'],
            ['title' => 'By writing a great profile!', 'key' => 'ans3', 'is_correct' => false, 'if_wrong' => 'Not quite!'],
            ['title' => 'All of the above', 'key' => 'ans4', 'is_correct' => true],
        ]
    ],

];

