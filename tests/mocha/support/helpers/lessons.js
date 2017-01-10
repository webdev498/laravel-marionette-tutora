var browser = require('./../browser');

var helpers = {

    create : function (
        done,
        student,
        subject,
        location,
        date,
        time,
        duration,
        repeat
    ) {
        browser.visit('/tutor/lessons/create', function () {
            browser.assert.text('.dialogue__header .heading', 'Book a new lesson');
            browser.select('.dialogue .js-student', student);
            browser.select('.dialogue .js-subject', subject);
            browser.fill('.dialogue .js-location', location);
            browser.fill('.dialogue .js-date', date);
            browser.select('.dialogue .js-time', time);
            browser.select('.dialogue .js-duration', duration);
            browser.click('.js-repeat-' + repeat);
            browser.pressButton('.dialogue .js-submit', done);
        });
    },

    fillForm : function (
        student,
        subject,
        location,
        date,
        time,
        duration,
        repeat
    ) {
        browser.select('.dialogue .js-student', student);
        browser.select('.dialogue .js-subject', subject);
        browser.fill('.dialogue .js-location', location);
        browser.fill('.dialogue .js-date', date);
        browser.select('.dialogue .js-time', time);
        browser.select('.dialogue .js-duration', duration);
        browser.click('.js-repeat-' + repeat);
    },

    tests : {
        it : {
            shouldBeOnTheTutorLessonsIndexPage : function () {
                browser.assert.success();
                browser.assert.url({
                    pathname : '/tutor/lessons'
                });
                browser.assert.text('.delta', 'Lessons');
            },

            shouldBeOnTheStudentLessonsIndexPage : function () {
                browser.assert.success();
                browser.assert.url({
                    pathname : '/student/lessons'
                });
                browser.assert.text('.delta', 'Lessons');
            },

            shouldHaveTheCreateDialogueOpen : function () {
                browser.assert.url({
                    pathname : '/tutor/lessons/create'
                });
                browser.assert.text('.dialogue__header .heading', 'Book a new lesson');
            },

            shouldHaveTheTutorCancelDialogueOpen : function () {
                browser.assert.url({
                    pathname : new RegExp('^/tutor/lessons/.+/cancel')
                });
                browser.assert.text(
                    '.dialogue__header .heading',
                    'Are you sure you want to cancel this lesson?'
                );
            },

            shouldHaveTheStudentCancelDialogueOpen : function () {
                browser.assert.url({
                    pathname : new RegExp('^/student/lessons/.+/cancel')
                });
                browser.assert.text(
                    '.dialogue__header .heading',
                    'Are you sure you want to cancel this lesson?'
                );
            },

            shouldFillTheCreateForm : function (
                student,
                subject,
                location,
                date,
                time,
                duration,
                repeat
            ) {
                browser.select('.dialogue .js-student', student);
                browser.select('.dialogue .js-subject', subject);
                browser.fill('.dialogue .js-location', location);
                browser.fill('.dialogue .js-date', date);
                browser.select('.dialogue .js-time', time);
                browser.select('.dialogue .js-duration', duration);
                browser.click('.js-repeat-' + repeat);
            }
        }
    }

};

module.exports = helpers;
