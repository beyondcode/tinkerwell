<?php

class OctoberCMSTinkerwellDriver extends TinkerwellDriver
{
    protected $excludeAppFolders = ["storage", "vendor", "node_modules", "public"];

    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/index.php') &&
            file_exists($projectPath.'/artisan');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        $app = require_once $projectPath.'/bootstrap/app.php';

        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        $kernel->bootstrap();
    }
}
