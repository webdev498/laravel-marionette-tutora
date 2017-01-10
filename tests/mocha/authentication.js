var browser = require('./support/browser');
var helpers = require('./support/helpers');

describe('I can login and logout', function () {
    var h = {
        shouldLogMeOut : function (done) {
            browser.visit('/logout', function () {
                browser.assert.url('/');
                browser.assert.element('.site-nav__login');
                done();
            });
        }
    };

    after(function (done) {
        helpers.logout(done);
    });

    describe('As a student', function () {
        it('allows me to login', function (done) {
            browser.assert.url('/');
            browser.visit('/login', function () {
                browser.fill('email', 'melissa.lord@student.com');
                browser.fill('password', 'secret');
                browser.pressButton('Log In', done);
            });
        });

        it('shows my name in the site header', function () {
            browser.assert.url('/student/dashboard');
            browser.assert.text('.site-nav__account__title', 'Melissa Lord');
        });

        it('allows me to logout', h.shouldLogMeOut);
    });

    describe('As a tutor', function () {
        it('allows me to login', function (done) {
            browser.visit('/login', function () {
                browser.fill('email', 'aaron.lord@tutor.com');
                browser.fill('password', 'secret');
                browser.pressButton('Log In', done);
            });
        });

        it('shows my name in the site header', function () {
            browser.assert.url('/tutor/dashboard');
            browser.assert.text('.site-nav__account__title', 'Aaron Lord');
        });

        it('should log me out', h.shouldLogMeOut);
    });
});
