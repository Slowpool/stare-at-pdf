<?php

namespace app\models\viewer;

use yii\base\Model;

class UpdateBookmarkModel extends Model
{
    public $pdfName;
    public $newBookmark;

    public function rules(): array
    {
        return [
            [['pdfName', 'newBookmark'], 'required'],
            ['pdfName', 'string'],
            ['newBookmark', 'integer'],
            ['newBookmark', 'compare', 'compareValue' => 1, 'operator' => '>=', 'type' => 'number'],
            ['newBookmark', 'compare', 'compareValue' => PHP_INT_MAX, 'operator' => '<=', 'type' => 'number'],
        ];
    }
}