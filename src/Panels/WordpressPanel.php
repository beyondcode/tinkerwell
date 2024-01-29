<?php

namespace Tinkerwell\Panels;

use Tinkerwell\Panels\Table\Section;
use Tinkerwell\Panels\Table\Table;

class WordpressPanel extends Panel
{
    public function __construct()
    {
        $this->setTitle('App Information');

        $this->setContent(
            Table::make()
                ->addSection(Section::make()
                    ->setTitle('About WordPress')
                    ->addRow('Title', get_bloginfo('name'))
                    ->addRow('Version', get_bloginfo('version'))
                    ->addRow('Environment Type', function_exists('wp_get_environment_type') ? wp_get_environment_type() : null)
                    ->addRow('Development Mode', function_exists('wp_get_development_mode') ? wp_get_development_mode() : null)
                    ->addRow('WP_DEBUG', defined('WP_DEBUG') ? WP_DEBUG : false)
                    ->addRow('SAVEQUERIES', defined('SAVEQUERIES') ? SAVEQUERIES : false)
                    ->addRow('WP_CACHE', defined('WP_CACHE') ? WP_CACHE : false)
                )
        );
    }
}
