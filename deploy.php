<?php

namespace Deployer;

require 'recipe/laravel.php';

date_default_timezone_set('Europe/Moscow');

// Project name
set('application', 'datame');

// Project repository
set('repository', 'https://tolik-developer@bitbucket.org/talikdev/datameweb.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', [
    'storage'
]);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
host('prod')
    ->hostname('datame.online')
    ->set('branch', 'master')
    ->set('writable_mode', 'chmod')
    ->set('env_file', '.env.prod')
    ->set('ssh_key_path', '~/.ssh/id_rsa_datame')
    ->set('deploy_path', '/var/www/www-root/data/www/engine/datame');

host('dev')
    ->hostname('datame.online')
    ->set('branch', 'develop')
    ->set('writable_mode', 'chmod')
    ->set('env_file', '.env.prod')
    ->set('ssh_key_path', '~/.ssh/id_rsa_datame')
    ->set('deploy_path', '/var/www/www-root/data/www/engine/datame');

// Tasks

task('build', function() {
    run('cd {{release_path}} && build');
});

// Tasks
desc('Copy ' . get('env_file') . " to release");
task('deploy:upload_env', function() {
    upload('{{env_file}}', '{{release_path}}/.env');
});

desc('Restart service');
task('deploy:restart', function() {
//    run("sudo systemctl restart php-fpm; sudo systemctl restart nginx");
    run('chown -R www-root:www-root {{deploy_path}}');
    run("systemctl restart supervisord");

});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

after('deploy:update_code', 'deploy:upload_env');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');

//restart nginx service
after('deploy:symlink', 'deploy:restart');

