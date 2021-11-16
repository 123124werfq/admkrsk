<?php

namespace common\behaviors;

use common\models\MailNotifyManager;
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
     * @var int
     */
    public $timeRuleAttribute;

    /**
     * @var string
     */
    public $messageAttribute;

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'mailNotify',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'mailNotify'
        ];
    }

    /**
     * Record and send message.
     */
    public function mailNotify()
    {
        // необходимый костыль: отсылаем письма админам коллекции, а не записи, 
        // поэтому для каждой записи эмулируем вызов апдейта всей коллекции вцелом - КБ

        if( strpos(get_class($this->owner),"CollectionRecord"))
        {
            $hostSender = $this->owner->collection;

            $uids = Yii::$app->db->createCommand("select user_id from notify_users where class='common\models\Collection' and entity_id=".$this->owner->id_collection)->queryColumn('user_id');
            $usersHasAccess = $uids;
        }
        else
        { 
            $hostSender = $this->owner;
            $usersHasAccess = $hostSender->{$this->userIds};
        }

        $senderName = isset($hostSender->name)?"в коллекции {$hostSender->name}":"на странице {$hostSender->title}";

        $isAdminNotify = intval($hostSender->{$this->isAdminNotify});
        $userIds = [];
        if ($usersHasAccess) {
            $userIds = static::toInt($usersHasAccess);
        }
        if ($isAdminNotify) {
            $userIds[] = Yii::$app->user->id;
        }
              
        $usersNotify = (new MailNotifyManager([
            'model' => $this->owner,
            'usersNotify' => $userIds,
            'timeRule' => $hostSender->{$this->timeRuleAttribute},
        ]))->getNotifyUsers();

        if ($usersNotify) {
            /** @var Mailer $mailer */
            $mailer = Yii::$app->mailer;
            /** @var Message[] $messages */
            $messages = [];
            $templateMessage = $hostSender->{$this->messageAttribute};

            $mailFrom = isset(Yii::$app->params['email'])?Yii::$app->params['email']:'noreply@admkrsk.ru';

            foreach ($usersNotify as $user) {
                $this->recordMessage($user, $templateMessage);
                try{
                    $messages[] = $mailer->compose(
                        ['html' => 'notifyUpdate-html'],
                        [
                            'message' => $templateMessage,
                        ]
                    )
                    ->setFrom('ssp@admkrsk.ru')
                    ->setTo($user->email)
                    ->setSubject("Произошло изменение данных ".$senderName)
                    ->send();
                } catch (\Exception $e) {
                    //echo($e->getMessage());
                    continue;
                } 
            }

            /*
            try{
                $mailer->sendMultiple($messages);
            } catch (\Exception $e) {
                //
            } 
            */
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