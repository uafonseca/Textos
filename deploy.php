<?php

namespace Deployer;

require 'recipe/symfony4.php';
require 'recipe/yarn.php';

// Project name
set('lideresv2', 'my_project');

// Project repository
set('repository', 'https://github.com/uafonseca/lideres.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
add('shared_files', []);

set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-suggest');

set('writable_mode', 'chmod');

set('writable_use_sudo', true);

set('writable_chmod_recursive', true);

add('shared_dirs', ['var/log', 'var/sessions', 'vendor','public/uploads']);

// Writable dirs by web server 
add('writable_dirs', ['var/log','var/cache/dev','var/cache/dev','var/sessions', 'public/']);


// Hosts

host('23.239.26.54')
    ->set('branch', 'main')
    ->user('deploy')
    ->set('deploy_path', '/var/www/html/v2Lideres');

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
task('assets:install', 'php {{bin/console}} assets:install --symlink public');

task('build', [
    'database:update',
    'assets:install',
    'yarn:install',
    'yarn:run:production',
]);

after('deploy:vendors', 'build');
after('deploy:failed', 'deploy:unlock');
