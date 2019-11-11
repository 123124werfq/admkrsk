<?php

namespace console\controllers;

use common\helpers\ProgressHelper;
use common\models\Address;
use common\models\House;
use Yii;
use yii\console\Controller;

class FiasController extends Controller
{
    /**
     * Обновление адресов
     * @throws \Exception
     */
    public function actionUpdateAddresses()
    {
        Yii::$app->db->createCommand()->truncateTable(Address::tableName());
        Yii::$app->db->createCommand()->resetSequence(Address::tableName());

        //$transaction = Yii::$app->db->beginTransaction();
        try {
            $query = House::find()->andWhere(['divtype' => 0]);

            $count = 0;
            $houseCount = $query->count();

            Yii::$app->db->createCommand()->truncateTable(Address::tableName())->execute();

            ProgressHelper::startProgress($count, $houseCount, "Обновление адресов: ");
            foreach ($query->each() as $house) {
                /* @var House $house */
                $address = new Address([
                    'houseguid' => $house['houseguid'],
                    'address' => $house->fullName,
                ]);

                if ($address->save()) {
                    $count++;
                }

                ProgressHelper::updateProgress($count, $houseCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);

            //$transaction->commit();

            $this->stdout(Yii::t('app', 'Обновлено {count} адресов', ['count' => $count]) . PHP_EOL);
        } catch (\Exception $e) {
            //$transaction->rollBack();
            throw $e;
        }
    }
}