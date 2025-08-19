<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

class SymfonyTinkerwellDriver extends TinkerwellDriver
{
    protected $kernel;
    protected $excludeAppFolders = ['var', 'vendor', 'node_modules', 'public'];

    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/public/index.php') &&
            file_exists($projectPath.'/symfony.lock') &&
            file_exists($projectPath.'/bin/console');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        (new Dotenv())->bootEnv($projectPath.'/.env');

        $this->kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
        $this->kernel->boot();
    }

    public function appVersion()
    {
        return 'Symfony '.Symfony\Component\HttpKernel\Kernel::VERSION;
    }

    public function getAvailableVariables()
    {
        return [
            'kernel' => $this->kernel,
            'container' => $this->kernel->getContainer(),
        ];
    }

    public function logFilesPath()
    {
        return '/var/log';
    }
}
