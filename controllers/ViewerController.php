<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\PageResponseWithIdentityAction;
use app\models\domain\PdfFileRecord;
use app\models\viewer\UpdateBookmarkModel;
use app\models\jsonResponses\BookmarkUpdateResponse;
use app\models\viewer\PdfModel;

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

    public function createHomePage(PdfModel $pdfModel): PageResponse
    {
        $page = parent::createHomePage($pdfModel);
        if ($pdfModel->getPdfSpecified())
            $page->url = "/stare-at/$pdfModel->slug";
        return $page;
    }

    // TODO doesn't work when this page is the first page the user asks for
    public function actionIndex($pdfSlug, $page = null): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () use ($pdfSlug, $page): PageResponse {
            $pdfFileRecord = PdfFileRecord::findBySlugForCurrentUser($pdfSlug);
            $pdfModel = new PdfModel($pdfFileRecord->id, $pdfFileRecord->name, $page, $pdfSlug);
            $pageResponse = $this->goHomeAjax($pdfModel);
            return $pageResponse;
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
        if (!$model->load(Yii::$app->request->post(), '') || !$model->validate()) {
            return $this->createBookmarkUpdateResponse(false, $model->pdfId ?? 0);
        }

        return $this->createBookmarkUpdateResponse(PdfFileRecord::updateBookmark($model->pdfId, $model->newBookmark), $model->pdfId);
    }

    public function createBookmarkUpdateResponse(bool $success, string $pdfId): BookmarkUpdateResponse
    {
        return new BookmarkUpdateResponse($success, $this->renderPartial(Yii::getAlias('@partial_new_bookmark_form'), compact('pdfId')));
    }
}
