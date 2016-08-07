module.exports = function(grunt) {
    grunt.initConfig({
        concat: {
            js: {
                src: ['htdocs/js/lib/*.js', 'htdocs/js/lib/*/*.js',  'htdocs/js/src/*.js'],
                dest: 'htdocs/js/built.js'
            },
            css: {
                src: ['htdocs/css/lib/*.css', 'htdocs/css/main.css'],
                dest: 'htdocs/css/built.css'
            }
        },
        sass: {
            dist: {
                files: {
                    'htdocs/css/main.css': 'htdocs/css/src/main.scss'
                },
                options: {
                    lineNumbers: true
                }
            }
        },
        watch: {
            scripts: {
                files: 'htdocs/js/src/*.js',
                tasks: ['concat:js'],
                options: {
                    debounceDelay: 250
                }
            },
            styles: {
                files: 'htdocs/css/src/*.scss',
                tasks: ['sass', 'concat:css'],
                options: {
                    debounceDelay: 250
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.registerTask('default', ["sass", 'concat']);
};