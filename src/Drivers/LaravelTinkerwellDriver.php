<?php

class LaravelTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/public/index.php') &&
            file_exists($projectPath.'/artisan');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        $app = require_once $projectPath.'/bootstrap/app.php';

        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        $kernel->bootstrap();
    }

    /**
     * Returns the application version.
     *
     * @return string
     */
    public function appVersion()
    {
        return 'Laravel '.app()->version();
    }

    /**
     * With panels, you can display general information as well as custom information about your
     * application in the UI of Tinkerwell. For more information, check out the documentation:
     * https://tinkerwell.app/docs/3/extending-tinkerwell/custom-drivers#panels.
     *
     * @return array
     */
    public function appPanels()
    {
        return [
            (new \Tinkerwell\Panels\LaravelPanel())->toArray(),
        ];
    }
}
