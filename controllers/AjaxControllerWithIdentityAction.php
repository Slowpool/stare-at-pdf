<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PageModel;
use yii\web\Response;
use app\models\identity\IdentityPageModel;

// TODO any ajax request does x2 requests each click. the 8th request does 8 requests instead of 1 end etc.
class AjaxControllerWithIdentityAction extends Controller
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
                $page = new IdentityPageModel($page, $this->renderPartial(Yii::getAlias(Yii::$app->user->isGuest
                    ? '@partial_nav_login_button'
                    : '@partial_nav_logout_form')));
            }
            return $page;
        } else {
            return $this->renderSinglePage();
        }
    }

    public function createHomePage($pdf_url)
    {
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
