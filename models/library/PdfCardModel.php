<?php

namespace app\models\library;

use Yii;
use yii\base\Model;

class PdfCardModel extends Model {
    public string $name;
    public int $bookmark;
    public string $slug;
    public array $backgroundColors;
    public function __construct(string $name, int $bookmark, string $slug, array $backgroundColor) {
        $this->name = $name;
        $this->bookmark = $bookmark;
        $this->slug = $slug;
        $this->backgroundColors = $backgroundColor;
    }
}