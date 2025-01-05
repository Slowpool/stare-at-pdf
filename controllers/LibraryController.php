<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;
use yii\helpers\FileHelper;
use yii\helpers\UserUploadsPathHelper;

use app\models\jsonResponses\PageResponse;
use app\models\jsonResponses\FailedToUploadFileResponse;
use app\models\jsonResponses\FileSuccessfullyUploadedResponse;
use app\models\jsonResponses\AddNewCategoryResponse;
use app\models\domain\PdfFileRecord;
use app\models\domain\PdfFileCategoryRecord;
use app\models\library\PdfCardModel;
use app\models\library\AddedPdfModel;
use app\models\library\NewFileModel;
use app\models\library\NewCategoryModel;
use app\models\library\AssignCategoryModel;
use app\models\library\AddedCategoryModel;

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

    public function createIndexPage($pdfCards, $newFileModel, $newCategoryModel, $assignCategoryModel): PageResponse
    {
        $categoryIds = PdfFileCategoryRecord::getCategoryIdsAndNames();
        $pdfFileIds = PdfFileRecord::getPdfFileIdsAndNames();
        return new PageResponse('Library', $this->renderPartial('index', compact('pdfCards', 'newFileModel', 'newCategoryModel', 'assignCategoryModel', 'pdfFileIds', 'categoryIds')), $this->request->url);
    }

    public function actionIndex(): PageResponse|string
    {
        return $this->executeIfAjaxOtherwiseRenderSinglePage(function (): PageResponse {
            $pdfFiles = PdfFileRecord::getFilesOfUserAsArray();
            // should be in automapper-like class
            $pdfCards = [];
            foreach ($pdfFiles as $pdfFile) {
                $pdfCards[] = new PdfCardModel($pdfFile['name'], $pdfFile['bookmark']);
            }
            $newFileModel = new NewFileModel();
            $newCategoryModel = new NewCategoryModel();
            $assignCategoryModel = new AssignCategoryModel();
            return $this->createIndexPage($pdfCards, $newFileModel, $newCategoryModel, $assignCategoryModel);
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

        // '.' ruins the pretty url matching. i tried everything, nothing helped except this
        $validPdfName = str_replace('.', '-', $newFileModel->newFile->basename);
        // q: difference between UploadedFile->baseName and name
        // a: baseName has no extension
        $pdfFileRecord = PdfFileRecord::explicitConstructor($validPdfName);
        if (!$pdfFileRecord->save()) {
            // TODO handle file deleting
            return $this->createFailedUploadFormWithError(
                // A
                $pdfFileRecord->errors['newFile'][0],
                $newFileModel
            );
        }
        // A - show only one error == bad UX

        // files handling
        try {
            $pdfDir = UserUploadsPathHelper::getUserUploadsPath();
            self::ensureDirRecursively($pdfDir);

            $newFileModel->newFile->saveAs(UserUploadsPathHelper::toFile($validPdfName));
        } catch (\Exception) {
            return $this->createFailedUploadFormWithError(
                "Failed to save file",
                $newFileModel
            );
        }

        $pdfCard = new PdfCardModel($pdfFileRecord->name, $pdfFileRecord->bookmark);
        $newFileModel = new NewFileModel();
        $addedPdfModel = new AddedPdfModel($pdfFileRecord->name, $pdfFileRecord->id);
        return $this->createSuccessfulUploadFileForm($newFileModel, $pdfCard, $addedPdfModel);
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

    public function createSuccessfulUploadFileForm(NewFileModel $newFileModel, PdfCardModel $newPdfCard, AddedPdfModel $addedPdfModel): FileSuccessfullyUploadedResponse
    {
        return new FileSuccessfullyUploadedResponse($this->createFailedUploadForm($newFileModel), $this->renderPartial(Yii::getAlias('@partial_pdf_card'), ['pdfCard' => $newPdfCard]), $addedPdfModel);
    }

    public function actionAddNewCategory(): AddNewCategoryResponse
    {
        $this->ResponseFormatJson();

        $newCategoryModel = new NewCategoryModel();
        if (!$newCategoryModel->load(Yii::$app->request->post(), '') || !$newCategoryModel->validate()) {
            // lack of data
            return $this->createAddNewCategoryResponse();
        }

        $pdfFileCategoryRecord = new PdfFileCategoryRecord();
        $pdfFileCategoryRecord->explicitConstructor($newCategoryModel->name, $newCategoryModel->color);
        if (!$pdfFileCategoryRecord->save()) {
            // domainly incorrect data
            return $this->createAddNewCategoryResponse();
        }

        $addedCategoryModel = new AddedCategoryModel($pdfFileCategoryRecord->name, $pdfFileCategoryRecord->id);
        return $this->createAddNewCategoryResponse($addedCategoryModel);
    }

    /**
     * When $addedCategoryModel is provided, returned response is successful adding of new category. Otherwise - as failed one.
     * @param mixed $addedCategoryModel
     * @return \app\models\jsonResponses\AddNewCategoryResponse
     */
    public function createAddNewCategoryResponse(AddedCategoryModel $addedCategoryModel = null): AddNewCategoryResponse
    {
        return new AddNewCategoryResponse($addedCategoryModel != null, $this->renderPartial(Yii::getAlias('@partial_new_category_form'), ['newCategoryModel' => new NewCategoryModel()]), $addedCategoryModel);
    }

    public function actionAssignCategory()
    {
        
    }
}
