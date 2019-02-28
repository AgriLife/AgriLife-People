module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
    watch:
      files: [
        '**/*.scss'
      ]
      tasks: ['develop']
    sass:
      pkg:
        options:
          sourcemap: 'none'
          style: 'compressed'
          precision: 2
        files:
          'css/style.css': 'css/src/style.scss'
      dev:
        options:
          style: 'expanded'
          precision: 2
          trace: true
        files:
          'css/style.css': 'css/src/style.scss'
    sasslint:
      options:
        configFile: '.sass-lint.yml'
      target: ['css/src/*.scss']
    compress:
      main:
        options:
          archive: '<%= pkg.name %>.zip'
        files: [
          {src: ['ALP/**']},
          {src: ['css/*.css']},
          {src: ['img/**']},
          {src: ['views/**']},
          {src: ['*.php']},
          {src: ['README.md']},
        ]
    gh_release:
      options:
        token: process.env.RELEASE_KEY
        owner: 'agrilife'
        repo: 'AgriLife-People'
      release:
        tag_name: '<%= pkg.version %>'
        target_commitish: 'master'
        name: 'Release'
        body: 'Release'
        draft: false
        prerelease: false
        asset:
          name: '<%= pkg.name %>.zip'
          file: '<%= pkg.name %>.zip'
          'Content-Type': 'application/zip'


  @loadNpmTasks 'grunt-contrib-sass'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-gh-release'
  @loadNpmTasks 'grunt-sass-lint'
  @loadNpmTasks 'grunt-contrib-watch'

  @registerTask 'release', ['compress', 'setreleasemsg', 'gh_release']
  @registerTask 'setreleasemsg', 'Set release message as range of commits', ->
  @registerTask 'default', ['sasslint', 'sass:pkg']
  @registerTask 'develop', ['sasslint', 'sass:dev']
    done = @async()
    grunt.util.spawn {
      cmd: 'git'
      args: [ 'tag' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        grunt.config.set 'lasttag', matches[1]
        grunt.task.run 'shortlog'
      done(err)
      return
    return
  @registerTask 'shortlog', 'Set gh_release body with commit messages since last release', ->
    done = @async()
    releaserange = grunt.template.process '<%= lasttag %>..HEAD'
    grunt.util.spawn {
      cmd: 'git'
      args: ['shortlog', releaserange, '--no-merges']
    }, (err, result, code) ->
      if result.stdout isnt ''
        message = result.stdout.replace /(\n)\s\s+/g, '$1- '
        message = message.replace /\s*\[skip ci\]/g, ''
        grunt.config 'gh_release.release.body', message
      done(err)
      return
    return
    done = @async()


    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
