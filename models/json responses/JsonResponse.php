<?php 

namespace app\models\json_responses;

use yii\base\Model;

abstract class JsonResponse extends Model {
    public string $responseType;
    public string $url;

}