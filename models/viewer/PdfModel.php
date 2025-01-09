<?php

namespace app\models\viewer;

class PdfModel
{
    public string $pdfName;
    public int $bookmark;
    public string $slug;
    public function getPdfSpecified(): bool
    {
        return $this->pdfName != null;
    }
    public function __construct($name, $bookmark, $slug)
    {
        $this->pdfName = $name;
        $this->bookmark = $bookmark;
        $this->slug = $slug;
    }
}