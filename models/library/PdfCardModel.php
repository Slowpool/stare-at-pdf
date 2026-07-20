<?php

namespace app\models\library;

use yii\base\Model;

class PdfCardModel extends Model {
    public int $id;
    public string $name;
    public int $bookmark;
    public string $slug;
    public array $backgroundColors;
    public bool $isAbandoned;
    public bool $isCompleted;
    public function __construct(int $id, string $name, int $bookmark, string $slug, array $backgroundColor, bool $isAbandoned, bool $isCompleted) {
        $this->id = $id;
        $this->name = $name;
        $this->bookmark = $bookmark;
        $this->slug = $slug;
        $this->backgroundColors = $backgroundColor;
        $this->isAbandoned = $isAbandoned;
        $this->isCompleted = $isCompleted;
    }
}