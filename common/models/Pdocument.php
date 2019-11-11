<?php

namespace common\models;

use Yii;
use DOMDocument;
use SimpleXMLElement;

/**
 * This is the model class for table "db_pdocument".
 *
 * @property int $id_pdocument
 * @property string $id_message
 * @property string $sender_code
 * @property string $sender_name
 * @property string $recipient_code
 * @property string $recipient_name
 * @property string $originator_code
 * @property string $originator_name
 * @property string $case_number
 * @property string $service_code
 * @property string $type
 * @property string $regnum
 * @property string $regdep
 * @property string $regdate
 * @property string $subject
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Pdocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_pdocument';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject'], 'string'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['id_message', 'sender_code', 'sender_name', 'recipient_code', 'recipient_name', 'originator_code', 'originator_name', 'case_number', 'service_code', 'type', 'regnum', 'regdep', 'regdate'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pdocument' => 'Id Pdocument',
            'id_message' => 'Id Message',
            'sender_code' => 'Sender Code',
            'sender_name' => 'Sender Name',
            'recipient_code' => 'Recipient Code',
            'recipient_name' => 'Recipient Name',
            'originator_code' => 'Originator Code',
            'originator_name' => 'Originator Name',
            'case_number' => 'Case Number',
            'service_code' => 'Service Code',
            'type' => 'Type',
            'regnum' => 'Regnum',
            'regdep' => 'Regdep',
            'regdate' => 'Regdate',
            'subject' => 'Subject',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }


    public function getLinks()
    {
        return $this->hasMany(PdocumentLink::class, ['id_message' => 'id_message'])
            ->orderBy('created_at');
    }

    public function getFiles()
    {
        return $this->hasMany(PdocumentFile::class, ['id_message' => 'id_message'])
            ->orderBy('created_at');
    }

    static protected function xmlToArray($xml)
    {
        $res = false;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $xml = new SimpleXMLElement($response);
        $body = $xml->xpath('//SBody')[0];
        $res = json_decode(json_encode((array)$body), TRUE);
        return $res;
    }

    static protected function with($object)
    {
        return $object;
    }


    static public function parseAndSave($xml, $fileinfo = null, $allowdupes = false)
    {
        $docarr = self::xmlToArray($xml);

        if(!$docarr) return false;

//        print_r($docarr['intInput_Document']['revMessageData']['revAppData']); die();

        if(!$allowdupes)
        {
            $dd = Pdocument::find()->where(['case_number' => $docarr['intInput_Document']['revMessage']['revCaseNumber'] ])->count();
            if($dd>0)
                return false;
        }


        $doc = new Pdocument();

        $publishSPA = (self::with($docarr['intInput_Document']['revMessageData']['revAppData']['PublishPA']));
        $doc->id_message = $publishSPA['ID'];
        $doc->type = $publishSPA['Type'];
        $doc->regnum = $publishSPA['regNum'];
        $doc->regdep = $publishSPA['regDep'];
        $doc->regdate = $publishSPA['regDate'];
        $doc->subject = $publishSPA['Subject'];

        $revMessage = (self::with($docarr['intInput_Document']['revMessage']));
        $doc->sender_code = $revMessage['revSender']['revCode'];
        $doc->sender_name = $revMessage['revSender']['revName'];
        $doc->recipient_code = $revMessage['revRecipient']['revCode'];
        $doc->recipient_name = $revMessage['revRecipient']['revName'];
        $doc->originator_code = $revMessage['revOriginator']['revCode'];
        $doc->originator_name = $revMessage['revOriginator']['revName'];
        $doc->case_number = $revMessage['revCaseNumber'];
        $doc->service_code = $revMessage['revServiceCode'];

        if(!$doc->save())
            return false;

        foreach($publishSPA['linkedPA'] as $link)
        {
            if(!$allowdupes)
            {
                $dl = PdocumentLink::find()->where(['id_link' => $link['ID'] ])->count();
                if($dd>0)
                    continue;
            }

            $lnk = new PdocumentLink();
            $lnk->id_pdocument = $doc->id_pdocument;
            $lnk->id_message = $doc->id_message;
            $lnk->id_link = $link['ID'];
            $lnk->type = $link['Type'];
            $lnk->regnum = $link['regNum'];
            $lnk->regdate = $link['regDate'];
            $lnk->subject = $link['Subject'];
            $lnk->linkname = $link['linkName'];
            $link->save();
        }


        return $doc->id_pdocument;
    }


}
