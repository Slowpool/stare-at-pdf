<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\PageModel;

class ViewerController extends BaseAjaxController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                // TODO remove error
                'only' => ['index', 'error'],
                'rules' => [
                    [
                        // TODO remove error
                        'actions' => ['index', 'error'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    // TODO remove error
                    'error' => ['get']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        // TODO how to send it once and then return only jsons? attach some value to request?
        // TODO pass url
        if ($this->isAjax()) {
            $url_for_pdf = 'uploads\semaphores.pdf';
            
            $page = new PageModel('Home', $this->renderPartial('index', ['url' => $url_for_pdf]), $this->request->url);
            $this->response->format = Response::FORMAT_JSON;
            return $page;
        } else {
            return $this->renderSinglePage();
        }
    }

    // TODO remove
    public function actionOldVersion() {
        $result = $this->render('index');
        return $result;
    }

    // TODO temporary stuff
    public function actionError()
    {
        return $this->render('error');
    }
}
