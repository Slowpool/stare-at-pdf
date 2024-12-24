<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\FailedToUploadFileResponse;
use app\models\jsonResponses\FileSuccessfullyUploadedResponse;
use app\models\domain\PdfFileRecord;
use app\views\library\PdfCardGenerator;
use app\models\library\PdfCardModel;
use app\models\library\NewFileModel;
use yii\web\ServerErrorHttpException;

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

        // q: difference between UploadedFile->baseName and name
        // a: baseName has no extension
        $pdfFileRecord = new PdfFileRecord($newFileModel->newFile->baseName);
        if (!$pdfFileRecord->save()) {
            return $this->createFailedUploadFormWithError(
                // A
                $pdfFileRecord->errors['newFile'][0],
                $newFileModel
            );
        }
        // A - show only one error == bad UX
        

        $uploads = Yii::getAlias('@uploads');
        try {
            // files handling
            self::EnsureDir($uploads);
            $pdfDir = "$uploads/" . Yii::$app->user->identity->name;
            self::EnsureDir($pdfDir);
            $newFileModel->newFile->saveAs("$pdfDir/" . $newFileModel->newFile->name);
        }
        catch (\Exception) {
            // TODO explore exceptions handling
            throw new ServerErrorHttpException('Something went wrong');
        }
        
        $pdfCard = new PdfCardModel($pdfFileRecord->name, $pdfFileRecord->bookmark);
        $newFileModel = new NewFileModel();
        return $this->createSuccessfulUploadFileForm($newFileModel, $pdfCard);
    }

    public static function EnsureDir($dir): void {
        if(!is_dir($dir))
            mkdir($dir);
    }

    public function createFailedUploadFormWithError($error, $newFileModel): FailedToUploadFileResponse
    {
        $newFileModel->addError('newFile', $error);
        return $this->createFailedUploadForm($newFileModel);
    }

    public function createFailedUploadForm($newFileModel): FailedToUploadFileResponse
    {
        return new FailedToUploadFileResponse($this->renderPartial(Yii::getAlias('@partial_new_file_form'), compact('newFileModel')), Yii::$app->request->url);
    }

    public function createSuccessfulUploadFileForm($newFileModel, $newPdfCard): FileSuccessfullyUploadedResponse
    {
        return new FileSuccessfullyUploadedResponse($this->createFailedUploadForm($newFileModel), PdfCardGenerator::render($newPdfCard));
    }
}
