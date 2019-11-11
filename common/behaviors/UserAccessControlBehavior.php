<?php

namespace common\behaviors;

use common\models\AuthEntity;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class UserAccessControlBehavior extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * @var string
     */
    public $permission;

    /**
     * @var string
     */
    public $attribute = 'access_user_ids';

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'save',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'save',
        ];
    }

    public function save()
    {
        if (!$this->permission) {
            throw new InvalidConfigException('The "permission" property must be set.');
        }

        $permission = Yii::$app->authManager->getPermission($this->permission);

        if ($this->owner->{$this->attribute} !== null) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $authEntities = AuthEntity::find()->where([
                    'entity_id' => $this->owner->primaryKey,
                    'class' => get_class($this->owner),
                ]);

                foreach ($authEntities->each() as $authEntity) {
                    /* @var AuthEntity $authEntity */
                    $queryAuthEntity = AuthEntity::find()
                        ->where([
                            'and',
                            ['user_id' => $authEntity->user_id],
                            ['!=', 'entity_id', $this->owner->primaryKey],
                            ['class' => get_class($this->owner)],
                        ]);

                    if (!$queryAuthEntity->exists()) {
                        Yii::$app->authManager->revoke($permission, $authEntity->user_id);
                    }

                    $authEntity->delete();
                }

                if (is_array($this->owner->{$this->attribute})) {
                    foreach ($this->owner->{$this->attribute} as $userId) {
                        if (!AuthEntity::find()->where(['user_id' => $userId, 'entity_id' => $this->owner->primaryKey, 'class' => $this->owner->className()])->exists()) {
                            $authEntity = new AuthEntity([
                                'user_id' => $userId,
                                'entity_id' => $this->owner->primaryKey,
                                'class' => get_class($this->owner),
                            ]);
                            $authEntity->save(false);

                            if (!Yii::$app->authManager->checkAccess($authEntity->user_id, $permission->name)) {
                                Yii::$app->authManager->assign($permission, $authEntity->user_id);
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
    }
}
