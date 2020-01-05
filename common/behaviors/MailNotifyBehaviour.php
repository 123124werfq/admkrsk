<?php

namespace common\behaviors;

use common\models\MailNotifyManager;
use common\models\Notify;
use common\models\NotifyMessage;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\swiftmailer\Message;
use yii\swiftmailer\Mailer;

/**
 * Class MailNotifyBehaviour notify on email about update entity
 */
class MailNotifyBehaviour extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * Field of owner having
     * users ids for send to email
     *
     * @var string
     */
    public $userIds;

    /**
     * @var boolean
     */
    public $isAdminNotify;

    /**
     * Theme for message
     *
     * @var string
     */
    public $subject = 'Уведомление';

    /**
     * Link to this entity.
     * Used when sending emails
     *
     * @var string
     */
    public $linkToEntity;

    /**
     * Sender name
     *
     * @var string
     */
    public $senderName = 'Администрация города Красноярска';

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'mailNotify'
        ];
    }

    /**
     * Record and send message.
     */
    public function mailNotify()
    {
        $usersHasAccess = $this->owner->{$this->userIds};
        $isAdminNotify = intval($this->owner->{$this->isAdminNotify});
        $userIds = [];
        if ($usersHasAccess) {
            $userIds = static::toInt($usersHasAccess);
        }
        if ($isAdminNotify) {
            $userIds[] = Yii::$app->user->id;
        }
        $usersNotify = MailNotifyManager::getUsersForSendNotify($this->owner->primaryKey, get_class($this->owner), $userIds);

        if ($usersNotify) {
            /** @var Mailer $mailer */
            $mailer = Yii::$app->mailer;
            /** @var Message[] $messages */
            $messages = [];
            /** @var Notify $templateMessage */
            $templateMessage = Notify::getNotifyRuleByClass(get_class($this->owner));
            foreach ($usersNotify as $user) {
                $this->recordMessage($user, $templateMessage->message);
                $messages[] = $mailer->compose(
                    ['html' => 'notifyUpdate-html'],
                    [
                        'linkToEntity' => $this->linkToEntity,
                        'entityId' => $this->owner->primaryKey,
                        'message' => $templateMessage->message,
                    ]
                )
                    //todo configure swiftMailer->transport and params that send emails!
                    ->setFrom([Yii::$app->params['email'] => $this->senderName])
                    ->setTo($user->email)
                    ->setSubject($this->subject);
            }
            $mailer->sendMultiple($messages);
        }
    }

    private function recordMessage($user, $template)
    {
        $message = new NotifyMessage();
        $message->user_id = $user->id;
        $message->entity_id = $this->owner->primaryKey;
        $message->class = get_class($this->owner);
        $message->message = $template;
        $message->save();
    }

    private static function toInt($array)
    {
        $items = [];
        foreach ($array as $item) {
            $items[] = intval($item);
        }
        return $items;
    }
}