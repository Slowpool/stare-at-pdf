<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\json_responses\PageResponse;
use yii\web\Response;
use app\models\identity\IdentityPageResponse;

abstract class AjaxControllerWithIdentityAction extends Controller
{
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    // at first i've came up with this approach and thought it's good, but now it is starting look awkward due to each action begins with this method. 
    public function executeIfAjaxOtherwiseRenderSinglePage($callback)
    {
        if ($this->isAjax()) {
            $this->response->format = Response::FORMAT_JSON;
            $page = $callback();
            // adds login or logout button (depending upon current user status) in response if it requested
            if (Yii::$app->request->headers->has('X-Gimme-Identity-Action')) {
                $page = new IdentityPageResponse($page, $this->renderPartial(Yii::getAlias(Yii::$app->user->isGuest
                    ? '@partial_nav_login_button'
                    : '@partial_nav_logout_form')));
            }
            return $page;
        } else {
            return $this->renderSinglePage();
        }
    }

    public function createHomePage($pdfName, $pdfSpecified, $page)
    {
        return new PageResponse(Yii::$app->name, $this->renderPartial(Yii::getAlias('@home_view'), compact('pdfName', 'page', 'pdfSpecified')), Yii::$app->homeUrl);
    }

    /** @return PageResponse  */
    /** Sends the page with pdf viewer */
    public function goHomeAjax($pdfName = '', $pdfSpecified = false, $page = 0)
    {
        return $this->createHomePage($pdfName, $pdfSpecified, $page);
    }

    public function renderSinglePage()
    {
        return $this->renderFile(Yii::getAlias('@main_layout'));
    }
}
