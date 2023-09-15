<?php

class MoodleTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/config.php')
            && file_exists($projectPath.'/course')
            && file_exists($projectPath.'/grade');
    }

    public function bootstrap($projectPath)
    {
        define('CLI_SCRIPT', true);
        require $projectPath.'/config.php';
    }

    public function appVersion()
    {
        global $CFG;

        return 'Moodle '.$CFG->release;
    }
}
