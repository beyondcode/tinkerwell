<?php

class TestbenchTinkerwellDriver extends LaravelTinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return ! file_exists($projectPath.'/public/index.php') &&
        file_exists($projectPath.'/vendor/orchestra/testbench-core/laravel/public/index.php');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        $config = \Orchestra\Testbench\Foundation\Config::loadFromYaml(
            workingPath: $projectPath,
            defaults: [
                'providers' => [],
                'dont-discover' => [],
            ],
        );
        $commander = new \Orchestra\Testbench\Console\Commander($config, $projectPath);
        $commander->laravel();
    }

    /**
     * Returns the application version.
     *
     * @return string
     */
    public function appVersion()
    {
        return 'Testbench '.app()->version();
    }

    /**
     * Returns the PHP files in the application to be referenced in the
     * Tinkerwell AI Chat.
     *
     * @return array
     */
    public function appFiles()
    {
        $skip = $this->excludeAppFolders;

        $appPath = $this->getBasePath();

        $appPath = str($appPath)->before('vendor')->replaceLast('/', '');

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($appPath, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($appPath) + 1);

            $skipFile = false;
            foreach ($skip as $folder) {
                if (strpos($relativePath, $folder.DIRECTORY_SEPARATOR) === 0) {
                    $skipFile = true;
                    break;
                }
            }
            if ($skipFile) {
                continue;
            }

            $files[] = [
                'name' => $file->getFilename(),
                'relativePath' => $relativePath,
                'fullPath' => $file->getPathname(),
            ];
        }

        return $files;
    }
}
