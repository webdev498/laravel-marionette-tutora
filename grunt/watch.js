module.exports = {
    options: {
        livereload: true
    },

    gruntfile: {
        files: ['gruntfile.js', 'grunt/**/**'],
        tasks: ['build']
    },

    sass: {
        files: ['public/css/**/**.scss'],
        tasks: ['sass']
    },

    sprite: {
        files: ['public/img/graphic/*.png', 'public/img/icon/*.png'],
        tasks: ['sprite']
    }
};
