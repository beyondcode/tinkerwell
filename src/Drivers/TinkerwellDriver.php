<?php

//namespace Tinkerwell\Drivers;
use Illuminate\Support\Facades\File;
abstract class TinkerwellDriver
{

    protected $excludeAppFolders = ["vendor"];

    /**
     * Determine if the driver can be used with the selected project path.
     * You most likely want to check the existence of project / framework specific files.
     *
     * @param  string  $projectPath
     * @return bool
     */
    abstract public function canBootstrap($projectPath);

    /**
     * Bootstrap the application so that any executed can access the application in your desired state.
     *
     * @param  string  $projectPath
     */
    abstract public function bootstrap($projectPath);

    public function appVersion()
    {
        return '';
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
                if (strpos($relativePath, $folder . DIRECTORY_SEPARATOR) === 0) {
                    $skipFile = true;
                    break;
                }
            }
            if ($skipFile) {
                continue;
            }

            $files[] = [
                'relativePath' => $relativePath,
                'pathname' => $file->getPathname(),
            ];
        }

        $fileStructure = [];
        foreach ($files as $file) {
            $parts = explode(DIRECTORY_SEPARATOR, $file['relativePath']);
            $filename = array_pop($parts);

            $current = &$fileStructure;
            foreach ($parts as $part) {
                if (!isset($current[$part])) {
                    $current[$part] = [];
                }
                $current = &$current[$part];
            }
            $current[$filename] = $file['pathname'];
        }

        return $fileStructure;
    }

    public function getBasePath()
    {
        return getcwd();
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
            (new \Tinkerwell\Panels\DefaultPanel())->toArray(),
        ];
    }

    public function getAvailableVariables()
    {
        return [];
    }

    public static function detectDriverForPath($projectPath)
    {
        $drivers = [];

        if (defined('TINKERWELL_HOME_PATH') && is_dir(TINKERWELL_HOME_PATH)) {
            $drivers = array_merge($drivers, static::driversIn(TINKERWELL_HOME_PATH));
        }

        $drivers = array_merge($drivers, static::driversIn($projectPath.DIRECTORY_SEPARATOR.'.tinkerwell'));

        $drivers = array_merge($drivers, static::getAvailableDrivers());

        foreach ($drivers as $driver) {
            /** @var TinkerwellDriver $driver */
            try {
                $driver = new $driver;

                if ($driver->canBootstrap($projectPath)) {
                    return $driver;
                }
            } catch (\Throwable $e) {
                //
            }
        }

        return new NullTinkerwellDriver();
    }

    /**
     * Get all of the driver classes in a given path.
     *
     * @param  string  $path
     * @return array
     */
    public static function driversIn($path)
    {
        if (! is_dir($path)) {
            return [];
        }
        $drivers = [];
        $dir = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($dir);
        $regex = new RegexIterator($iterator, '/^.+TinkerwellDriver\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file) {
            require_once $file[0];
            $drivers[] = basename($file[0], '.php');
        }

        return $drivers;
    }

    /**
     * Override the global setting for enabling collision output.
     *
     * @return bool|null
     */
    public function usesCollision()
    {
        return null;
    }

    public function injectQueryLogging($code)
    {
        return $code;
    }

    public function logFilesPath()
    {
        return '/storage/logs';
    }

    public static function getAvailableDrivers()
    {
        return [
            'InfectionTinkerwellDriver',
            'StatamicTinkerwellDriver',
            'Drupal7TinkerwellDriver',
            'Drupal8TinkerwellDriver',
            'KirbyTinkerwellDriver',
            'MoodleTinkerwellDriver',
            'LaravelTinkerwellDriver',
            'CraftTinkerwellDriver',
            'Magento2TinkerwellDriver',
            'LumenTinkerwellDriver',
            'PrestaShopTinkerwellDriver',
            'OctoberCMSTinkerwellDriver',
            'WordpressTinkerwellDriver',
            'ShopwareTinkerwellDriver',
            'SymfonyTinkerwellDriver',
            'Typo3TinkerwellDriver',
            'TestbenchTinkerwellDriver',
        ];
    }
}
