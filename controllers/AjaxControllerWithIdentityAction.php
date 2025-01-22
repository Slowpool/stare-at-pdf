<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\Response;
use yii\web\HttpException;

use app\models\jsonResponses\RedirectResponse;
use app\models\jsonResponses\ErrorPageResponse;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\PageResponseWithIdentityAction;
use app\models\viewer\PdfModel;
use app\models\ErrorModel;

abstract class AjaxControllerWithIdentityAction extends Controller
{
    abstract protected function createHomePage($viewModel = null): PageResponse;

    // at first i came up with this approach and i thought it's good, but now it is starting look awkward due to each action begins with this method. 
    /**
     * Returns `PageResponse` as a completely new page, that was requested via AJAX.
     * Wraps `PageResponse` as `PageResponseWithIdentityAction` when the identity action is requested.
     * Returns `string` when the request was not via AJAX.
     * Returns `RedirectResponse` as AJAX redirect.
     * @param callback $callback
     * @return \app\models\jsonResponses\PageResponse|\app\models\jsonResponses\PageResponseWithIdentityAction|string|\app\models\jsonResponses\RedirectResponse
     */
    protected function executeIfAjaxOtherwiseRenderSinglePage($callback): PageResponse|PageResponseWithIdentityAction|string|RedirectResponse
    {
        if ($this->isAjax()) {
            $this->ResponseFormatJson();
            try {
                $page = $callback();
                // adding login or logout button (depending upon current user status) in response if it requested
                if ($page->responseType != 'redirect' && Yii::$app->request->headers->has('X-Gimme-Identity-Action')) {
                    $page = new PageResponseWithIdentityAction($page, $this->renderPartial(Yii::getAlias(Yii::$app->user->isGuest
                        ? '@partial_nav_login_button'
                        : '@partial_nav_logout_form')));
                }
                return $page;
            } catch (HttpException $exception) {
                return $this->createErrorPage($exception->getName(), $exception->getMessage());
            }
            catch (\Exception $exception) {
                return $this->createErrorPage('Server error', 'Something went wrong');
            }
        } else {
            return $this->createSinglePage();
        }
    }

    protected function isAjax(): bool
    {
        return Yii::$app->request->headers->has('X_REQUESTED_WITH') && strtolower(Yii::$app->request->headers->get('X_REQUESTED_WITH')) == 'xmlhttprequest';
    }

    protected function ResponseFormatJson(): void
    {
        $this->response->format = Response::FORMAT_JSON;
    }

    protected function createSinglePage(): string
    {
        return $this->renderFile(Yii::getAlias('@main_layout'));
    }

    protected function createErrorPage($errorName, $message = null): PageResponse
    {
        return new PageResponse('Error', $this->renderPartial(Yii::getAlias('@error_view'), ['errorModel' => new ErrorModel($errorName, $message)]), Yii::$app->request->url);
    }

    protected function ajaxRedirect($url): RedirectResponse {
        Yii::$app->response->statusCode = 300;
        return new RedirectResponse($url);
    }
}
