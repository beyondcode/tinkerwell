<?php

class KirbyTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/kirby/bootstrap.php');
    }

    public function bootstrap($projectPath)
    {
        define('KIRBY_HELPER_DUMP', false);

        require $projectPath.'/kirby/bootstrap.php';
        (new Kirby)->render();
    }

    public function appVersion()
    {
        return 'Kirby v.'.Kirby::version();
    }

    public function getAvailableVariables()
    {
        return [
            'site' => site(),
            'kirby' => kirby(),
        ];
    }
}
