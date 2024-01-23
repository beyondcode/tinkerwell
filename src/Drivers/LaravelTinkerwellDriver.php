<?php

class LaravelTinkerwellDriver extends TinkerwellDriver
{
    public function canBootstrap($projectPath)
    {
        return file_exists($projectPath.'/public/index.php') &&
            file_exists($projectPath.'/artisan');
    }

    public function bootstrap($projectPath)
    {
        require_once $projectPath.'/vendor/autoload.php';

        $app = require_once $projectPath.'/bootstrap/app.php';

        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

        $kernel->bootstrap();
    }

    /**
     * Returns the application version.
     *
     * @return string
     */
    public function appVersion()
    {
        return 'Laravel '.app()->version();
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
            (new \Tinkerwell\Panels\LaravelPanel())->toArray(),
        ];
    }

    public function injectQueryLogging($code)
    {
        $codePrefix = <<<'EOT'
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

        return $codePrefix.PHP_EOL.$code;
    }

    public function logFilesPath()
    {
        return '/storage/logs';
    }
}
