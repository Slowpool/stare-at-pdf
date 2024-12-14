<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\PageModel;

class LibraryController extends BaseAjaxController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'upload-pdf'],
                'rules' => [
                    [
                        'actions' => ['index', 'upload-pdf'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'upload-pdf' => ['post'],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            $model = ['pdf1', 'pdf2'];
            $page = new PageModel('Library', $this->renderPartial('index', compact('model')), $this->request->url);
            return $page;
        });
    }

    public function actionUploadPdf()
    {
        return 'uploadPDf';
    }
}
