<?php

namespace app\models\jsonResponses;

use yii\base\Model;


abstract class UrllessJsonResponse extends Model {
    public string $responseType;    
}