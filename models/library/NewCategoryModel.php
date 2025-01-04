<?php

namespace app\models\library;

use Yii;
use yii\base\Model;

class NewCategoryModel extends Model
{
    public string $name;
    public string $color;
    
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => Yii::getAlias('@MAX_CATEGORY_NAME_LENGTH')],
            [['color'], 'string', 'length' => Yii::getAlias('@CATEGORY_COLOR_LENGTH')],
        ];
    }
}