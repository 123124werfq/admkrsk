<?php

namespace frontend\controllers;

use common\components\odata\DataService;
use ODataProducer\OperationContext\DataServiceHost;
use Yii;

class ODataController extends \yii\web\Controller
{
    /**
     * @return mixed
     * @throws \ODataProducer\Common\ODataException
     */
    public function actionIndex()
    {
        $host = new DataServiceHost();
        $host->setAbsoluteServiceUri('http://localhost:8080/odata.svc');

        $service = new DataService();
        $service->setHost($host);
        $service->handleRequest();

        $odataResponse = $host->getWebOperationContext()->outgoingResponse();
        $response = yii::$app->response;

        foreach ($odataResponse->getHeaders() as $headerName => $headerValue) {
            if (!is_null($headerValue)) {
                $response->headers->set($headerName, $headerValue);
            }
        }

        return $odataResponse->getStream();
    }
}
