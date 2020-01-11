<?php

namespace common\behaviors;

use common\models\AuthEntity;
use common\models\User;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class AccessControlBehavior extends Behavior
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
    public $userAttribute = 'access_user_ids';

    /**
     * @var string
     */
    public $userGroupAttribute = 'access_user_group_ids';

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

        if ($this->owner->{$this->userAttribute} !== null || $this->owner->{$this->userGroupAttribute} !== null) {
            $rbacInvalidateIds = [];

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $authEntities = AuthEntity::find()
                    ->where([
                        'entity_id' => $this->owner->primaryKey,
                        'class' => get_class($this->owner),
                    ]);

                foreach ($authEntities->each() as $authEntity) {
                    /* @var AuthEntity $authEntity */
                    $queryAuthEntity = AuthEntity::find()
                        ->where([
                            'and',
                            [
                                'or',
                                ['id_user' => $authEntity->id_user],
                                ['id_user_group' => $authEntity->id_user_group]
                            ],
                            ['!=', 'entity_id', $this->owner->primaryKey],
                            ['class' => get_class($this->owner)],
                        ]);

                    if (!$queryAuthEntity->exists()) {
                        if ($authEntity->id_user) {
                            Yii::$app->authManager->revoke($permission, $authEntity->id_user);

                            $rbacInvalidateIds[$authEntity->id_user] = $authEntity->id_user;
                        } elseif ($authEntity->id_user_group) {
                            foreach ($authEntity->userGroup->users as $user) {
                                Yii::$app->authManager->revoke($permission, $user->id);

                                $rbacInvalidateIds[$user->id] = $user->id;
                            }
                        }
                    }

                    $authEntity->delete();
                }

                if (is_array($this->owner->{$this->userAttribute})) {
                    foreach ($this->owner->{$this->userAttribute} as $userId) {
                        if (!AuthEntity::find()->where(['id_user' => $userId, 'entity_id' => $this->owner->primaryKey, 'class' => get_class($this->owner)])->exists()) {
                            $authEntity = new AuthEntity([
                                'id_user' => $userId,
                                'entity_id' => $this->owner->primaryKey,
                                'class' => get_class($this->owner),
                            ]);
                            $authEntity->save(false);

                            if (!Yii::$app->authManager->checkAccess($authEntity->id_user, $permission->name)) {
                                Yii::$app->authManager->assign($permission, $authEntity->id_user);

                                $rbacInvalidateIds[$authEntity->id_user] = $authEntity->id_user;
                            }
                        }
                    }
                }

                if (is_array($this->owner->{$this->userGroupAttribute})) {
                    foreach ($this->owner->{$this->userGroupAttribute} as $userGroupId) {
                        if (!AuthEntity::find()->where(['id_user' => $userGroupId, 'entity_id' => $this->owner->primaryKey, 'class' => get_class($this->owner)])->exists()) {
                            $authEntity = new AuthEntity([
                                'id_user_group' => $userGroupId,
                                'entity_id' => $this->owner->primaryKey,
                                'class' => get_class($this->owner),
                            ]);
                            $authEntity->save(false);

                            foreach ($authEntity->userGroup->users as $user) {
                                if (!Yii::$app->authManager->checkAccess($user->id, $permission->name)) {
                                    Yii::$app->authManager->assign($permission, $user->id);

                                    $rbacInvalidateIds[$user->id] = $user->id;
                                }
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

            foreach ($rbacInvalidateIds as $rbacInvalidateId) {
                TagDependency::invalidate(Yii::$app->cache, User::rbacCacheTag($rbacInvalidateId));
            }
        }
    }
}
