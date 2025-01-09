<?php

namespace app\models\library;

use Yii;
use yii\base\Model;

class PdfCardModel extends Model {
    public string $name;
    public int $bookmark;
    // TODO despite to being implemented via array, view displayes a single color. (i don't know yet what to do with several colors. divide card on several parts with different colors? weird?)
    public array $backgroundColors;
    public function __construct(string $name, int $bookmark, string $slug, array $backgroundColor) {
        $this->name = $name;
        $this->bookmark = $bookmark;
        $this->slug = $slug;
        $this->backgroundColors = $backgroundColor;
    }
}