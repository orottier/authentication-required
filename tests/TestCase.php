<?php

class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        // call migrations specific to our tests, e.g. to seed the db
        // the path option should be relative to the 'path.database'
        // path unless `--path` option is available.
        $this->artisan('migrate:refresh', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/migrations'),
        ]);
    }

    protected static function postData()
    {
        return [
            'user_id' => 1,
            'published_at' => new DateTime(),
            'title' => 'Hello',
            'contents' => 'My first post',
        ];
    }
}
