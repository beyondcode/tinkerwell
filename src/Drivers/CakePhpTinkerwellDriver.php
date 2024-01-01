<?php

use App\Application;

class CakePhpTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/config/bootstrap.php')
            && file_exists($projectPath.'/vendor/autoload.php')
            && file_exists($projectPath.'/src/Application.php');
    }

    public function bootstrap($projectPath)
    {
        require $projectPath.'/vendor/autoload.php';

        $application = new Application($projectPath.'/config');

        $application->bootstrap();
    }
}
