tilde_importer = require('grunt-sass-tilde-importer');

module.exports = function(grunt) {
  require("load-grunt-tasks")(grunt);

  // Project configuration.
  grunt.initConfig({
    uglify: {
      dist: {
        files: {
          /* 
          'dist': [
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
          "assets/dist/css/styles.css": [
            "assets/dev/css/styles.css"
          ]
        }
      }
    },
    sass: {
      options: {
        importer: tilde_importer
      },
      dist: {
        files: {
          "assets/dev/css/styles.css": "assets/dev/scss/styles.scss"
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
    }
  });

  grunt.registerTask('default', ["jshint", "uglify", "sass:dist", "cssmin"]);
};
