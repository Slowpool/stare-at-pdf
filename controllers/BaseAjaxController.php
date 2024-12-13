<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PageModel;

class BaseAjaxController extends Controller {
    public function isAjax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public function goHomeAjax() {
        return new PageModel(Yii::$app->name, Yii::$app->name, $this->renderPartial(Yii::getAlias('@home_view'), ['url' => '']));
    }
}