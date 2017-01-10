module.exports = function(grunt) {
    grunt.registerTask('build', [/*'sprite',*/ 'sass', 'modernizr', 'uglify']);
    grunt.registerTask('release', ['build', 'requirejs']);
    grunt.registerTask('default', ['build', 'watch']);
};
