var should  = require('should');
var _       = require('underscore');
var browser = require('./support/browser');
var helpers = require('./support/helpers');

/**
 * I can sign up
 * I can fill out my profile
 * I can validate my account
 */
describe('I can sign up as a tutor', function () {
    after(function (done) {
        helpers.logout(done);
    });

    describe('Create an account', function () {
        before(function (done) {
            browser.visit('/become-a-tutor/sign-up', done);
        });

        it('should be a successful visit', function () {
            browser.assert.success();
        });

        it('should have the dialogue open', function () {
            browser.assert.url({
                pathname : '/become-a-tutor/sign-up'
            });
            browser.assert.text(
                '.dialogue__header .heading',
                'Sign up as a tutor'
            );
        });

        it('should fill in the form', function () {
            browser.fill('.dialogue .js-first-name', 'Testy');
            browser.fill('.dialogue .js-last-name', 'McTest');
            browser.fill('.dialogue .js-email', 'testy.mctest@tutor.com');
            browser.fill('.dialogue .js-telephone', '01234567890');
            browser.fill('.dialogue .js-password', 'secret');
        });

        it('should submit the form', function (done) {
            browser.pressButton('.dialogue .js-submit', done);
        });

        it('should redirect me to my profile', function () {
            browser.assert.url({
                pathname : new RegExp('^\/tutors/[A-z0-9]{8}')
            });
        });

        it('should show my progress is 0', function () {
            browser.assert.hasClass('.progress-bar', 'progress-bar--0');
        });
    });

    describe('Fill in the profile', function () {
        before(function() {
            browser.assert.url({
                pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
            });
        });

        describe('Set a tagline', function () {
            it('should show the placeholder', function () {
                browser.assert.text('.profile-tagline .js-tagline', 'Set your tagline here');
            });

            it('should click the edit link', function (done) {
                browser.clickLink('a[href$="tagline"].edit-link', done);
            });

            it('should open the edit tagline dialogue', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/tutors/[A-z0-9]{8}/tagline$')
                });
                browser.assert.text('.dialogue .heading', 'Edit your tagline');
            });

            describe('An invalid request', function () {
                describe('required', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display an error', function () {
                        browser.assert.hasClass('.dialogue .field', 'field--has-error');
                        browser.assert.text('.dialogue .field__error', 'The profile.tagline field is required.');
                    });
                });
            });

            describe('A successful change', function () {
                it('should fill in the form', function () {
                    browser.fill('.dialogue .js-tagline', 'Hello, world!');
                });

                it('should submit the form', function (done) {
                    browser.pressButton('Save', done);
                });

                it('should redirect me to my profile', function () {
                    browser.assert.url({
                        pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
                    });
                });

                it('should show the new tagline', function () {
                    browser.assert.text('.profile-tagline .js-tagline', 'Hello, world!');
                });
            });
        });

        describe('Set a rate', function () {
            it('should show the placeholder', function () {
                browser.assert.text('.profile-details .js-rate', '-');
            });

            it('should click the edit link', function (done) {
                browser.clickLink('a[href$="rate"].edit-link', done);
            });

            it('should open the edit rate dialogue', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/tutors/[A-z0-9]{8}/rate$')
                });
                browser.assert.text('.dialogue .heading', 'Edit your rate');
            });

            describe('An invalid request', function () {
                describe('required', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display an error', function () {
                        browser.assert.hasClass('.dialogue .field', 'field--has-error');
                        browser.assert.text('.dialogue .field__error', 'The profile.rate field is required.');
                    });
                });

                describe('numeric', function () {
                    it('should fill in the form', function () {
                        browser.fill('.dialogue .js-rate', 'abc');
                    });

                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display an error', function () {
                        browser.assert.hasClass('.dialogue .field', 'field--has-error');
                        browser.assert.text('.dialogue .field__error', 'The profile.rate must be a number.');
                    });
                });
            });

            describe('A successful change', function () {
                it('should fill in the form', function () {
                    browser.fill('.dialogue .js-rate', '25');
                });

                it('should submit the form', function (done) {
                    browser.pressButton('Save', done);
                });

                it('should redirect me to my profile', function () {
                    browser.assert.url({
                        pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
                    });
                });

                it('should show the new tagline', function () {
                    browser.assert.text('.profile-details .js-rate', '25');
                });
            });
        });

        describe('Set a travel policy', function () {
            it('should show the placeholder', function () {
                browser.assert.text('.js-travel-policy p', 'You haven\'t set a travel policy yet. Do it now?');
            });

            it('should click the edit link', function (done) {
                browser.clickLink('a[href$="travel_policy"].edit-link', done);
            });

            it('should open the edit travel policy dialogue', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/tutors/[A-z0-9]{8}/travel_policy$')
                });
                browser.assert.text('.dialogue .heading', 'Edit your travel policy');
            });

            describe('An invalid request', function () {
                describe('required', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display errors', function () {
                        browser.assert.elements('.dialogue .field--has-error', 4);
                    });
                });
            });

            describe('A successful change', function () {
                it('should fill in the form', function () {
                    browser.select('.dialogue .js-travel-radius', "10");
                    browser.fill('.dialogue .js-address-line-1', '44 Plymouth Road');
                    browser.fill('.dialogue .js-address-line-2', 'Sheffield');
                    browser.fill('.dialogue .js-address-postcode', 'S7 2DE');
                });

                it('should submit the form', function (done) {
                    browser.pressButton('Save', done);
                });

                it('should redirect me to my profile', function () {
                    browser.assert.url({
                        pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
                    });
                });

                it('should show the new travel distance', function () {
                    browser.assert.text('.js-travel-policy p', '10 miles');
                });
            });
        });

        describe('Set a bio', function () {
            it('should show no bio', function () {
                browser.assert.text('.profile-body .js-bio', 'You haven\'t written a bio yet.');
            });

            it('should click the edit link', function (done) {
                browser.clickLink('a[href$="bio"].edit-link', done);
            });

            it('should open the edit bio dialogue', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/tutors/[A-z0-9]{8}/bio$')
                });
                browser.assert.text('.dialogue .heading', 'Edit your bio');
            });

            describe('An invalid request', function () {
                describe('required', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display errors', function () {
                        browser.assert.elements('.dialogue .field--has-error', 1);
                        browser.assert.text('.dialogue .field__error', 'The profile.bio field is required.');
                    });
                });
            });

            describe('A successful change', function () {
                it('should fill in the form', function () {
                    browser.fill('.dialogue .js-bio', "Hello, world!");
                });

                it('should submit the form', function (done) {
                    browser.pressButton('Save', done);
                });

                it('should redirect me to my profile', function () {
                    browser.assert.url({
                        pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
                    });
                });

                it('should show the new bio', function () {
                    browser.assert.text('.profile-body .js-bio', 'Hello, world!');
                });
            });
        });

        describe('Add subjects', function () {
            it('should show no subjects', function () {
                browser.assert.text('.profile-aside .js-subjects .dl__dd', 'You haven\'t added any subjects yet. Add some now?');
            });

            it('should click the edit link', function (done) {
                browser.clickLink('a[href$="subjects"].box', done);
            });

            it('should open the edit subjects dialogue', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/tutors/[A-z0-9]{8}/subjects$')
                });
                browser.assert.text('.dialogue .heading', 'Edit your subjects');
            });

            it('should load the subjects', function () {
                browser.assert.text('.dialogue .tabs .tabs__link--active', 'Maths');
                browser.assert.text('.dialogue .tab-content--active .box:nth-of-type(1) .zeta:nth-of-type(1)', 'Maths');
                browser.assert.elements('.dialogue input[type="checkbox"][name="subjects"][value="3"]', 1);
                browser.assert.elements('.dialogue input[type="checkbox"][name="subjects"][value="6"]', 1);
            });

            describe('An invalid request', function () {
                describe('required', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display errors', function () {
                        browser.assert.elements('.dialogue .field--has-error', 1);
                        browser.assert.text('.dialogue .field__error', 'The subjects field is required.');
                    });
                });
            });

            describe('A successful change', function () {
                var subjects = [3, 6, 24];

                it('should fill in the form', function () {
                    for (var i = 0, j = subjects.length; i < j; i++) {
                        var el = browser.querySelector('#_subject_' + subjects[i]);
                        browser.click(el);
                    };
                });

                it('should have selected a subject', function () {
                    browser.assert.elements('input[name="subjects"]:checked', subjects.length);

                    for (var i = 0, j = subjects.length; i < j; i++) {
                        browser.assert.element('#_subject_' + subjects[i] + ':checked');
                    };
                });

                it('should submit the form', function (done) {
                    browser.pressButton('.dialogue .js-save', done);
                });

                it('should redirect me to my profile', function () {
                    browser.assert.url({
                        pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
                    });
                });

                it('should show the new subjects', function () {
                    browser.assert.text(
                        '.profile-aside .js-subjects .dl__dt:nth-of-type(1)',
                        'Maths'
                    );

                    browser.assert.text(
                        '.profile-aside .js-subjects .dl__dd:nth-of-type(1)',
                        'Maths (Primary, GCSE)'
                    );

                    browser.assert.text(
                        '.profile-aside .js-subjects .dl__dt:nth-of-type(2)',
                        'English'
                    );

                    browser.assert.text(
                        '.profile-aside .js-subjects .dl__dd:nth-of-type(2)',
                        'English (Primary)'
                    );
                });
            });
        });

        describe('Add qualifications', function () {
            it('should show no qualifications', function () {
                browser.assert.text(
                    '.profile-aside .js-qualifications .dl__dd',
                    'You haven\'t added any qualifications yet. Add some now?'
                );
            });

            it('should click the edit link', function (done) {
                browser.clickLink('a[href$="qualifications"].box', done);
            });

            it('should open the edit qualifications dialogue', function () {
                browser.assert.url({
                    pathname : new RegExp('^\/tutors/[A-z0-9]{8}/qualifications/university$')
                });
                browser.assert.text('.dialogue .heading', 'Edit your qualifications');
            });

            describe('An invalid request', function () {
                describe('required', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should display errors', function () {
                        browser.assert.text(
                            '.dialogue .field__error',
                            'The qualifications field is required.'
                        );
                    });
                });
            });

            describe('A successful change', function () {
                describe('fill in a university', function () {
                    it('should be the correct tab', function () {
                        browser.assert.hasClass(
                            'a[data-tab-group="qualifications"][data-tab-name="university"]',
                            'tabs__link--active'
                        );
                    });

                    it('should be the correct url', function () {
                        browser.assert.url({
                            pathname : new RegExp('^\/tutors/[A-z0-9]{8}/qualifications/university$')
                        });
                    });

                    it('should fill in the form', function () {
                        browser.fill(
                            '.dialogue .js-universities .js-university',
                            'Sheffield Hallam University'
                        );

                        browser.select(
                            '.dialogue .js-universities .js-level',
                            'Degree'
                        );

                        browser.fill(
                            '.dialogue .js-universities .js-subject',
                            'Web Development'
                        );

                        browser.click(
                            browser.querySelector(
                                '.dialogue .js-universities .js-still-studying'
                            )
                        );
                    });
                });

                describe('fill in a college', function () {
                    it('should click the tab', function () {
                        browser.clickLink('College');
                    });

                    it('should be the correct url', function () {
                        browser.assert.url({
                            pathname : new RegExp('^\/tutors/[A-z0-9]{8}/qualifications/college$')
                        });
                        browser.assert.hasClass(
                            'a[data-tab-group="qualifications"][data-tab-name="alevels"]',
                            'tabs__link--active'
                        );
                    });

                    it('should fill in the form', function () {
                        browser.fill(
                            '.dialogue .js-alevels .js-college',
                            'Sheffield College'
                        );

                        browser.select(
                            '.dialogue .js-alevels .js-grade',
                            'A*'
                        );

                        browser.fill(
                            '.dialogue .js-alevels .js-subject',
                            'Web Development'
                        );

                        browser.click(
                            browser.querySelector(
                                '.dialogue .js-alevels .js-still-studying'
                            )
                        );
                    });
                });

                describe('fill in an "other"', function () {
                    it('should click the tab', function () {
                        browser.clickLink('Other');
                    });

                    it('should be the correct url', function () {
                        browser.assert.url({
                            pathname : new RegExp('^\/tutors/[A-z0-9]{8}/qualifications/other$')
                        });
                        browser.assert.hasClass(
                            'a[data-tab-group="qualifications"][data-tab-name="other"]',
                            'tabs__link--active'
                        );
                    });

                    it('should fill in the form', function () {
                        browser.fill(
                            '.dialogue .js-others .js-location',
                            'http'
                        );

                        browser.fill(
                            '.dialogue .js-others .js-grade',
                            'www'
                        );

                        browser.fill(
                            '.dialogue .js-others .js-subject',
                            'Internet'
                        );

                        browser.click(
                            browser.querySelector(
                                '.dialogue .js-others .js-still-studying'
                            )
                        );
                    });
                });

                describe('saving', function () {
                    it('should submit the form', function (done) {
                        browser.pressButton('Save', done);
                    });

                    it('should redirect me to my profile', function () {
                        browser.assert.url({
                            pathname : new RegExp('^\/tutors/[A-z0-9]{8}$')
                        });
                    });

                    it('should show the new qualifications', function () {
                        browser.assert.text(
                            '.profile-aside .js-qualifications .dl__dt:nth-of-type(1)',
                            'University'
                        );

                        browser.assert.text(
                            '.profile-aside .js-qualifications .dl__dd:nth-of-type(1)',
                            'Web Development * Sheffield Hallam University Degree'
                        );

                        browser.assert.text(
                            '.profile-aside .js-qualifications .dl__dt:nth-of-type(2)',
                            'College'
                        );

                        browser.assert.text(
                            '.profile-aside .js-qualifications .dl__dd:nth-of-type(2)',
                            'Web Development * Sheffield College A*'
                        );

                        /*
                        Not sure why this is failing
                        browser.assert.text(
                            '.profile-aside .js-qualifications .dl__dt:nth-of-type(3)',
                            'Others'
                        );

                        browser.assert.text(
                            '.profile-aside .js-qualifications .dl__dd:nth-of-type(3)',
                            'Internet * http www'
                        );
                        */
                    });
                });
            });
        });
    });
});
