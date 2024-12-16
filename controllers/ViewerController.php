<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\PageModel;

class ViewerController extends BaseAjaxController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                // TODO remove error
                'only' => ['index', 'error'],
                'rules' => [
                    [
                        // TODO remove error
                        'actions' => ['index', 'error'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    // TODO remove error
                    'error' => ['get']
                ]
            ]
        ];
    }

    public function actionIndex($pdf_name)
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () use ($pdf_name) {
            $pdf_url = isset($pdf_name) ? "uploads\\" . Yii::$app->user->identity->name . "\\$pdf_name.pdf" : '';
            $page = new PageModel('Home', $this->renderPartial('index', compact('pdf_url')), $this->request->url);
            return $page;
        });
    }

    // TODO remove
    public function actionOldVersion()
    {
        $result = $this->render('index');
        return $result;
    }

    // TODO temporary stuff
    public function actionError()
    {
        return $this->render('error');
    }
}
