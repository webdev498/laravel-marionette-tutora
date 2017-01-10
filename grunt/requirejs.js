module.exports = {
    main: {
        options: {
            optimize: 'uglify2',
            uglify2: {
                mangler: {
                    toplevel: true
                }
            },
            baseUrl: 'public/js',
            mainConfigFile: 'public/js/main.js',
            // name: '../vendor/almond/almond',
            include: 'main',
            out: 'public/js/main.min.v2.js',
            preserveLicenseComments: false
        }
    }
};
