<?php

namespace app\models\library;

use yii\base\Model;

class AssignCategoryModel extends Model {
    public ?int $pdfFileId = null;
    public ?int $categoryId = null;
    public function rules() {
        return [
            [['pdfFileId', 'categoryId'], 'integer'],
            [['pdfFileId', 'categoryId'], 'required'],
        ];
    }
    public function formName() {
        return '';
    }
}