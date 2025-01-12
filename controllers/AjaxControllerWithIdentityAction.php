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
    abstract protected function createHomePage($viewModel = null): PageResponse|PageResponseWithIdentityAction;

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
            return $this->createSinglePage();
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

    /**
     * @param mixed $pdfModel
     * @return \app\models\jsonResponses\PageResponse
     * @obsolete
     */
    public function createHomePageOld(?PdfModel $pdfModel = null): PageResponse
    {
        trigger_error('Obsolete. This method logically belongs to ViewerController.', E_USER_ERROR);
        return new PageResponse(Yii::$app->name, $this->renderPartial(Yii::getAlias('@pdf_viewer_view'), compact('pdfModel')), Yii::$app->homeUrl);
    }

    /**
     * @param ?PdfModel $pdfModel
     * @return \app\models\jsonResponses\PageResponse
     * @obsolete
     */
    public function goHomeAjaxOld(?PdfModel $pdfModel): PageResponse
    {
        trigger_error('Obsolete. This method mustn\'t be implemented in this class.', E_USER_ERROR);
        return $this->createHomePageOld($pdfModel);
    }

    public function createSinglePage(): string
    {
        return $this->renderFile(Yii::getAlias('@main_layout'));
    }

    public function createErrorPage($errorName, $message = null): PageResponse
    {
        return new PageResponse('Error', $this->renderPartial(Yii::getAlias('@error_view'), ['errorModel' => new ErrorModel($errorName, $message)]), Yii::$app->request->url);
    }
}
