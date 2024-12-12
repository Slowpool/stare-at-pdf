<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class ViewerController extends Controller
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
        return $this->render('index');
    }

    // TODO temporary stuff
    public function actionError() {}
}
