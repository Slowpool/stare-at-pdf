<?php

namespace app\models\library;

use Yii;
use yii\base\Model;

class NewCategoryModel extends Model
{
    public string $name = '';
    public string $color = '';

    public function rules()
    {
        return [
            [['name', 'color'], 'required'],
            // TODO the question of eternity: where to define consts?
            [['name'], 'string', 'max' => (int)Yii::getAlias('@MAX_CATEGORY_NAME_LENGTH')],
            [['color'], 'string', 'length' => (int)Yii::getAlias('@CATEGORY_COLOR_LENGTH')],
        ];
    }

    public function formName() {
        return '';
    }
}