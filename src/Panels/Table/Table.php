<?php

namespace Tinkerwell\Panels\Table;

class Table
{
    protected $sections = [];

    public static function make()
    {
        return new Table();
    }

    public function addSection(Section $section)
    {
        $this->sections[$section->getTitle()] = $section->toArray();

        return $this;
    }

    public function toArray()
    {
        return $this->sections;
    }
}
