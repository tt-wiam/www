<?php

namespace app\controllers;

use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'requests' => ['post'],
                    'processor' => ['get'],
                ],
            ],
        ];
    }

    public function actionRequests()
    {

    }

    public function actionProcessor(int $delay = 0)
    {

        if ($delay < 0) {
            throw new BadRequestHttpException('Delay must be greater than zero');
        }

        sleep($delay);

        return [
            'delay' => $delay,
        ];
    }
}
