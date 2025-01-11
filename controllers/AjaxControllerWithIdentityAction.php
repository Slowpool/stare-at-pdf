<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\jsonResponses\ErrorPageResponse;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\PageResponseWithIdentityAction;
use app\models\viewer\PdfModel;
use app\models\ErrorModel;
use yii\web\HttpException;

abstract class AjaxControllerWithIdentityAction extends Controller
{
    // at first i've came up with this approach and thought it's good, but now it is starting look awkward due to each action begins with this method. 
    public function executeIfAjaxOtherwiseRenderSinglePage($callback): PageResponse|PageResponseWithIdentityAction|string
    {
        if ($this->isAjax()) {
            $this->ResponseFormatJson();
            try {
                $page = $callback();
                // adding login or logout button (depending upon current user status) in response if it requested
                if (Yii::$app->request->headers->has('X-Gimme-Identity-Action')) {
                    $page = new PageResponseWithIdentityAction($page, $this->renderPartial(Yii::getAlias(Yii::$app->user->isGuest
                        ? '@partial_nav_login_button'
                        : '@partial_nav_logout_form')));
                }
                return $page;
            } catch (HttpException $exception) {
                return $this->createErrorPage($exception->getName(), $exception->getMessage());
            }
        } else {
            return $this->renderSinglePage();
        }
    }

    public function isAjax(): bool
    {
        return Yii::$app->request->headers->has('X_REQUESTED_WITH') && strtolower(Yii::$app->request->headers->get('X_REQUESTED_WITH')) == 'xmlhttprequest';
    }

    public function ResponseFormatJson(): void
    {
        $this->response->format = Response::FORMAT_JSON;
    }

    public function createHomePage(?PdfModel $pdfModel = null): PageResponse
    {
        return new PageResponse(Yii::$app->name, $this->renderPartial(Yii::getAlias('@home_view'), compact('pdfModel')), Yii::$app->homeUrl);
    }

    /** @return PageResponse the page with pdf viewer */
    // TODO it mustn't be here.
    // upd: the problem is that it goes to ViewerController page, but the authorization redirects user to login page anyway. maybe i'm wrong.
    public function goHomeAjax(?PdfModel $pdfModel): PageResponse
    {
        return $this->createHomePage($pdfModel);
    }

    public function renderSinglePage(): string
    {
        return $this->renderFile(Yii::getAlias('@main_layout'));
    }

    protected function createErrorPage($errorName, $message = null): PageResponse
    {
        $errorModel = new ErrorModel($errorName, $message);
        return new PageResponse('Error', $this->renderPartial(Yii::getAlias('@error_view'), compact('errorModel')), Yii::$app->request->url);
    }
}
