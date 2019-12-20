<?php

namespace console\migrations;

use common\models\City;
use common\models\District;
use common\models\House;
use common\models\Region;
use common\models\Street;
use common\models\Subregion;
use Yii;
use yii\db\Migration;

/**
 * Class M191220020702AlterIdHouseColumnInHouseTable
 */
class M191220020702AlterIdHouseColumnInHouseTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('create sequence if not exists map_house_id_house_seq;')->execute();
        Yii::$app->db->createCommand('alter table map_house alter column id_house set default nextval(\'public.map_house_id_house_seq\');')->execute();
        Yii::$app->db->createCommand('alter sequence map_house_id_house_seq owned by map_house.id_house;')->execute();

        Yii::$app->db->createCommand('create sequence if not exists map_region_id_region_seq;')->execute();
        Yii::$app->db->createCommand('alter table map_region alter column id_region set default nextval(\'public.map_region_id_region_seq\');')->execute();
        Yii::$app->db->createCommand('alter sequence map_region_id_region_seq owned by map_region.id_region;')->execute();

        Yii::$app->db->createCommand('create sequence if not exists map_street_id_street_seq;')->execute();
        Yii::$app->db->createCommand('alter table map_street alter column id_street set default nextval(\'public.map_street_id_street_seq\');')->execute();
        Yii::$app->db->createCommand('alter sequence map_street_id_street_seq owned by map_street.id_street;')->execute();

        Yii::$app->db->createCommand('create sequence if not exists map_subregion_id_subregion_seq;')->execute();
        Yii::$app->db->createCommand('alter table map_subregion alter column id_subregion set default nextval(\'public.map_subregion_id_subregion_seq\');')->execute();
        Yii::$app->db->createCommand('alter sequence map_subregion_id_subregion_seq owned by map_subregion.id_subregion;')->execute();

        Yii::$app->db->createCommand('create sequence if not exists map_district_id_district_seq;')->execute();
        Yii::$app->db->createCommand('alter table map_district alter column id_district set default nextval(\'public.map_district_id_district_seq\');')->execute();
        Yii::$app->db->createCommand('alter sequence map_district_id_district_seq owned by map_district.id_district;')->execute();

        Yii::$app->db->createCommand('create sequence if not exists map_city_id_city_seq;')->execute();
        Yii::$app->db->createCommand('alter table map_city alter column id_city set default nextval(\'public.map_city_id_city_seq\');')->execute();
        Yii::$app->db->createCommand('alter sequence map_city_id_city_seq owned by map_city.id_city;')->execute();

        Yii::$app->db->createCommand()->resetSequence(House::tableName())->execute();
        Yii::$app->db->createCommand()->resetSequence(Region::tableName())->execute();
        Yii::$app->db->createCommand()->resetSequence(Street::tableName())->execute();
        Yii::$app->db->createCommand()->resetSequence(Subregion::tableName())->execute();
        Yii::$app->db->createCommand()->resetSequence(District::tableName())->execute();
        Yii::$app->db->createCommand()->resetSequence(City::tableName())->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191220020702AlterIdHouseColumnInHouseTable cannot be reverted.\n";

        return false;
    }
    */
}
