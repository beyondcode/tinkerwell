<?php

use Tinkerwell\ContextMenu\Label;
use Tinkerwell\ContextMenu\OpenURL;
use Tinkerwell\ContextMenu\SetCode;

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
     * Provides a custom context menu for the application.
     *
     * @deprecated Use appVersion() for version information instead. For SetCode, refer to the
     * snippets feature, as seen in the documentation:
     * https://tinkerwell.app/docs/3/advanced-usage/snippets
     */
    public function contextMenu()
    {
        return [
            Label::create('Detected Laravel v'.app()->version()),

            OpenURL::create('Documentation', 'https://laravel.com/docs'),
        ];
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
