<?php

namespace Tinkerwell\Panels;

use Tinkerwell\Panels\Table\Section;
use Tinkerwell\Panels\Table\Table;

class DefaultPanel extends Panel
{

    public function __construct()
    {
        $this->setTitle('App Information');

        $information = Table::make();

        $information->addSection(
            Section::make()
                ->setTitle('Application')
                ->addRow("PHP Version", PHP_VERSION)
        );

        $this->setContent($information);
    }

}
