<?php

namespace app\models\viewer;

class PdfModel
{
    public int $id;
    public string $name;
    public int $bookmark;
    public string $slug;
    public function getPdfSpecified(): bool
    {
        return $this->name != null;
    }
    public function __construct($id, $name, $bookmark, $slug)
    {
        $this->id = $id;
        $this->name = $name;
        $this->bookmark = $bookmark;
        $this->slug = $slug;
    }
}