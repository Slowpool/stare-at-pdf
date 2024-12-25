<?php

namespace app\models\jsonResponses;

use yii\base\Model;


abstract class JsonResponse extends Model {
    public string $responseType;    
}