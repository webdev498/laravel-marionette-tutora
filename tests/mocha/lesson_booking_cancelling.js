var _       = require('underscore');
var should  = require('should');
var moment  = require('moment');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

var date = moment().add(2, 'days');

describe('Lesson cancelling', function () {
    after(function (done) {
        helpers.logout(function () {
            helpers.migrate(done);
        });
    });

    describe('as a student', function () {
        after(function (done) {
            helpers.logout(done);
        });

        describe('the tutor books a lesson', function () {
            before(function (done) {
                helpers.login.tutor(function () {
                    browser.visit('/tutor/lessons/create', done);
                });
            });

            after(function (done) {
                helpers.logout(done);
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
        });

        describe('As the student', function () {
            before(function (done) {
                helpers.login.student(function () {
                    browser.visit('/student/lessons', done);
                });
            });

            describe('I can see the lesson', function () {
                it(
                    'should be on the student lessons index page',
                    helpers.lessons.tests.it.shouldBeOnTheStudentLessonsIndexPage
                );

                it('should list 1 lesson', function () {
                    browser.assert.elements('.table tbody tr', 1);
                });

                it('should show the lesson booking', function () {
                    var booking = {
                        1 : date.format('Do MMM'),
                        2 : '11:30 - 12:30',
                        4 : 'Aaron Lord',
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

            describe('I can cancel lesson', function () {
                it('should click the cancel booking link', function (done) {
                    browser.clickLink('a[href*="/student/lessons"][href$="cancel"]', done);
                });

                it(
                    'should have the cancel dialogue open',
                    helpers.lessons.tests.it.shouldHaveTheStudentCancelDialogueOpen
                );

                it('should submit the form', function (done) {
                    browser.pressButton('.dialogue .js-submit', done);
                });
            });

            describe('I can see the cancelled lesson', function () {
                it(
                    'should be on the student lessons index page',
                    helpers.lessons.tests.it.shouldBeOnTheStudentLessonsIndexPage
                );

                it('should list 1 lesson', function () {
                    browser.assert.elements('.table tbody tr', 1);
                });

                it('should show the lesson booking', function () {
                    var booking = {
                        1 : date.format('Do MMM'),
                        2 : '11:30 - 12:30',
                        4 : 'Aaron Lord',
                        5 : 'Maths'
                    };

                    _.each(booking, function (value, key) {
                        browser.assert.text(
                            '.table tbody tr:first-child td:nth-child(' + key + ')',
                            value
                        );
                    });
                });

                it('should be cancelled', function () {
                    browser.assert.hasClass(
                        '.table tbody tr:first-child',
                        'table__row--strike'
                    );
                });
            });
        });
    });

    describe('as a tutor', function () {
        before(function (done) {
            helpers.migrate(function () {
                helpers.login.tutor(done);
            });
        });

        after(function (done) {
            helpers.logout(done);
        });

        describe('I book a lesson', function () {
            before(function (done) {
                browser.visit('/tutor/lessons/create', done);
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
        });

        describe('I can see the lesson', function () {
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

        describe('I can cancel lesson', function () {
            it('should click the cancel booking link', function (done) {
                browser.clickLink('a[href*="/tutor/lessons"][href$="cancel"]', done);
            });

            it(
                'should have the cancel dialogue open',
                helpers.lessons.tests.it.shouldHaveTheTutorCancelDialogueOpen
            );

            it('should submit the form', function (done) {
                browser.pressButton('.dialogue .js-submit', done);
            });
        });

        describe('I can see the lesson is cancelled', function () {
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

            it('should be cancelled', function () {
                browser.assert.hasClass(
                    '.table tbody tr:first-child',
                    'table__row--strike'
                );
            });
        });
    });
});

