#-----------------------------------------------------------------------------------------------------------------------
# envoy run pull-on-server --composer_update --migrate
#-----------------------------------------------------------------------------------------------------------------------

@servers(['web' => 'shegun@paynacle.ng'])

@setup
    $wwwDir = '/var/www/';
    $appDir = '/var/www/paynacle-apis';
    $php = '/usr/bin/php8.1';
@endsetup


@task('pull-on-server', ['on' => 'web'])

    cd {{ $appDir }}

    {{ $php }} artisan route:clear
    {{ $php }} artisan event:clear
    {{ $php }} artisan down

    git pull

    @if ( $composer_update )
        sudo composer install -n
    @endif

    @if ( $migrate )
        {{ $php }} artisan migrate --force
    @endif

    {{ $php }} artisan up

@endtask
