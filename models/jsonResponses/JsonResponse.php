<?php 

namespace app\models\jsonResponses;

use yii\base\Model;

// bug: composer ignores this class
// solution: it doesn't ignore. i made the wrong order of files autoloading (turned out it matters). this class must be before his; derived class.

abstract class JsonResponse extends Model {
    public string $responseType;
    public string $url;

}