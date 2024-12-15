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

    /** overriden */
    public function goHomeAjax()
    {
        return Yii::$app->user->isGuest
            ? $this->createLoginPage([])
            : $this->createHomePage('');
    }

    /**
     * @return string
     */
    public function actionIndex()
    {


        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLoginForm()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            if (!Yii::$app->user->isGuest) {
                return parent::goHomeAjax();
            }
            $page = new PageModel('Login', $this->renderPartial(Yii::getAlias('@login_view')), '');
            return $page;
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
            if (!Yii::$app->user->isGuest) {
                return $this->goHomeAjax();
            }

            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post(), 'LoginForm') && $model->login()) {
                return $this->goHomeAjax();
            }


            $model->password = '';
            return $this->createLoginPage();
        });
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        if ($this->isAjax()) {
            return $this->goHomeAjax();
        } else {
            return $this->goHome();
        }
    }
}
