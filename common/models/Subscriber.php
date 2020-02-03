<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * @property integer $id
 * @property string $email
 * @property integer $id_user
 * @property string $access_token
 * @property integer $time_subscribe
 * @property Page[] $subscriptions
 * @property array $pagesIds
 */
class Subscriber extends ActiveRecord
{
    public static function tableName()
    {
        return 'subscribers';
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Page::class, ['id_page' => 'page_id'])
            ->viaTable('subscriber_subscriptions', ['subscriber_id' => 'id']);
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getPagesIds()
    {
        return $this->getSubscriptions()
            ->select('id_page')
            ->asArray()
            ->column();
    }

    /**
     * @param string $email
     * @return Subscriber|null
     */
    public static function findByEmail(string $email)
    {
        return Subscriber::findOne(['email' => $email]);
    }

    /**
     * @param string $token
     * @return Subscriber|null
     */
    public static function findByToken(string $token)
    {
        return Subscriber::findOne(['access_token' => $token]);
    }

    /**
     * @throws Exception
     */
    public function deleteAllSubscriptions()
    {
        $pages = $this->pagesIds;
        Yii::$app->db->createCommand()->delete('subscriber_subscriptions', [
            'page_id' => $pages,
            'subscriber_id' => $this->id,
        ])->execute();
    }

    /**
     * @param array $subscriptions
     * @return bool
     * @throws Exception
     */
    public function createSubscriptions($subscriptions)
    {
        if (!$subscriptions) {
            return false;
        }
        $pages = [];
        foreach ($subscriptions as $subscription) {
            $page = Page::findOne(['id_page' => $subscription]);
            if ($page) {
                $pages[] = [
                    intval($subscription),
                    $this->id
                ];
            }
        }
        if (!empty($pages)) {
            Yii::$app->db->createCommand()->batchInsert(
                'subscriber_subscriptions',
                ['page_id', 'subscriber_id'],
                $pages
            )->execute();
            return true;
        }
        return false;
    }

    public function rules()
    {
        return [
            [['id', 'id_user', 'time_subscribe'], 'integer'],
            [['email', 'access_token'], 'string'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'time_subscribe',
                'updatedAtAttribute' => false,
            ]
        ];
    }
}