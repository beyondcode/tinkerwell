<?php


class LumenTinkerwellDriver extends TinkerwellDriver {

    /**
     * Determine if Driver can bootstrap by checking for public/index.php and bootstrap/app.php
     *
     * @param string $projectPath
     * @return bool
     */
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath . '/public/index.php') &&
            file_exists($projectPath . '/bootstrap/app.php');
    }

    /**
     * Bootstrap the Lumen application and run to get the base state
     *
     * @param string $projectPath
     */
    public function bootstrap($projectPath)
    {
        $app = require $projectPath . '/bootstrap/app.php';
        $app->run();
    }

    public function appVersion()
    {
        return 'Lumen ' . app()->version();
    }

}
