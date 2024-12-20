<?php

namespace app\models\library;

use Yii;
use yii\base\Model;

class PdfCardModel extends Model {
    public string $name;
    public int $bookmark;

    public function __construct($name, $bookmark) {
        $this->name = $name;
        $this->bookmark = $bookmark;
    }
}