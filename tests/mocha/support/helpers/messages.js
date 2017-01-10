var browser = require('./../browser');
var helpers = {
    tests : {
        it : {},
        assert : {},
    }
};

/**
 * Assert
 */
helpers.tests.assert.lastMessage = function (message) {
    browser.assert.text(
        '.messages__item:last-child .messages__body',
        message
    );
};

/**
 * It
 */
helpers.tests.it.shouldBeOnTheTutorLessonsIndexPage = function () {
    browser.assert.success();
    browser.assert.url({
        pathname : '/tutor/lessons'
    });
};

helpers.tests.it.shouldRedirectMeToTheMessage = function () {
    browser.assert.redirected();
    browser.assert.url({
        pathname : new RegExp('^\/tutor/messages/.+')
    });
};

module.exports = helpers;
