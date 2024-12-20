<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\FailedToUploadFileResponse;
use app\models\domain\PdfFileRecord;
use app\models\library\NewFileModel;

class LibraryController extends AjaxControllerWithIdentityAction
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

    public function createIndexPage($pdfFiles, $newFileModel)
    {
        return new PageResponse('Library', $this->renderPartial('index', compact('pdfFiles', 'newFileModel')), $this->request->url);
    }

    public function actionIndex()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            // TODO add view model for pdf files
            $pdfFiles = PdfFileRecord::getFilesOfUserAsArray(Yii::$app->user->identity->name);
            $newFileModel = new NewFileModel();
            $newFileModel->newFile = null;
            return $this->createIndexPage($pdfFiles, $newFileModel);
        });
    }

    public function actionUploadPdf()
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function () {
            $newFileModel = new NewFileModel();
            if (!$newFileModel->load(Yii::$app->request->post())) {
                return $this->createUploadFormWithError('Lack of file in request', $newFileModel);
            }

            $newFileModel->newFile = UploadedFile::getInstance($newFileModel, 'newFile');
            if (!$newFileModel->validate() || !$newFileModel->newFile) {
                return $this->createUploadFormWithError(
                    // A
                    $newFileModel->errors['newFile'][0],
                    $newFileModel
                );
            }

            $pdfFile = new PdfFileRecord($newFileModel->newfile->baseName);
            if (!$pdfFile->save() || !$pdfFile->update()) {
                return $this->createUploadFormWithError(
                    // A
                    $pdfFile->errors[0],
                    $newFileModel
                );
            }

            $newFileModel = new NewFileModel();
            return $this->createUploadForm($newFileModel);
        });
        // A - show only one error == bad UX
    }

    public function createUploadFormWithError($error, $newFileModel)
    {
        $newFileModel->addError('newFile', $error);
        return $this->createUploadForm($newFileModel);
    }

    public function createUploadForm($newFileModel)
    {
        return new FailedToUploadFileResponse($this->renderPartial(Yii::getAlias('@partial_new_file_form'), compact('newFileModel')));
    }
}
