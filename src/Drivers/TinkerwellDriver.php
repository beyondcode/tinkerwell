<?php

//namespace Tinkerwell\Drivers;

abstract class TinkerwellDriver
{
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
        ];
    }
}
