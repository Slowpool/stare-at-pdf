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

    public function actionIndex($pdfName, $page = null): PageResponse|PageResponseWithIdentityAction|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () use ($pdfName, $page): PageResponse {
            $pdfSpecified = $pdfName != null;
            if ($pdfSpecified) {
                $page ??= PdfFileRecord::getBookmarkByFileName($pdfName);
            }
            $pageResponse = $this->goHomeAjax($pdfName, $pdfSpecified, $page);
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
            return $this->createBookmarkUpdateResponse(false, $model->pdfName ?? '');
        }

        return $this->createBookmarkUpdateResponse(PdfFileRecord::updateBookmark($model->pdfName, $model->newBookmark), $model->pdfName);
    }

    public function createBookmarkUpdateResponse(bool $success, string $pdfName): BookmarkUpdateResponse
    {
        return new BookmarkUpdateResponse($success, $this->renderPartial(Yii::getAlias('@partial_new_bookmark_form'), compact('pdfName')));
    }
}
