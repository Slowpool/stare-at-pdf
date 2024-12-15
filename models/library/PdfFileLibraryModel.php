<?php

namespace models\library;
use Yii;
use yii\base\Model;

class PdfFileLibraryModel extends Model {
    public string $name;
    public int $bookmark;

    public function __construct($name, $bookmark) {
        $this->name = $name;
        $this->bookmark = $bookmark;
    }
}