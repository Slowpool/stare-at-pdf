<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\identity\LoginForm;
use app\models\viewer\PdfModel;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\PageResponseWithIdentityAction;

class IdentityController extends AjaxControllerWithIdentityAction
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'send-login-form' => ['post'],
                    'login-form' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    // public function actions()
    // {
    //     return [
    //         'error' => [
    //             'class' => 'yii\web\ErrorAction',
    //         ],
    //         'captcha' => [
    //             'class' => 'yii\captcha\CaptchaAction',
    //             'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
    //         ],
    //     ];
    // }

    /**
     * @obsolete
     * @param mixed $viewParams
     * @return \app\models\jsonResponses\PageResponse
     */
    public function createLoginPageOld($viewParams): PageResponse
    {
        trigger_error('Obsolete', E_USER_ERROR);
        return new PageResponse('Login', $this->renderPartial(Yii::getAlias('@login_view'), $viewParams), Yii::$app->user->loginUrl);
    }

    /**
     * @param PdfModel $pdfModel IGNORED inherited param. i can't imagine any case when you have a need to pick pdf url being on the login stage.
     * @obsolete
     */
    public function goHomeAjaxOld(?PdfModel $pdfModel = null): PageResponse
    {
        trigger_error('Obsolete', E_USER_ERROR);
        return Yii::$app->user->isGuest
            ? $this->createLoginPageOld([])
            : parent::goHomeAjaxOld($pdfModel);
    }

    /**
     * @param ?PdfModel $pdfModel ignored
     * @return \app\models\jsonResponses\PageResponse
     */
    public function createHomePage($loginForm = null): PageResponse
    {
        return new PageResponse('Login', $this->renderPartial(Yii::getAlias('@login_view'), compact('loginForm')), Yii::$app->user->loginUrl);
    }

    /**
     * @return PageResponse|PageResponseWithIdentityAction|string
     */
    public function actionLoginForm(): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
            return $this->createHomePage();
        });
    }

    /**
     * @return Response|string
     */
    public function actionSendLoginForm(): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
            // a signed-in user tries to login
            if (!Yii::$app->user->isGuest) {
                // redirect. Looks weird
                return Yii::$app->runAction('viewer/index');
            }

            $loginForm = new LoginForm();
            // a guest successfully logged-in
            if ($loginForm->load(Yii::$app->request->post(), 'LoginForm') && $loginForm->login()) {
                return Yii::$app->runAction('viewer/index');
            }

            $loginForm->password = '';
            // a guest failed to log in
            return $this->createHomePage($loginForm);
        });
    }

    /**
     * @return Response
     */
    public function actionLogout(): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
            Yii::$app->user->logout();
            return $this->createHomePage();
        });
    }
}
