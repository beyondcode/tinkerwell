<?php

class PrestaShopTinkerwellDriver extends TinkerwellDriver
{
    protected $excludeAppFolders = ['app/cache', 'app/logs', 'vendor', 'node_modules'];

    public function canBootstrap($projectPath)
    {
        return is_file($projectPath.'/config/config.inc.php')
            && is_dir($projectPath.'/src/PrestaShopBundle');
    }

    public function bootstrap($projectPath)
    {
        require $projectPath.'/config/config.inc.php';
    }

    public function appVersion()
    {
        return 'PrestaShop v'._PS_VERSION_;
    }

    public function getAvailableVariables()
    {
        return [
            '__context' => Context::getContext(),
        ];
    }
}
