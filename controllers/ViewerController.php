<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\jsonResponses\PageResponse;
use app\models\domain\PdfFileRecord;

class ViewerController extends AjaxControllerWithIdentityAction
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

    public function actionIndex($pdfName, $page = null)
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () use ($pdfName, $page) {
            $pdfSpecified = $pdfName == null ? false : true;
            if ($pdfSpecified) {
                $page = $page ?? PdfFileRecord::getBookmarkByFileName($pdfName);
            }
            $PageResponse = $this->goHomeAjax($pdfName, $pdfSpecified, $page);
            return $PageResponse;
        });
    }

    // TODO temporary stuff
    public function actionError()
    {
        return $this->render('error');
    }
}
