module.exports = function(grunt) {
    grunt.initConfig({
        shipit: {
            options: {
                workspace: '.',
                deployTo: '/var/www/html/tagchat-api',
                repositoryUrl: 'https://github.com/QasAshraf/ducking-octo-robot.git',
                ignores: ['.git', 'node_modules'],
                keepReleases: 2
            },
            prod: {
                servers: 'root@bongo.qasashraf.com'
            }
        },
        phplint: {
            src: ["src/*.php"],
            web: ["web/*.php"],
            all: ["web/*.php", "src/*.php"]
        }
    });

    grunt.loadNpmTasks('grunt-shipit');
    grunt.loadNpmTasks('grunt-phplint');

    grunt.registerTask('default', ['phplint:all']);
};