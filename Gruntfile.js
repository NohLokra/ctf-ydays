
module.exports = function(grunt) {
  require("load-grunt-tasks")(grunt);

  // Project configuration.
  grunt.initConfig({
    uglify: {
      dist: {
        files: {
          /* 'dist': [
            'dev'
          ] //*/
        }
      }
    },
    jshint: {
      all: [
        'Gruntfile.js',
        'assets/dev/**/*.js'
      ]
    },
    cssmin: {
      dist: {
        files: {
          "assets/dist/css/min.css": [
            "assets/dev/css/main.css"
          ],
          "assets/dist/css/bootstrap.css": [
            "assets/dev/css/bootstrap-theme.min.css"
          ]
        }
      }
    },
    watch: {
      css: {
        files: ["assets/dev/css/*.css"],
        tasks: ["cssmin"],
        options: {
          spawn: false
        }
      },
      js: {
        files: ["assets/dev/js/*.js"],
        tasks: ["jshint", "uglify"],
        options: {
          spawn: false
        }
      },
      sass: {
        files: ["assets/dev/sass/*.scss"],
        tasks: ["sass:dist", "cssmin"],
        options: {
          spawn: false
        }
      }
    },
    imagemin: {
      dist: {                         // Another target
        files: [{
          expand: true,                  // Enable dynamic expansion
          cwd: 'assets/dev/img/',                   // Src matches are relative to this path
          src: ['*.{png,jpg,gif}'],   // Actual patterns to match
          dest: 'assets/dist/img/'                  // Destination path prefix
        }]
      }
    },
    sass: {
      options: {
        includePaths: [
          'node_modules/foundation-sites/scss',
          'assets/dev/sass/lib'
        ]
      },
      dist: {
        files: {
          "assets/dev/css/main.css": "assets/dev/sass/main.scss"
        }
      }
    }
    // replace: { //Remplacement de texte dans des fichiers
    //   dist: {
    //     src: ['resources/dev/css/*.css'],
    //     overwrite: true,                 // overwrite matched source files
    //     replacements: [{
    //       from: /[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}/g,
    //       to: "<%= grunt.template.today('dd/mm/yyyy') %>"
    //     }]
    //   }
    // }
  });

  grunt.registerTask('default', ["jshint", "uglify", "sass:dist", "cssmin"]);
};
