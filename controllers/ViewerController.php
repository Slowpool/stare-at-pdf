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

    public function createHomePage($pdfName, $pdfSpecified, $bookmark): PageResponse {
        $page = parent::createHomePage($pdfName, $pdfSpecified, $bookmark);
        if($pdfSpecified)
            $page->url = "/stare-at/$pdfName";
        return $page;
    }

    public function actionIndex($pdfName, $page = null)
    {
        // TODO "/stare-at/book" url becomes "/"
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () use ($pdfName, $page) {
            $pdfSpecified = $pdfName == null ? false : true;
            if ($pdfSpecified) {
                $page = $page ?? PdfFileRecord::getBookmarkByFileName($pdfName);
            }
            $PageResponse = $this->goHomeAjax($pdfName, $pdfSpecified, $page);
            return $PageResponse;
        });
    }

    // TODO temporary stuff (it doesn't work)
    public function actionError()
    {
        return $this->render('error');
    }
}
