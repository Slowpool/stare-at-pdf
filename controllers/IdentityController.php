<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\identity\LoginForm;
use app\models\ContactForm;
use app\models\PageModel;
use Override;

class IdentityController extends BaseAjaxController
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
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function createLoginPage($viewParams)
    {
        return new PageModel('Login', $this->renderPartial(Yii::getAlias('@login_view'), $viewParams), Yii::$app->user->loginUrl);
    }

    /** Overriden. Sends either login page (for unsigned user) or home page (pdf viewer)
     * @param string $pdf_url
     * ignored. i can't imagine any case when you can pick pdf url being on the login stage.
     * This method using looks redundant everywhere. I forgot why i wanted to use it, but it defines one logic which applied everywhere.
     */
    public function goHomeAjax($pdf_url = '')
    {
        return Yii::$app->user->isGuest
            ? $this->createLoginPage([])
            : parent::goHomeAjax('');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLoginForm()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            return $this->goHomeAjax();
        });
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionSendLoginForm()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            // a signed-in user tries to login
            if (!Yii::$app->user->isGuest) {
                return $this->goHomeAjax();
            }

            $model = new LoginForm();
            // a guest successfully logged-in
            if ($model->load(Yii::$app->request->post(), 'LoginForm') && $model->login()) {
                return $this->goHomeAjax();
            }

            $model->password = '';
            // a guest failed to log in
            return $this->createLoginPage(compact('model'));
        });
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            Yii::$app->user->logout();
            return $this->goHomeAjax();
        });
    }
}
