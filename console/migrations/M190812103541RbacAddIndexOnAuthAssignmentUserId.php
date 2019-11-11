<?php

namespace console\migrations;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;

/**
 * Class M190812103541RbacAddIndexOnAuthAssignmentUserId
 */
class M190812103541RbacAddIndexOnAuthAssignmentUserId extends Migration
{
    public $column = 'user_id';
    public $index = 'auth_assignment_user_id_idx';

    /**
     * @throws InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->createIndex($this->index, $authManager->assignmentTable, $this->column);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->dropIndex($this->index, $authManager->assignmentTable);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190812103541RbacAddIndexOnAuthAssignmentUserId cannot be reverted.\n";

        return false;
    }
    */
}
