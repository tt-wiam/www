<?php
/** @noinspection PhpUnused */

namespace app\controllers;

use app\forms\ProcessorForm;
use app\forms\RequestsForm;
use Throwable;
use yii\db\Exception;
use yii\filters\Cors;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    public function behaviors(): array
    {
        return [
            'cors' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['http://localhost:8080'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 86400,
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'requests' => ['POST'],
                    'processor' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actionRequests(): array
    {
        $requestsForm = new RequestsForm(\Yii::$app->request->post());

        if (($id = $requestsForm->createLoanApplication()) !== false) {
            $this->response->statusCode = 201;
            return [
                "result" => true,
                "id" => $id,
            ];
        }

        $this->response->statusCode = 400;
        return [
            "result" => false,
            'errors' => $requestsForm->getErrors(),
        ];
    }

    /**
     * @param int $delay
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function actionProcessor(int $delay = 0): array
    {
        $processorForm = new ProcessorForm([
            'delay' => $delay,
        ]);

        if ($processorForm->process()) {
            return [
                "result" => true,
            ];
        }

        $this->response->statusCode = 400;
        return [
            "result" => false,
            'errors' => $processorForm->getErrors(),
        ];
    }
}
