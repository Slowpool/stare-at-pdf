<?php

namespace app\models\viewer;

use yii\base\Model;

class UpdateBookmarkModel extends Model {
    public string $pdfName;
    public int $newBookmark;

    public function rules(): array {
        return [
            [['pdfName', 'newBookmark'], 'required'],
        ];
    }
}