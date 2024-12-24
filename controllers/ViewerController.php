<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\jsonResponses\PageResponse;
use app\models\domain\PdfFileRecord;
use app\models\viewer\UpdateBookmarkModel;
use app\models\jsonResponses\BookmarkUpdateResponse;

class ViewerController extends AjaxControllerWithIdentityAction
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                // TODO remove error
                'only' => ['index', 'error', 'updateBookmark'],
                'rules' => [
                    [
                        // TODO remove error
                        'actions' => ['index', 'error', 'updateBookmark'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'updateBookmark' => ['post'],
                    // TODO remove error
                    'error' => ['get']
                ]
            ]
        ];
    }

    public function createHomePage($pdfName, $pdfSpecified, $bookmark): PageResponse
    {
        $page = parent::createHomePage($pdfName, $pdfSpecified, $bookmark);
        if ($pdfSpecified)
            $page->url = "/stare-at/$pdfName";
        return $page;
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

    // TODO remove error
    public function actionError(): string
    {
        return $this->render('error');
    }

    public function actionUpdateBookmark(): BookmarkUpdateResponse
    {
        $this->ResponseFormatJson();
        $model = new UpdateBookmarkModel;
        if (!$model->load(Yii::$app->request->post())) {
            return $this->createBookmarkUpdateResponse(false);
        }

        return $this->createBookmarkUpdateResponse(PdfFileRecord::updateBookmark($model->pdfName, $model->newBookmark));
    }

    public function createBookmarkUpdateResponse($result): BookmarkUpdateResponse
    {
        return new BookmarkUpdateResponse($result);
    }
}
