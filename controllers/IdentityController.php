<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
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

    /**
     * Displays homepage.
     *
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
        $page = new PageModel('Login', 'Signing in', $this->renderPartial(Yii::getAlias('@login_view')));
        // TODO implement it via flags
        if (self::isAjax()) {
            if (!Yii::$app->user->isGuest) {
                
            }
            else {
                return json_encode($page);
            }
        } else {
            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }
            return $this->render(Yii::getAlias('@single_page'), compact('page'));
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionSendLoginForm()
    {
        // TODO implement it via flags
        // if (!self::isAjax()) {
        //     if (!Yii::$app->user->isGuest) {
        //         return $this->goHome();
        //     }
        // } else {
        // }
        if (!Yii::$app->user->isGuest) {
            // return json_encode($this->);
            // TODO override goHome 
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        if (isAjax()) {
        } else {
            return $this->goHome();
        }
    }
}
