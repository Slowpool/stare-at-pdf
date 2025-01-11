<?php

namespace app\controllers;

use app\models\ErrorModel;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\PageResponseWithIdentityAction;
use app\models\jsonResponses\BookmarkUpdateResponse;
use app\models\jsonResponses\ErrorPageResponse;
use app\models\domain\PdfFileRecord;
use app\models\viewer\UpdateBookmarkModel;
use app\models\viewer\PdfModel;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

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

    public function createHomePage(?PdfModel $pdfModel = null): PageResponse
    {
        $page = parent::createHomePage($pdfModel);
        if ($pdfModel->getPdfSpecified())
        // TODO why to use Url::to()?
            $page->url = "/stare-at/$pdfModel->slug";
        return $page;
    }

    // TODO doesn't work when this page is the first page the user asks for
    public function actionIndex($pdfSlug = null, $page = null): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () use ($pdfSlug, $page): PageResponse {
            if ($pdfSlug) {
                try {
                    // TODO what exceptions can this method throw here? if none, then delete try {}
                    $pdfFileRecord = PdfFileRecord::findBySlugForCurrentUser($pdfSlug);
                    if ($pdfFileRecord) {
                        Yii::$app->response->cookies->add(new Cookie([
                            'name' => 'last-opened-pdf-id',
                            'value' => $pdfFileRecord->id,
                        ]));
                    }
                    else {
                        throw new \Exception();
                    }
                }
                catch (\Exception) {
                    throw new NotFoundHttpException('Such a PDF file was not found');
                }
            }
            else {
                try {
                    $lastOpenedPdfId = Yii::$app->request->cookies->get('last-opened-pdf-id')->value;
                    $pdfFileRecord = PdfFileRecord::findByIdForCurrentUser($lastOpenedPdfId);
                }
                catch (\Exception) {
                    // if the last opened pdf wasn't found, the request is valid (this is a case when user has no uploaded pdf at all. e.g. just registered)
                    $pdfFileRecord = null;
                }
            }
            $pdfModel = $pdfFileRecord ? new PdfModel($pdfFileRecord->id, $pdfFileRecord->name, $page ?? $pdfFileRecord->bookmark, $pdfFileRecord->slug) : null;

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
