<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\PageResponseWithIdentityAction;
use yii\web\Response;

abstract class AjaxControllerWithIdentityAction extends Controller
{
    public function isAjax(): bool
    {
        // TODO debug
        return Yii::$app->request->headers->has('X_REQUESTED_WITH') && strtolower(Yii::$app->request->headers->get('X_REQUESTED_WITH')) == 'xmlhttprequest';
    }

    // at first i've came up with this approach and thought it's good, but now it is starting look awkward due to each action begins with this method. 
    public function executeIfAjaxOtherwiseRenderSinglePage($callback): PageResponse | string
    {
        if ($this->isAjax()) {
            $this->response->format = Response::FORMAT_JSON;
            $page = $callback();
            // adding login or logout button (depending upon current user status) in response if it requested
            if (Yii::$app->request->headers->has('X-Gimme-Identity-Action')) {
                $page = new PageResponseWithIdentityAction($page, $this->renderPartial(Yii::getAlias(Yii::$app->user->isGuest
                    ? '@partial_nav_login_button'
                    : '@partial_nav_logout_form')));
            }
            return $page;
        } else {
            return $this->renderSinglePage();
        }
    }
    
    public function createHomePage($pdfName, $pdfSpecified, $bookmark): PageResponse
    {
        return new PageResponse(Yii::$app->name, $this->renderPartial(Yii::getAlias('@home_view'), compact('pdfName', 'bookmark', 'pdfSpecified')), Yii::$app->homeUrl);
    }

    /** @return PageResponse the page with pdf viewer */
    public function goHomeAjax($pdfName = '', $pdfSpecified = false, $page = 0): PageResponse
    {
        return $this->createHomePage($pdfName, $pdfSpecified, $page);
    }

    public function renderSinglePage(): string
    {
        return $this->renderFile(Yii::getAlias('@main_layout'));
    }
}
