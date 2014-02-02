<?php

use Orchestra\Testbench\TestCase;
use Dimsav\Translatable\Test\Model\Country;

class TestsBase extends TestCase {

    public function setUp() {
        parent::setUp();
        $artisan = $this->app->make('artisan');
        $artisan->call('migrate', [
            '--database' => 'testbench',
            '--path'     => '../tests/migrations',
        ]);
    }

    public function testRunningMigration()
    {
        $country = Country::find(1);
        $this->assertEquals('gr', $country->iso);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = __DIR__ . '/../Translatable';

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ));
    }

    protected function getPackageAliases() {
        return array('Eloquent' => 'Illuminate\Database\Eloquent\Model');
    }

    protected function getPackageProviders() {
        return array('Dimsav\Translatable\TranslatableServiceProvider');
    }
}