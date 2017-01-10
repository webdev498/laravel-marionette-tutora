var should  = require('should');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

describe('As an admin, I can view relationships', function () {
    before(function (done) {
        helpers.login.admin(function () {
            browser.visit('/admin/relationships', done);
        });
    });

    after(function (done) {
        helpers.logout(done);
    });

    describe('I visit the relationships index', function () {
        it('should be a successful page view', function () {
            browser.assert.url({
                pathname : '/admin/relationships'
            });
            browser.assert.text('.delta', 'Relationships');
        });

        it('should list existing relationships', function () {
            browser.assert.elements('.table tbody tr', 7);
        });
    });
});

