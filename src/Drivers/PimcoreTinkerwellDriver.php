<?php

use App\Kernel;
use Pimcore\Version;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\Kernel as HttpKernelKernel;
use Tinkerwell\Panels\Panel;
use Tinkerwell\Panels\Table\Section;
use Tinkerwell\Panels\Table\Table;

class PimcoreTinkerwellDriver extends TinkerwellDriver
{
    protected $kernel;

    public function canBootstrap($projectPath)
    {
        // PimcoreTinkerwellDriver has to be loaded before SymfonyTinkerwellDriver because SymfonyTinkerwellDriver's canBootstrap() method will also return true for Pimcore Projects
        return file_exists($projectPath.'/public/index.php') &&
            file_exists($projectPath.'/symfony.lock') &&
            file_exists($projectPath.'/bin/console') &&
            file_exists($projectPath.'/vendor/pimcore/pimcore/lib/Version.php');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        define('PIMCORE_PROJECT_ROOT', $projectPath);
        \Pimcore\Bootstrap::defineConstants();

        (new Dotenv())->bootEnv($projectPath.'/.env');

        $this->kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $this->kernel->boot();

        Pimcore::setKernel($this->kernel);
    }

    public function appVersion()
    {
        return 'Pimcore: '.Version::getVersion();
    }

    public function getAvailableVariables()
    {
        return [
            'kernel' => $this->kernel,
            'container' => $this->kernel->getContainer(),
        ];
    }

    public function appPanels()
    {
        $panel = Panel::make()
            ->setTitle('App Information')
            ->setContent(
                Table::make()
                ->addSection(
                    Section::make()
                ->setTitle('Applicationaa')
                ->addRow('Pimcore Version', Version::getVersion())
                ->addRow('Symfony Version', HttpKernelKernel::VERSION)
                ->addRow('PHP Version', PHP_VERSION)
                )
            )
        ;

        return [ $panel->toArray() ];

    }
}
