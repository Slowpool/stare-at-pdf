<?php

namespace app\models\viewer;

use yii\base\Model;

class UpdateBookmarkModel extends Model
{
    public $pdfId;
    public $newBookmark;
    public function rules(): array
    {
        return [
            [['pdfId', 'newBookmark'], 'required'],
            [['pdfId', 'newBookmark'], 'integer'],
            [['newBookmark', 'pdfId'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'type' => 'number'],
            // db may have max id type greater than standard 32 bits. it depends upon implementation, so it isn't being checked 
            [['newBookmark'], 'compare', 'compareValue' => PHP_INT_MAX, 'operator' => '<=', 'type' => 'number'],
        ];
    }
}