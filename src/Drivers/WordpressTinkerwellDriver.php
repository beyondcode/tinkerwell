<?php

class WordpressTinkerwellDriver extends TinkerwellDriver
{
    protected $excludeAppFolders = ['wp-content/cache', 'wp-content/uploads', 'wp-content/upgrade', 'node_modules', 'vendor'];

    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/wp-load.php');
    }

    public function bootstrap($projectPath)
    {
        require $projectPath.'/wp-load.php';
    }

    public function getAvailableVariables()
    {
        return [
            '__blog' => get_bloginfo(),
        ];
    }

    public function appVersion()
    {
        return 'WordPress '.get_bloginfo('version');
    }

    /**
     * With panels, you can display general information as well as custom information about your
     * application in the UI of Tinkerwell. For more information, check out the documentation:
     * https://tinkerwell.app/docs/3/extending-tinkerwell/custom-drivers#panels.
     *
     * @return array
     */
    public function appPanels()
    {
        return [
            (new \Tinkerwell\Panels\WordpressPanel())->toArray(),
        ];
    }

    public function usesCollision()
    {
        return false;
    }

    public function injectQueryLogging($code)
    {
        $codePrefix = <<<'EOT'
try {
    if (! defined('SAVEQUERIES') || ! SAVEQUERIES) {
        define('SAVEQUERIES', true);
    }
    add_filter('log_query_custom_data', function ($data, $query, $timeInSeconds) {
        __tinkerwell_query($query);
    }, 1, 3);
} catch (\Throwable $e) {}
EOT;

        return $codePrefix.PHP_EOL.$code;
    }

    public function logFilesPath()
    {
        return '/wp-content';
    }
}
