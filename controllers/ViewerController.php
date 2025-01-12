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
                'only' => ['index', 'update-bookmark'],
                'rules' => [
                    [
                        'actions' => ['index', 'update-bookmark'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'update-bookmark' => ['post'],
                ],
            ],
        ];
    }

    private function createBookmarkUpdateResponse(bool $success, string $pdfId): BookmarkUpdateResponse
    {
        return new BookmarkUpdateResponse($success, $this->renderPartial(Yii::getAlias('@partial_new_bookmark_form'), compact('pdfId')));
    }

    /**
     * @param ?PdfModel $pdfModel
     * @return \app\models\jsonResponses\PageResponse
     */
    protected function createHomePage($pdfModel = null): PageResponse
    {
        return new PageResponse(Yii::$app->name, $this->renderPartial(Yii::getAlias('@pdf_viewer_view'), compact('pdfModel')), Yii::$app->homeUrl);
    }

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
                    } else {
                        throw new \Exception();
                    }
                } catch (\Exception) {
                    throw new NotFoundHttpException('Such a PDF file was not found');
                }
            } else {
                try {
                    $lastOpenedPdfId = Yii::$app->request->cookies->get('last-opened-pdf-id')->value;
                    $pdfFileRecord = PdfFileRecord::findByIdForCurrentUser($lastOpenedPdfId);
                } catch (\Exception) {
                    // if the last opened pdf wasn't found, the request is valid (this is a case when user has no uploaded pdf at all. e.g. just registered)
                    $pdfFileRecord = null;
                }
            }
            // TODO automapper
            $pdfModel = $pdfFileRecord
                ? new PdfModel($pdfFileRecord->id, $pdfFileRecord->name, $page ?? $pdfFileRecord->bookmark, $pdfFileRecord->slug)
                : null;

            $pageResponse = $this->createHomePage($pdfModel);
            return $pageResponse;
        });
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
}
