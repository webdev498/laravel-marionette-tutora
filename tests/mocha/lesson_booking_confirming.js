var _       = require('underscore');
var should  = require('should');
var moment  = require('moment');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

var date = moment().add(2, 'days');

describe('Student lessons confirming', function () {
    after(function (done) {
        helpers.logout(function () {
            helpers.migrate(done);
        });
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

    describe('I, the student, confirm the lesson', function () {
        before(function (done) {
            helpers.login.student(function () {
                browser.visit('/student/lessons', done);
            });
        });

        describe('the booking should be listed', function () {
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

        describe('I can navigate to the confirm booking page', function () {
            it('should click the confirm booking link', function (done) {
                browser.clickLink('a[href*="/student/lessons"][href$="confirm"]', done);
            });

            it('should have sent me to the confirmation page', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/student/lessons/.+/confirm')
                });
                browser.assert.text('.band .delta', 'Confirm lesson booking');
            });

            it('should show me the correct lesson details', function () {
                browser.assert.text('.lesson__details .lesson__details__subject', 'Maths');
                browser.assert.text('.lesson__details .lesson__details__date', date.format('Do MMMM, YYYY'));
                browser.assert.text('.lesson__details .lesson__details__time', '11:30 - 12:30 with Aaron Lord.');
                browser.assert.text('.lesson__price .lesson__details__date', '£25.00');
            });

        });

        describe('I confirm the lesson booking', function () {
            it('should fill in the form', function () {
                var year = date.clone().add(2, 'years').format('YYYY');

                browser.fill('.lesson__payment .js-card-number', '4242 4242 4242 4242');
                browser.fill('.lesson__payment .js-card-cvc', '123');
                browser.select('.lesson__payment .js-card-expiry-month', '01');
                browser.select('.lesson__payment .js-card-expiry-year', year);
            });

            it('should submit the form', function (done) {
                browser.pressButton('.js-form .js-submit', done);
            });

            it('should have sent me to the confirmed page', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/student/lessons/.+/confirmed')
                });
                browser.assert.text('.message .gamma', 'Lesson booked');
            });
        });
    });
});
