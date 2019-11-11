<?php

namespace console\migrations;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Migration;
use yii\rbac\DbManager;

/**
 * Class M190812103551RbacUpdatesIndexesWithoutPrefix
 */
class M190812103551RbacUpdatesIndexesWithoutPrefix extends Migration
{
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

        $this->dropIndex('auth_assignment_user_id_idx', $authManager->assignmentTable);
        $this->createIndex('{{%idx-auth_assignment-user_id}}', $authManager->assignmentTable, 'user_id');

        $this->dropIndex('idx-auth_item-type', $authManager->itemTable);
        $this->createIndex('{{%idx-auth_item-type}}', $authManager->itemTable, 'type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $authManager = $this->getAuthManager();

        $this->dropIndex('{{%idx-auth_assignment-user_id}}', $authManager->assignmentTable);
        $this->createIndex('auth_assignment_user_id_idx', $authManager->assignmentTable, 'user_id');


        $this->dropIndex('{{%idx-auth_item-type}}', $authManager->itemTable);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190812103551RbacUpdatesIndexesWithoutPrefix cannot be reverted.\n";

        return false;
    }
    */
}
