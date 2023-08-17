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
                    ->setTitle('About Wordpress')
                    ->addRow('Title', get_bloginfo('name'))
                    ->addRow('Version', get_bloginfo('version'))
                    ->addRow('Charset', get_option('blog_charset'))
                    ->addRow('Default category', get_option('default_category'))
                    ->addRow('Template', get_option('template'))
                )
        );
    }

}
