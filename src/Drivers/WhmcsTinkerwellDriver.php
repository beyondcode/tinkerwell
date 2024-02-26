<?php

declare(strict_types=1);

class WhmcsTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return is_dir($projectPath . '/vendor/whmcs/whmcs-foundation');
    }

    public function bootstrap($projectPath)
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        if (!defined("ROOTDIR")) {
            define("ROOTDIR", realpath(__DIR__ . '/..'));
        }

        if (!defined("WHMCS")) {
            define("WHMCS", true);
        }

        require_once ROOTDIR . "/includes/dbfunctions.php";
        require_once ROOTDIR . "/includes/functions.php";

        $errMgmt = WHMCS\Utility\ErrorManagement::boot();
        $runtimeStorage = new WHMCS\Config\RuntimeStorage();
        $runtimeStorage->errorManagement = $errMgmt;
        WHMCS\Utility\Bootstrap\Application::boot($runtimeStorage);
        App::self();
    }
}
