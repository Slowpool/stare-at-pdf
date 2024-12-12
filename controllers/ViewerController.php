<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class ViewerController extends Controller {



    public function actionIndex() {
        // TODO how to request it once and then return json? attach some value to request?
        return $this->render('index');
    }

    // TODO temporary stuff
    public function actionError() {
        
    }

    // public function action

}