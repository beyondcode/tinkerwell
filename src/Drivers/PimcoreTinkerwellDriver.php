<?php

namespace Tinkerwell\Drivers;

use Composer\InstalledVersions;
use Pimcore;
use Pimcore\Bootstrap;
use TinkerwellDriver;

class PimcoreTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath): bool
    {
        return file_exists($projectPath.'/public/index.php')
            && file_exists($projectPath.'/bin/console')
            && file_exists($projectPath.'/vendor/pimcore/pimcore');
    }

    public function bootstrap($projectPath): void
    {
        require_once $projectPath.'/vendor/autoload.php';

        Bootstrap::setProjectRoot();
        Bootstrap::bootstrap();
        Bootstrap::kernel();
    }

    public function appVersion(): string
    {
        return 'Pimcore '.InstalledVersions::getPrettyVersion('pimcore/pimcore');
    }

    public function getAvailableVariables(): array
    {
        return [
            'kernel' => Pimcore::getKernel(),
            'container' => Pimcore::getKernel()->getContainer(),
        ];
    }
}
