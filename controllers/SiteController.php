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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'requests' => ['POST', 'OPTIONS'],
                    'processor' => ['GET', 'OPTIONS'],
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
