<?php

class TestbenchTinkerwellDriver extends LaravelTinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return ! file_exists($projectPath.'/public/index.php') &&
        file_exists($projectPath.'/vendor/orchestra/testbench-core/laravel/public/index.php');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        $config = \Orchestra\Testbench\Foundation\Config::loadFromYaml(
            workingPath: $projectPath,
            defaults: [
                'providers' => [],
                'dont-discover' => [],
            ],
        );
        $commander = new \Orchestra\Testbench\Console\Commander($config, $projectPath);
        $commander->laravel();
    }

    /**
     * Returns the application version.
     *
     * @return string
     */
    public function appVersion()
    {
        return 'Testbench '.app()->version();
    }
}
