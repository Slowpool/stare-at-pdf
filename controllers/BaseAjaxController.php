<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PageModel;

class BaseAjaxController extends Controller {
    public function isAjax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /** @return PageModel  */
    public function goHomeAjax() {
        // TODO if unsigned user? (or in another app he might don't have required role)
        return new PageModel(Yii::$app->name, Yii::$app->name, $this->renderPartial(Yii::getAlias('@home_view'), ['url' => '']));
    }
}