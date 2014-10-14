module.exports = function(grunt) { 

    var requirejs   = grunt.config('requirejs') || {};
    var clean       = grunt.config('clean') || {};
    var copy        = grunt.config('copy') || {};

    var root        = grunt.option('root');
    var libs        = grunt.option('mainlibs');
    var ext         = require(root + '/tao/views/build/tasks/helpers/extensions')(grunt, root);

    /**
     * Remove bundled and bundling files
     */
    clean.taocebundle = ['output',  root + '/taoCe/views/js/controllers.min.js'];
    
    /**
     * Compile tao files into a bundle 
     */
    requirejs.taocebundle = {
        options: {
            baseUrl : '../js',
            dir : 'output',
            mainConfigFile : './config/requirejs.build.js',
            paths : { 'taoCe' : root + '/taoCe/views/js' },
            modules : [{
                name: 'taoCe/controller/routes',
                include : ext.getExtensionsControllers(['taoCe']),
                exclude : ['mathJax', 'mediaElement'].concat(libs)
            }]
        }
    };

    /**
     * copy the bundles to the right place
     */
    copy.taocebundle = {
        files: [
            { src: ['output/taoCe/controller/routes.js'],  dest: root + '/taoCe/views/js/controllers.min.js' },
            { src: ['output/taoCe/controller/routes.js.map'],  dest: root + '/taoCe/views/js/controllers.min.js.map' }
        ]
    };

    grunt.config('clean', clean);
    grunt.config('requirejs', requirejs);
    grunt.config('copy', copy);

    // bundle task
    grunt.registerTask('taocebundle', ['clean:taocebundle', 'requirejs:taocebundle', 'copy:taocebundle']);
};
