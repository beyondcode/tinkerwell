<?php

namespace Tinkerwell\Panels;

use Tinkerwell\Panels\Table\Table;

class Panel
{
    protected $title;
    protected $content;

    public static function make()
    {
        $screen = new Panel();

        return $screen;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function setContent($content)
    {
        if ($content instanceof Table) {
            $this->content = $content->toArray();
        }

        return $this;
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
