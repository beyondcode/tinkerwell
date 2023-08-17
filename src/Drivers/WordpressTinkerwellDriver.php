<?php

use Tinkerwell\ContextMenu\Label;

class WordpressTinkerwellDriver extends TinkerwellDriver
{

    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath . '/wp-load.php');
    }

    public function bootstrap($projectPath)
    {
        require $projectPath . '/wp-load.php';
    }

    public function getAvailableVariables()
    {
        return [
            '__blog' => get_bloginfo()
        ];
    }

    public function appVersion()
    {
        return 'Wordpress ' . get_bloginfo('version');
    }

    /**
     * With panels, you can display general information as well as custom information about your
     * application in the UI of Tinkerwell. For more information, check out the documentation:
     * https://tinkerwell.app/docs/3/extending-tinkerwell/custom-drivers#panels
     *
     * @return array
     */
    public function appPanels()
    {
        return [
            (new \Tinkerwell\Panels\WordpressPanel())->toArray()
        ];
    }

    public function contextMenu()
    {
        return [
            Label::create('Detected Wordpress v' . get_bloginfo('version')),
        ];
    }

    public function usesCollision()
    {
        return false;
    }
}
