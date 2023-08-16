<?php

namespace Tinkerwell\Panels;

use Illuminate\Support\Facades\Artisan;
use Tinkerwell\Panels\Table\Section;
use Tinkerwell\Panels\Table\Table;

class LaravelPanel extends Panel
{

    public function __construct()
    {
        $this->setTitle('App Information');

        $about = null;

        try {
            if (version_compare(app()->version(), '9.21.0', '>=')) {
                $about = $this->getAboutInformation();
            } else {
                $about = $this->getBasicInformation();
            }
        } catch (Throwable $e) {}

        $this->setContent($about);
    }


    protected function getAboutInformation()
    {
        \Illuminate\Support\Facades\Artisan::call('about --json');

        $about = collect(json_decode(\Illuminate\Support\Facades\Artisan::output(), true));


        $information = Table::make();

        foreach ($about as $name => $content) {
            $section = Section::make()
                ->setTitle(mb_convert_case(str_replace('_', ' ', $name), MB_CASE_TITLE));

            foreach ($content as $key => $value) {
                $title = mb_convert_case(str_replace('_', ' ', $key), MB_CASE_TITLE);

                if ($key === 'url') {
                    $title = mb_strtoupper($key);
                }

                if (str_contains($title, 'Php')) {
                    $title = str_replace('Php', 'PHP', $title);
                }
                $section->addRow($title, $value);
            }

            $information->addSection($section);
        }

        return $information;
    }

    protected function getBasicInformation()
    {
        $information = Table::make();

        $appData = [
            "Application Name" => config('app.name'),
            "Laravel Version" => app()->version(),
            "PHP Version" => PHP_VERSION,
            "Environment" => config('app.env'),
            "Debug Mode" => config('app.debug') ? 'ENABLED' : 'NOT ENABLED',
            "URL" => config('app.url'),
        ];

        $section = Section::make()
            ->setTitle('Application');

        foreach ($appData as $key => $value) {
            $section->addRow($key, $value);
        }

        $information->addSection($section);

        return $information;
    }

}
