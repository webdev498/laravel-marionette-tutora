var _       = require('underscore');
var should  = require('should');
var moment  = require('moment');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

var date = moment().add(2, 'days');

describe('Tutor lesson booking', function () {
    before(function (done) {
        helpers.login.tutor(done);
    });

    after(function (done) {
        helpers.logout(done);
    });

    describe('I can access the create booking dialogue', function () {
        describe('with a click', function () {
            before(function (done) {
                browser.visit('/tutor/lessons', done);
            });

            it('should have a clickable link', function () {
                browser.clickLink('Book a Lesson');
            });

            it(
                'should have the dialogue open',
                helpers.lessons.tests.it.shouldHaveTheCreateDialogueOpen
            );
        });

        describe('via url navigation', function () {
            before(function (done) {
                browser.visit('/tutor/lessons/create', done);
            });

            it('should be a successful visit', function () {
                browser.assert.success();
            });

            it(
                'should have the dialogue open',
                helpers.lessons.tests.it.shouldHaveTheCreateDialogueOpen
            );
        });
    });

    describe('I can book', function () {
        after(function (done) {
            helpers.migrate(done);
        });

        describe('a single lesson', function () {
            before(function (done) {
                helpers.migrate(function () {
                    browser.visit('/tutor/lessons/create', done);
                });
            });

            it('should be a successful visit', function () {
                helpers.tests.it.shouldBeASuccessfulVisit()
            });

            it(
                'should have the create dialogue open',
                helpers.lessons.tests.it.shouldHaveTheCreateDialogueOpen
            );

            it('should fill in the the form', function () {
                helpers.lessons.tests.it.shouldFillTheCreateForm(
                    'Melissa Lord',
                    'Maths',
                    'Location location location',
                    date.format('DD/MM/YYYY'),
                    '11:30',
                    '1:00',
                    'never'
                );
            });

            it('should submit the form', function (done) {
                browser.pressButton('.dialogue .js-submit', done);
            });

            describe('the booking should now be listed', function () {
                it(
                    'should be on the tutor lessons index page',
                    helpers.lessons.tests.it.shouldBeOnTheTutorLessonsIndexPage
                );

                it('should list 1 lesson', function () {
                    browser.assert.elements('.table tbody tr', 1);
                });

                it('should show the lesson booking', function () {
                    var booking = {
                        1 : date.format('Do MMM'),
                        2 : '11:30 - 12:30',
                        4 : 'Melissa Lord',
                        5 : 'Maths'
                    };

                    _.each(booking, function (value, key) {
                        browser.assert.text(
                            '.table tbody tr:first-child td:nth-child(' + key + ')',
                            value
                        );
                    });
                });
            });

            describe('I should have recieved a booking message', function () {
                before(function (done) {
                    browser.visit('/tutor/messages/melissa.lord', done);
                });

                it(
                    'should redirect me to the message',
                    helpers.messages.tests.it.shouldRedirectMeToTheMessage
                );

                it('should have sent a message confirming the booking', function () {
                    helpers.messages.tests.assert.lastMessage(
                        'Aaron has booked a lesson in Maths.'
                    );
                });
            });
        });

        describe('a weekly lesson', function (done) {
            before(function (done) {
                helpers.migrate(function () {
                    browser.visit('/tutor/lessons/create', done);
                });
            });

            it('should be a successful visit', function () {
                helpers.tests.it.shouldBeASuccessfulVisit()
            });

            it(
                'should have the create dialogue open',
                helpers.lessons.tests.it.shouldHaveTheCreateDialogueOpen
            );

            it('should fill in the the form', function () {
                helpers.lessons.tests.it.shouldFillTheCreateForm(
                    'Melissa Lord',
                    'Maths',
                    'Location location location',
                    date.format('DD/MM/YYYY'),
                    '11:30',
                    '1:00',
                    'weekly'
                );
            });

            it('should submit the form', function (done) {
                browser.pressButton('.dialogue .js-submit', done);
            });

            describe('the booking should now be listed', function () {
                before(function (done) {
                    browser.visit('/tutor/lessons', done);
                });

                it(
                    'should be on the tutor lessons index page',
                    helpers.lessons.tests.it.shouldBeOnTheTutorLessonsIndexPage
                );

                it('should list 10 lessons', function () {
                    browser.assert.evaluate('$(".table tbody tr").length', 10);
                });

                it('should show the lesson bookings', function () {
                    for (var i = 0, j = 10; i < j; i++) {
                        var booking = {
                            1 : date.clone().add(i, 'weeks').format('Do MMM'),
                            2 : '11:30 - 12:30',
                            4 : 'Melissa Lord',
                            5 : 'Maths'
                        };

                        _.each(booking, function (value, column) {
                            browser.assert.text(
                                '.table tbody tr:nth-child(' + (i + 1) + ') td:nth-child(' + column + ')',
                                value
                            );
                        });
                    };
                });
            });

            describe('I should have recieved a booking message', function () {
                before(function (done) {
                    browser.visit('/tutor/messages/melissa.lord', done);
                });

                it(
                    'should redirect me to the message',
                    helpers.messages.tests.it.shouldRedirectMeToTheMessage
                );

                it('should have sent a message confirming the booking', function () {
                    helpers.messages.tests.assert.lastMessage(
                        'Aaron has booked a lesson in Maths.'
                    );
                });
            });
        });

        describe('a fortnightly lesson', function (done) {
            before(function (done) {
                helpers.migrate(function () {
                    browser.visit('/tutor/lessons/create', done);
                });
            });

            it('should be a successful visit', function () {
                helpers.tests.it.shouldBeASuccessfulVisit()
            });

            it(
                'should have the create dialogue open',
                helpers.lessons.tests.it.shouldHaveTheCreateDialogueOpen
            );

            it('should fill in the the form', function () {
                helpers.lessons.tests.it.shouldFillTheCreateForm(
                    'Melissa Lord',
                    'Maths',
                    'Location location location',
                    date.format('DD/MM/YYYY'),
                    '11:30',
                    '1:00',
                    'fortnightly'
                );
            });

            it('should submit the form', function (done) {
                browser.pressButton('.dialogue .js-submit', done);
            });

            describe('the booking should now be listed', function () {
                before(function (done) {
                    browser.visit('/tutor/lessons', done);
                });

                it(
                    'should be on the tutor lessons index page',
                    helpers.lessons.tests.it.shouldBeOnTheTutorLessonsIndexPage
                );

                it('should list 10 lessons', function () {
                    browser.assert.evaluate('$(".table tbody tr").length', 6);
                });

                it('should show the lesson bookings', function () {
                    for (var i = 0, j = 6; i < j; i++) {
                        var booking = {
                            1 : date.clone().add(i * 2, 'weeks').format('Do MMM'),
                            2 : '11:30 - 12:30',
                            4 : 'Melissa Lord',
                            5 : 'Maths'
                        };

                        _.each(booking, function (value, column) {
                            browser.assert.text(
                                '.table tbody tr:nth-child(' + (i + 1) + ') td:nth-child(' + column + ')',
                                value
                            );
                        });
                    };
                });
            });

            describe('I should have recieved a booking message', function () {
                before(function (done) {
                    browser.visit('/tutor/messages/melissa.lord', done);
                });

                it(
                    'should redirect me to the message',
                    helpers.messages.tests.it.shouldRedirectMeToTheMessage
                );

                it('should have sent a message confirming the booking', function () {
                    helpers.messages.tests.assert.lastMessage(
                        'Aaron has booked a lesson in Maths.'
                    );
                });
            });
        });
    });
});
