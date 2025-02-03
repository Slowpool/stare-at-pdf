<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use app\models\identity\LoginForm;
use app\models\identity\RegistrationForm;
use app\models\viewer\PdfModel;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\RedirectResponse;
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
     * @return \app\models\jsonResponses\PageResponse
     */
    protected function createHomePage($loginForm = null): PageResponse
    {
        $loginForm ??= new LoginForm; 
        return new PageResponse('Login', $this->renderPartial(Yii::getAlias('@login_view'), compact('loginForm')), Yii::$app->user->loginUrl);
    }
     
    protected function createRegistrationPage(RegistrationForm $registrationForm = null): PageResponse {
        $registrationForm = $registrationForm ?: new RegistrationForm; 
        return new PageResponse('Registration', $this->renderPartial('@registration_view', compact('registrationForm')), '/registration');
    }

    /**
     * @return PageResponse|PageResponseWithIdentityAction|string
     */
    public function actionLoginForm(): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
                return Yii::$app->user->isGuest
                ? $this->createHomePage()
                : $this->ajaxRedirect('/');
        });
    }

    /**
     * @return Response|string
     */
    public function actionSendLoginForm(): PageResponse|PageResponseWithIdentityAction|string|RedirectResponse
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse|RedirectResponse {
            // a signed-in user tries to login
            if (!Yii::$app->user->isGuest) {
                return $this->ajaxRedirect('/');
            }

            $loginForm = new LoginForm();
            // a guest successfully logged-in
            if ($loginForm->load(Yii::$app->request->post(), 'LoginForm') && $loginForm->login()) {
                return $this->ajaxRedirect('/');
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

    public function actionRegistrationForm(): PageResponse|PageResponseWithIdentityAction|RedirectResponse|string {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
            return Yii::$app->user->isGuest
                ? $this->createRegistrationPage()
                : $this->ajaxRedirect('/');
        });
    }

    // TODO problem: this method is copy-paste (except `$registrationForm->register()` moment) of actionSendLoginForm
    public function actionSendRegistrationForm(): PageResponse|PageResponseWithIdentityAction|RedirectResponse|string {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse|RedirectResponse {
            // a signed-in user tries to register
            if (!Yii::$app->user->isGuest) {
                return $this->ajaxRedirect('/');
            }

            $registrationForm = new RegistrationForm;
            // a guest successfully registered
            if ($registrationForm->load(Yii::$app->request->post(), 'RegistrationForm') && $registrationForm->registerAndLogin()) {
                return $this->ajaxRedirect('/');
            }

            $registrationForm->password = '';
            // a guest failed to log in
            return $this->createRegistrationPage($registrationForm);
        });
    }
}
