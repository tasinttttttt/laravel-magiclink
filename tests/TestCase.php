<?php

namespace MagicLink\Test;

use Illuminate\Database\Schema\Blueprint;
use MagicLink\MagicLinkServiceProvider;
use MagicLink\Test\TestSupport\User;
use Orchestra\Testbench\TestCase as Orchestra;
use Orbit\OrbitServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        
        $this->setUpDatabase($this->app);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            MagicLinkServiceProvider::class,
            OrbitServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:mJlbzP1TMXUPouK3KK6e9zS/VvxtWTfzfVlkn1JTqpM=');

        $app['config']->set('auth.providers.users.model', 'MagicLink\Test\TestSupport\User');

        $app['config']->set('view.paths', [__DIR__.'/stubs/resources/views']);

        $app['config']->set('filesystems.disks.local.root', __DIR__.'/stubs/storage/app');

        $app['config']->set('filesystems.disks.alternative', [
            'driver' => 'local',
            'root'   => __DIR__.'/stubs/storage/app_alternative',
        ]);

        \Config::set('orbit.paths.content', __DIR__.'/stubs/storage/app/db');
        \Config::set('orbit.paths.cache', __DIR__.'/stubs/storage/app/cache');
        \Config::set('orbit.default', 'json');

        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ]);

        $app['config']->set('database.default', 'sqlite');
    }

    /**
     * Set up the database.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function setUpDatabase($app)
    {
        \File::cleanDirectory(__DIR__.'/stubs/storage/app/db');
        \File::cleanDirectory(__DIR__.'/stubs/storage/app/cache');

        $user = User::create(['email' => 'test@user.com']);
    }

    protected function loadRoutes()
    {
        include __DIR__.'/stubs/routes.php';
    }

    public static function tearDownAfterClass(): void
    {
        \File::deleteDirectory(__DIR__.'/stubs/storage/app/db');
        \File::deleteDirectory(__DIR__.'/stubs/storage/app/cache');
    }
}
