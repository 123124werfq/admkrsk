<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;
/**
 * Class M191028224904Insertservicedata
 */
class M191028224904Insertservicedata extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "UPDATE service_service SET client_type = 6 WHERE client_type = 11";
        Yii::$app->db->createCommand($sql)->execute();
        $sql = "UPDATE service_service SET client_type = 4 WHERE client_type = 1";
        Yii::$app->db->createCommand($sql)->execute();
        $sql = "UPDATE service_service SET client_type = 2 WHERE client_type = 10";
        Yii::$app->db->createCommand($sql)->execute();

        $sql ="INSERT INTO servicel_situation (id_service, id_situation) VALUES
            ('47', '91'),
            ('7', '65'),
            ('8', '65'),
            ('27', '61'),
            ('28', '61'),
            ('71', '68'),
            ('72', '69'),
            ('79', '60'),
            ('99', '67'),
            ('88', '66'),
            ('89', '66'),
            ('90', '66'),
            ('37', '84'),
            ('38', '84'),
            ('40', '87'),
            ('42', '87'),
            ('41', '87'),
            ('39', '87'),
            ('43', '88'),
            ('44', '85'),
            ('45', '86'),
            ('46', '85'),
            ('51', '87'),
            ('100', '84'),
            ('102', '87'),
            ('11', '83'),
            ('12', '83'),
            ('14', '78'),
            ('23', '83'),
            ('24', '78'),
            ('25', '79'),
            ('20', '100'),
            ('21', '100'),
            ('54', '70'),
            ('98', '70'),
            ('113', '70'),
            ('60', '71'),
            ('61', '72'),
            ('114', '70'),
            ('63', '72'),
            ('93', '73'),
            ('65', '90'),
            ('69', '76'),
            ('108', '74'),
            ('73', '98'),
            ('208', '92'),
            ('190', '92'),
            ('111', '81'),
            ('19', '81'),
            ('116', '68'),
            ('118', '69'),
            ('119', '69'),
            ('120', '67'),
            ('121', '67'),
            ('172', '67'),
            ('173', '67'),
            ('141', '104'),
            ('143', '105'),
            ('144', '105'),
            ('149', '106'),
            ('151', '99'),
            ('152', '99'),
            ('153', '99'),
            ('154', '99'),
            ('155', '99'),
            ('156', '99'),
            ('180', '78'),
            ('182', '78'),
            ('183', '78'),
            ('195', '110'),
            ('191', '111'),
            ('192', '111'),
            ('193', '111'),
            ('194', '111'),
            ('205', '71'),
            ('200', '113'),
            ('201', '84'),
            ('203', '68'),
            ('202', '114'),
            ('204', '81'),
            ('206', '115'),
            ('207', '115'),
            ('209', '87')";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191028224904Insertservicedata cannot be reverted.\n";

        return false;
    }
    */
}
