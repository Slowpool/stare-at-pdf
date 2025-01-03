<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;
use yii\helpers\FileHelper;
use yii\helpers\UserUploadsPathMaker;

use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\FailedToUploadFileResponse;
use app\models\jsonResponses\FileSuccessfullyUploadedResponse;
use app\models\domain\PdfFileRecord;
use app\models\library\PdfCardModel;
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

    public function createIndexPage($pdfCards, $newFileModel): PageResponse
    {
        return new PageResponse('Library', $this->renderPartial('index', compact('pdfCards', 'newFileModel')), $this->request->url);
    }

    public function actionIndex(): PageResponse|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
            $pdfFiles = PdfFileRecord::getFilesOfUserAsArray(Yii::$app->user->identity->name);
            // should be in automapper-like class
            $pdfCards = [];
            foreach ($pdfFiles as $pdfFile) {
                $pdfCards[] = new PdfCardModel($pdfFile['name'], $pdfFile['bookmark']);
            }
            $newFileModel = new NewFileModel();
            $newFileModel->newFile = null;
            return $this->createIndexPage($pdfCards, $newFileModel);
        });
    }

    /**
     * Returns only json.
     * @throws \yii\web\ServerErrorHttpException
     */
    // TODO throws error. debug it
    public function actionUploadPdf(): FailedToUploadFileResponse|FileSuccessfullyUploadedResponse
    {
        $this->ResponseFormatJson();
        $newFileModel = new NewFileModel();
        if (!$newFileModel->load(Yii::$app->request->post())) {
            return $this->createFailedUploadFormWithError('Lack of file in request', $newFileModel);
        }

        $newFileModel->newFile = UploadedFile::getInstance($newFileModel, 'newFile');
        if (!$newFileModel->validate() || !$newFileModel->newFile) {
            return $this->createFailedUploadFormWithError(
                // A
                $newFileModel->errors['newFile'][0],
                $newFileModel
            );
        }
        
        // '.' ruins the pretty url matching. i tried everything, nothing helped except this
        $validPdfName = str_replace('.', '-', $newFileModel->newFile->basename);
        // q: difference between UploadedFile->baseName and name
        // a: baseName has no extension
        $pdfFileRecord = PdfFileRecord::explicitConstructor($validPdfName);
        if (!$pdfFileRecord->save()) {
            // TODO it doesn't handle file deleting
            return $this->createFailedUploadFormWithError(
                // A
                $pdfFileRecord->errors['newFile'][0],
                $newFileModel
            );
        }
        // A - show only one error == bad UX
        
        // files handling
        try {
            $pdfDir = UserUploadsPathMaker::getUserUploadsPath();
            self::ensureDirRecursively($pdfDir);

            $newFileModel->newFile->saveAs(UserUploadsPathMaker::toFile($validPdfName));
        }
        catch (\Exception) {
            return $this->createFailedUploadFormWithError(
                "Failed to save file",
                $newFileModel
            );
        }

        $pdfCard = new PdfCardModel($pdfFileRecord->name, $pdfFileRecord->bookmark);
        $newFileModel = new NewFileModel();
        return $this->createSuccessfulUploadFileForm($newFileModel, $pdfCard);
    }
    
    public static function ensureDirRecursively($dir): void
    {
        if (!is_dir($dir))
            FileHelper::createDirectory($dir);
    }

    public function createFailedUploadFormWithError($error, $newFileModel): FailedToUploadFileResponse
    {
        $newFileModel->addError('newFile', $error);
        return $this->createFailedUploadForm($newFileModel);
    }

    public function createFailedUploadForm($newFileModel): FailedToUploadFileResponse
    {
        return new FailedToUploadFileResponse($this->renderPartial(Yii::getAlias('@partial_new_file_form'), compact('newFileModel')));
    }

    public function createSuccessfulUploadFileForm($newFileModel, $newPdfCard): FileSuccessfullyUploadedResponse
    {
        return new FileSuccessfullyUploadedResponse($this->createFailedUploadForm($newFileModel), $this->renderPartial(Yii::getAlias('@partial_pdf_card'), ['pdfCard' => $newPdfCard]));
    }
}
