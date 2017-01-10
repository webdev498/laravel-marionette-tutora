var browser = require('./browser');

var helpers = {

    'migrate' : function (done) {
        browser.visit('/testing/migrate', done);
    },

    'login' : {
        'admin' : function (done) {
            browser.visit('/login', function () {
                browser.fill('email', 'aaron.lord@admin.com');
                browser.fill('password', 'secret');
                browser.pressButton('Log In', done);
            });
        },
        'tutor' : function (done) {
            browser.visit('/login', function () {
                browser.fill('email', 'aaron.lord@tutor.com');
                browser.fill('password', 'secret');
                browser.pressButton('Log In', done);
            });
        },
        'student' : function (done) {
            browser.visit('/login', function () {
                browser.fill('email', 'melissa.lord@student.com');
                browser.fill('password', 'secret');
                browser.pressButton('Log In', done);
            });
        }
    },

    'logout' : function (done) {
        browser.visit('/logout', function () {
            browser.assert.url('/');
            done();
        });
    },

    tests : {
        it : {
            shouldBeASuccessfulVisit : function () {
                browser.assert.success();
            },

            shouldRedirectMe : function () {
                browser.assert.redirected();
            }
        }
    },

    'messages' : require('./helpers/messages'),

    'lessons' : require('./helpers/lessons')

};

before(function (done) {
    this.timeout(30000);
    helpers.migrate(done);
});

module.exports = helpers;
