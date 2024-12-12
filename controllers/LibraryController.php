<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class LibraryController extends Controller {



    public function actionIndex() {
        return 'libraryIndex';
    }

    public function actionUploadPdf() {
        return 'uploadPDf';
    }

}