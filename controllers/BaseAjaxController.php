<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PageModel;
use yii\web\Response;

class BaseAjaxController extends Controller
{
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function executeIfAjaxOtherwiseRenderSinglePage($callback)
    {
        if ($this->isAjax()) {
            $this->response->format = Response::FORMAT_JSON;
            return $callback();
        } else {
            return $this->renderSinglePage();
        }
    }

    public function createHomePage($pdf_url) {
        return new PageModel(Yii::$app->name, $this->renderPartial(Yii::getAlias('@home_view'), ['pdf_url' => $pdf_url]), Yii::$app->homeUrl);
    }

    /** @return PageModel  */
    /** Sends the page with pdf viewer */
    public function goHomeAjax($pdf_url = '')
    {
        return $this->createHomePage($pdf_url);
    }

    public function renderSinglePage()
    {
        return $this->renderFile(Yii::getAlias('@main_layout'));
    }
}
