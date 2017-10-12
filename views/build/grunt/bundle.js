module.exports = function (grunt) {
    'use strict';

    var requirejs = grunt.config('requirejs') || {};
    var clean     = grunt.config('clean') || {};
    var copy      = grunt.config('copy') || {};
    var root      = grunt.option('root');
    var libs      = grunt.option('mainlibs');
    var ext       = require(root + '/tao/views/build/tasks/helpers/extensions')(grunt, root);
    var out       = 'output';

    /**
     * Remove bundled and bundling files
     */
    clean.taocebundle = [out];

    /**
     * Compile tao files into a bundle
     */
    requirejs.taocebundle = {
        options: {
            exclude: ['mathJax'].concat(libs),
            include: ext.getExtensionsControllers(['taoCe']),
            out: out + '/taoCe/bundle.js',
            paths: { taoCe: root + '/taoCe/views/js' },
        }
    };

    /**
     * copy the bundles to the right place
     */
    copy.taocebundle = {
        files: [
            { src: [out + '/taoCe/bundle.js'],     dest: root + '/taoCe/views/dist/controllers.min.js' },
            { src: [out + '/taoCe/bundle.js.map'], dest: root + '/taoCe/views/dist/controllers.min.js.map' }
        ]
    };

    grunt.config('clean', clean);
    grunt.config('requirejs', requirejs);
    grunt.config('copy', copy);

    // bundle task
    grunt.registerTask('taocebundle', ['clean:taocebundle', 'requirejs:taocebundle', 'copy:taocebundle']);
};
