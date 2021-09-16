<?php

namespace Deployer;

require 'recipe/symfony4.php';
require 'recipe/yarn.php';

// Project name
set('lideresv2', 'my_project');

// Project repository
set('repository', 'https://github.com/uafonseca/lideres.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys 
add('shared_files', []);

set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-suggest');

set('writable_mode', 'chmod');

set('writable_use_sudo', true);

set('writable_chmod_recursive', true);

add('shared_dirs', ['var/log', 'var/sessions', 'vendor','public/uploads']);

// Writable dirs by web server 
add('writable_dirs', ['var/log','var/cache','var/sessions', 'public/']);

set('ssh_multiplexing', false);

set('default_timeout', 120000);
// Hosts

host('classbook.edu')
    ->hostname('172.105.16.81')
    ->set('branch', 'bookdy')
    ->user('deploy')
    ->set('deploy_path', '/var/www/html/classbook');


host('demo')
    ->hostname('172.105.16.81')
    ->set('branch', 'bookdy')
    ->user('deploy')
    ->set('deploy_path', '/var/www/html/demo_classbook');

set('release_name', function () {
    return date('YmdHis');
});

set('keep_releases', 2);

set('env', [
    'APP_ENV' => 'production',
]);

set('release_version_text', function () {
    $release = get('branch');
    if (input()->hasOption('tag') && !empty(input()->getOption('tag'))) {
        $release = input()->getOption('tag');
    }
    return $release;
});

// Tasks


desc('Compile assets in production');
task('yarn:run:production', 'yarn run encore production');

desc('Database update');
task('database:update', function () {
    run('php {{bin/console}} doctrine:schema:update --force');
});

desc('Publish assets');
task('assets:install', 'php {{bin/console}} assets:install public');

desc('Expose routes');
task('routes:expose', 'php {{bin/console}} fos:js-routing:dump --target=public/js/fos_js_routes.js');

desc('Set writable');
task('set:writable', function () {
    run('sudo chmod -R 777 {{deploy_path}}/releases/{{release_name}}/var/log');
});


task('build', [
    'database:update',
    'assets:install',
    'routes:expose',
    'yarn:install',
    'yarn:run:production',
    // 'set:writable'
]);

after('deploy:vendors', 'build');
after('deploy:failed', 'deploy:unlock');
