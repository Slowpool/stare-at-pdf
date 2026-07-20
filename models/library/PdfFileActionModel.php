<?php

namespace app\models\library;

use yii\base\Model;

class PdfFileActionModel extends Model
{
    public int $pdfFileId = 0;

    public function rules()
    {
        return [
            [['pdfFileId'], 'required'],
            [['pdfFileId'], 'integer'],
        ];
    }

    public function formName()
    {
        return '';
    }
}