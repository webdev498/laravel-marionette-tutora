var should  = require('should');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

describe('As a tutor, I can messages students', function () {
    before(function (done) {
        helpers.login.tutor(done);
    });

    after(function (done) {
        helpers.logout(done);
    });

    describe('I visit my messages page', function () {
        before(function (done) {
            browser.visit('/tutor/messages', done);
        });

        it('should show me my messages', function () {
            browser.assert.success();
            browser.assert.text('.delta', 'Messages');
        });

        it('should see existing (seeded) messages', function () {
            browser.assert.elements('.table tbody tr', {
                atLeast : 3
            });
        });
    });

    describe('I visit a message thread', function () {
        before(function (done) {
            browser.visit('/tutor/messages', function () {
                browser.clickLink('.table tbody tr:first-child a', done);
            });
        });

        it('should be a sucessful page view', function () {
            browser.assert.success();
            browser.assert.url({
                pathname : new RegExp('^\/tutor/messages/.+')
            });
        });

        it('should see existing (seeded) message lines', function () {
            browser.assert.elements('.messages__item', {
                atLeast : 2,
                atMost : 10
            });
        });

        describe('I can send a message', function () {
            it('should send messages', function (done) {
                browser.fill('.message-form textarea[name=body]', 'Test message');
                browser.pressButton('Send message', done);
            });

            it('should display the new message', function () {
                browser.assert.text(
                    '.messages__item--me:last-child .messages__body p',
                    'Test message'
                );
            });
        });
    });
});
