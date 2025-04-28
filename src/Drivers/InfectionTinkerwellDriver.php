<?php

use Infection\Container;

final class InfectionTinkerwellDriver extends TinkerwellDriver
{
    protected $excludeAppFolders = ["vendor", "node_modules"];

    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/bin/infection') &&
            file_exists($projectPath.'/bin/infection-debug');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';
    }

    public function getAvailableVariables()
    {
        $container = Container::create();
        $container = $container->withDynamicParameters(
            null,
            '',
            false,
            'default',
            false,
            false,
            'dot',
            false,
            null,
            null,
            false,
            null,
            null,
            null,
            null,
            ''
        );

        return [
            'container' => $container,
        ];
    }
}
