<?php

namespace Tinkerwell\Panels\Table;
class Section
{

    protected $title;
    protected $rows = [];

    public static function make()
    {
        $section = new Section();
        return $section;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addRow($key, $value)
    {
        $this->rows[$key] = $value;
        return $this;
    }

    public function toArray()
    {
        return [
            "title" => $this->title,
            "data" => $this->rows
        ];
    }
}
