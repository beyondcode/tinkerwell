<?php

namespace Tinkerwell\Panels;

use Tinkerwell\Panels\Table\Section;
use Tinkerwell\Panels\Table\Table;

class WordpressPanel extends Panel
{
    public function __construct()
    {
        $this->setTitle('App Information');

        try {
            $title = get_bloginfo('name');
            $version = get_bloginfo('version');
            $environmentType = wp_get_environment_type();
            $developmentMode = wp_get_development_mode();
        } catch (\Throwable $e) {
            $title = '';
            $version = '';
            $environmentType = '';
            $developmentMode = '';
        }

        $this->setContent(
            Table::make()
                ->addSection(Section::make()
                    ->setTitle('About WordPress')
                    ->addRow('Title', $title)
                    ->addRow('Version', $version)
                    ->addRow('Environment Type', $environmentType)
                    ->addRow('Development Mode', $developmentMode)
                    ->addRow('WP_DEBUG', defined('WP_DEBUG') ? WP_DEBUG : false)
                    ->addRow('SAVEQUERIES', defined('SAVEQUERIES') ? SAVEQUERIES : false)
                    ->addRow('WP_CACHE', defined('WP_CACHE') ? WP_CACHE : false)
                )
        );
    }
}
