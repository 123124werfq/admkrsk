<?php

namespace console\migrations;

use yii\db\Migration;

use common\models\Service;

/**
 * Class M191208165651ServiceCTarray
 */
class M191208165651ServiceCTarray extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $services = Service::find()->all();
        
        $insert = [];

        foreach ($services as $key => $service)
        {
            $insert[$service->id_service] = [];
            
            if ($service->client_type&2==1)
                $insert[$service->id_service][] = 'Физическое лицо';

            if ($service->client_type&4==1)
                $insert[$service->id_service][] = 'Юридическое лицо';
        }

        $this->dropColumn('service_service', 'client_type');

        $this->addColumn('service_service', 'client_type', $this->string(255).'[]');

        foreach ($services as $key => $services) {
            $services->client_type = $insert[$service->id_service];
            $services->updateAttributes(['client_type']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191208165651ServiceCTarray cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191208165651ServiceCTarray cannot be reverted.\n";

        return false;
    }
    */
}
