<?php

namespace app\models\library;

use yii\base\Model;

class NewFileModel extends Model {
    public $newFile;
    
    public function rules() {
        return [
            [['newFile'], 'required'],
            [['newFile'], 'file', 'extensions' => 'pdf', 'maxFiles' => 1],
        ];
    }
    
}