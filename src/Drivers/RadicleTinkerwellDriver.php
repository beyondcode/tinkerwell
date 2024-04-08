<?php

// https://roots.io/radicle/
// https://github.com/beyondcode/tinkerwell
// https://tinkerwell.app/docs/4/extending-tinkerwell/custom-drivers
// https://discourse.roots.io/t/tinkerwell-with-sage-10-bedrock/24821

class RadicleTinkerwellDriver extends TinkerwellDriver
{
    /**
     * Determine if the driver can be used with the selected project path.
     * You most likely want to check the existence of project / framework specific files.
     *
     * @param  string  $projectPath
     * @return bool
     */
    public function canBootstrap($projectPath)
    {
        return file_exists("{$projectPath}/public/wp-config.php");
    }

    /**
     * Bootstrap the application so that any executed can access the application in your desired state.
     *
     * @param  string  $projectPath
     */
    public function bootstrap($projectPath)
    {
        if (isset($_SERVER['argv']) && ! in_array('acorn', $_SERVER['argv'])) {
            putenv('APP_RUNNING_IN_CONSOLE=false');
        }

        require "{$projectPath}/vendor/autoload.php";
        require "{$projectPath}/bedrock/application.php";
        require ABSPATH.'/wp-settings.php';
    }

    public function getAvailableVariables()
    {
        return [
            '__blog' => get_bloginfo(),
            '__options' => wp_load_alloptions(),
            '__posts' => (new \WP_Query(['posts_per_page' => -1]))->get_posts(),
            '__sage' => function ($service = null) {
                return \Roots\app($service);
            },
            'collection' => Illuminate\Support\Collection::class,
        ];
    }

    public function appVersion()
    {
        return 'Wordpress '.get_bloginfo('version').' '.app()->version();
    }

    /**
     * With panels, you can display general information as well as custom information about your
     * application in the UI of Tinkerwell. For more information, check out the documentation:
     * https://tinkerwell.app/docs/4/extending-tinkerwell/custom-drivers#panels.
     *
     * @return array
     */
    public function appPanels()
    {
        return [
            (new \Tinkerwell\Panels\WordpressPanel())
                ->setTitle('Wordpress')
                ->toArray(),
            (new \Tinkerwell\Panels\LaravelPanel())
                ->setTitle('Laravel')
                ->toArray(),
        ];
    }

    public function injectQueryLogging($code)
    {
        $wordpressCodePrefix = <<<'EOT'
try {
    if (! defined('SAVEQUERIES') || ! SAVEQUERIES) {
        define('SAVEQUERIES', true);
    }
    add_filter('log_query_custom_data', function ($data, $query, $timeInSeconds) {
        __tinkerwell_query($query);
    }, 1, 3);
} catch (\Throwable $e) {}
EOT;

        $laravelCodePrefix = <<<'EOT'
try {
    \DB::listen(function($query)
    {
        try {
            $grammar = $query->connection->getQueryGrammar();

            $properties = method_exists($grammar, 'substituteBindingsIntoRawSql') ? [
                'sql' => $grammar->substituteBindingsIntoRawSql(
                    $query->sql,
                    $query->connection->prepareBindings($query->bindings)
                ),
                'bindings' => [],
            ] : [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
            ];

            __tinkerwell_query($properties['sql'], $properties['bindings']);
        } catch (\Throwable $e) {}
    });
} catch (\Throwable $e) {}
EOT;

        return $wordpressCodePrefix.$laravelCodePrefix.PHP_EOL.$code;
    }
}
