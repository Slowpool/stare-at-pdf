<?php

namespace app\models\library;

use Yii;
use yii\base\Model;

class PdfCardModel extends Model {
    public string $name;
    public int $bookmark;
    // TODO now it is implemented via array, but treated as a single color. (i don't know yet what to do with several colors. divide card on several parts with different colors? weird?r)
    public array $backgroundColor;
    public function __construct(string $name, int $bookmark, array $backgroundColor) {
        $this->name = $name;
        $this->bookmark = $bookmark;
        $this->backgroundColor = $backgroundColor;
    }
}