/*global module:false*/
module.exports = function (grunt)
{

    // Project configuration.
    grunt.initConfig({
        // Metadata.
        pkg: grunt.file.readJSON('package.json'),
        banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
          '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
          '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
          '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
          ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',


        // Task configuration.
        //concat: {
        //  options: {
        //    banner: '<%= banner %>',
        //    stripBanners: true
        //  },
        //  dist: {
        //    src: ['lib/<%= pkg.name %>.js'],
        //    dest: 'dist/<%= pkg.name %>.js'
        //  }
        //},
        //uglify: {
        //  options: {
        //    banner: '<%= banner %>'
        //  },
        //  dist: {
        //    src: '<%= concat.dist.dest %>',
        //    dest: 'dist/<%= pkg.name %>.min.js'
        //  }
        //},
        //jshint: {
        //  options: {
        //    curly: true,
        //    eqeqeq: true,
        //    immed: true,
        //    latedef: true,
        //    newcap: true,
        //    noarg: true,
        //    sub: true,
        //    undef: true,
        //    unused: true,
        //    boss: true,
        //    eqnull: true,
        //    browser: true,
        //    globals: {
        //      jQuery: true
        //    }
        //  },
        //  gruntfile: {
        //    src: 'Gruntfile.js'
        //  },
        //  lib_test: {
        //    src: ['lib/**/*.js', 'test/**/*.js']
        //  }
        //},
        //qunit: {
        //  files: ['test/**/*.html']
        //},
        copy:
            {
                build:
                    {
                        files:
                            [
                                // PHP Files:
                                { expand: true, cwd: 'php/', src: ['**', '!include/config.php', '!include/api/key.php'], dest: 'build/' },

                                //CSS Files:
                                { expand: true, cwd: 'css/', src: ['**'], dest: 'build/css' },

                                //JS Files:
                                { expand: true, cwd: 'js/', src: ['**'], dest: 'build/js' },

                                // Bootstrap-Fonts:
                                { expand: true, cwd: 'fonts/', src: ['**'], dest: 'build/fonts' },

                                // Images:
                                { expand: true, cwd: 'images/', src: ['**'], dest: 'build/images' },

                                // Subpages:
                                { expand: true, cwd: 'subpages/', src: ['**'], dest: 'build/subpages' },

                                 // Templates:
                                { expand: true, cwd: 'template/', src: ['**'], dest: 'build/template' },

                                //Meta:
                                { expand: true, cwd: 'downloads/', src: ['**'], dest: 'build/downloads' }

                            ]
                    },
                dev:
                    {
                        files:
                            [
                                 //Local Config:
                                { expand: true, cwd: 'localConfig/', src: ['config.php'], dest: 'build/include', filter: 'isFile' },
                                { expand: true, cwd: 'localConfig/', src: ['mysql.php'], dest: 'build/version', filter: 'isFile' }
                            ]

                    }

            },


        watch: {
            gruntfile: {
                files: '<%= jshint.gruntfile.src %>',
                tasks: ['jshint:gruntfile']
            },
            //lib_test: {
            //  files: '<%= jshint.lib_test.src %>',
            //  tasks: ['jshint:lib_test', 'qunit']
            //},
            less:
            {
                files: 'less/*.less',
                tasks: ['less:development']
            },
            dev:
            {
                files:
                    [
                        'css/**/*.css',
                        'less/**/*.less',
                        'php/**/*.php',
                        'php/**/*.html',
                        'subpages/**/*.php',
                        'subpages/**/*.html',
                        'template/**/*.php',
                        'template/**/*.html',
                    ],
                tasks: ['default']
            }
        },
        phplint: {
            options: {
                phpCmd: "D:/xampp/php/php.exe", // Or "c:\EasyPHP-5.3.8.1\PHP.exe"
                phpArgs: {
                    "-l": null
                },
                spawnLimit: 10
            },

            all: ["**/*.php"]
        },
        inlinelint:
        {
            //html: ['**/*.html'], My Template System kills this ... :P
            all: ['**/*.php', 'js/*.js']
        },
        less: {
            development:
            {
                options:
                {
                    paths: ["less"]
                },
                files:
                {
                    "build/css/hpclass.css": "less/hpclass.less"
                }
            },
            production:
            {
                options:
                {
                    paths: ["less"],
                    cleancss: true
                    /*,
                    modifyVars: 
                    {
                        imgPath: '"http://mycdn.com/path/to/images"',
                        bgColor: 'red'
                    }*/
                },
                files:
                {
                    "build/css/hpclass.css": "less/hpclass.less"
                }
            },
            compileBootstrapCore:
                {
                    options:
                        {
                            strictMath: true,
                            sourceMap: true,
                            outputSourceFiles: true,
                            sourceMapURL: 'bootstrap.css.map',
                            sourceMapFilename: 'build/css/bootstrap.css.map'
                        },
                    files:
                        {
                            'build/css/bootstrap.css': 'less/bootstrap/bootstrap.less'
                        }
                },
            compileBoostrapTheme:
                {
                    options:
                        {
                            strictMath: true,
                            sourceMap: true,
                            outputSourceFiles: true,
                            sourceMapURL: 'bootstrap-theme.css.map',
                            sourceMapFilename: 'build/css/bootstrap-theme.css.map'
                        },
                    files:
                        {
                            'build/css/bootstrap-theme.css': 'less/bootstrap/theme.less'
                        }
                },
            bootstrapMinify:
                {
                    options:
                        {
                            cleancss: true
                        },
                    files:
                        {
                            'build/css/bootstrap.min.css': 'build/css/bootstrap.css',
                            'build/css/bootstrap-theme.min.css': 'build/css/bootstrap-theme.css'
                        }
                },
            minify:
            {
                options:
                    {
                        cleancss: true
                    },
                files:
                    {
                        'build/css/hpclass.min.css': 'build/css/hpclass.css'
                    }
            }
        }
    });

    // These plugins provide necessary tasks.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks("grunt-phplint");
    grunt.loadNpmTasks('grunt-lint-inline');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Default task.
    //grunt.registerTask('default', ['jshint', 'qunit', 'concat', 'uglify']);

    grunt.registerTask('bootstrap', ['less:compileBootstrapCore', 'less:compileBoostrapTheme', 'less:bootstrapMinify']);

    grunt.registerTask('default', ['lint', 'less:development', 'less:minify', 'copy:build', 'copy:dev']);




    grunt.registerTask('lint', ['phplint', 'inlinelint']);
    grunt.registerTask('devBuild', ['default', 'watch:dev']);

};
