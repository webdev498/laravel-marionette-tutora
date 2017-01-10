var should  = require('should');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

describe('I can message a tutor', function () {
    var h = {
        shouldBeASuccessfulPageView : function () {
            browser.assert.success();
            browser.assert.url({
                pathname : '/tutors/aaron.lord'
            });
        },

        shouldHaveASendMessageDialogue : function () {
            browser.clickLink('Message');
            browser.assert.text('.dialogue__header .heading', 'Send message');
            browser.assert.url({
                pathname : '/message/aaron.lord'
            });
        },

        shouldHaveSentTheMessage : function () {
            browser.assert.url({
                pathname : new RegExp('^\/student/messages/.+')
            });
            browser.assert.text('.messages__item:last-child .messages__body', 'Test message');
        }
    };

    describe('As an authenticated student', function () {
        before(function (done) {
            helpers.login.student(function () {
                browser.visit('/tutors/aaron.lord', done);
            });
        });

        after(function (done) {
            helpers.logout(done);
        });

        it('should be a successful page view', function () {
            browser.assert.success();
            browser.assert.url({
                pathname : '/tutors/aaron.lord'
            });
        });

        it('should have a dialogue to send a message', function () {
            browser.clickLink('Message');
            browser.assert.text('.dialogue__header .heading', 'Send message');
            browser.assert.url({
                pathname : '/message/aaron.lord'
            });
        });

        it('should submit the form', function (done) {
            browser.fill('.dialogue [name=body]', 'Test message');
            browser.pressButton('.dialogue button', done);
        });

        it('should have sent the message', function () {
            browser.assert.url({
                pathname : new RegExp('^\/student/messages/.+')
            });
            browser.assert.text('.messages__item:last-child .messages__body', 'Test message');
        });
    });

    describe('As a guest', function () {
        describe('signing up as a new student', function () {
            before(function (done) {
                helpers.logout(function () {
                    browser.visit('/tutors/aaron.lord', done);
                });
            });

            after(function (done) {
                helpers.logout(done);
            });

            it('should be a successful page view', h.shouldBeASuccessfulPageView);

            it('should have a dialogue to send a message', h.shouldHaveASendMessageDialogue);

            it('should submit the form', function (done) {
                browser.clickLink('.js-register-button');
                browser.fill('.dialogue [name=body]', 'Test message');
                browser.fill('.dialogue .js-register-first-name', 'Testy');
                browser.fill('.dialogue .js-register-last-name', 'McTesty');
                browser.fill('.dialogue .js-register-email', 'testy.mctest@student.com');
                browser.fill('.dialogue .js-register-telephone', '07123456789');
                browser.fill('.dialogue .js-register-password', 'secret');
                browser.pressButton('.dialogue button', done);
            });

            it('should have sent the message', h.shouldHaveSentTheMessage);
        });

        describe('logging in as an existing student', function () {
            before(function (done) {
                helpers.logout(function () {
                    browser.visit('/tutors/aaron.lord', done);
                });
            });

            after(function (done) {
                helpers.logout(done);
            });

            it('should be a successful page view', h.shouldBeASuccessfulPageView);

            it('should have a dialogue to send a message', h.shouldHaveASendMessageDialogue);

            it('should submit the form', function (done) {
                browser.clickLink('.js-login-button');
                browser.fill('.dialogue [name=body]', 'Test message');
                browser.fill('.dialogue .js-login-email', 'melissa.lord@student.com');
                browser.fill('.dialogue .js-login-password', 'secret');
                browser.pressButton('.dialogue button', done);
            });

            it('should have sent the message', h.shouldHaveSentTheMessage);
        });
    });
});
