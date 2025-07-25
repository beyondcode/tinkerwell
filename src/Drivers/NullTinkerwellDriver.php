<?php

class NullTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return false;
    }

    public function bootstrap($projectPath)
    {
        if (file_exists($projectPath.'/vendor/autoload.php')) {
            require $projectPath.'/vendor/autoload.php';
        }
    }

    public function appFiles()
    {
        return [];
    }
}
