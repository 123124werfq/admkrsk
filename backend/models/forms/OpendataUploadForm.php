<?php

namespace backend\models\forms;

use common\models\OpendataData;
use common\models\OpendataStructure;
use Yii;
use yii\base\Model;

class OpendataUploadForm extends Model
{
    public $id_opendata;
    public $structure;
    public $data;

    public function rules()
    {
        return [
            [['structure'], 'file', 'extensions' => ['csv', 'xml', 'json'], 'checkExtensionByMimeType' => false],
            [['data'], 'file', 'skipOnEmpty' => false, 'extensions' => ['csv', 'xml', 'json'], 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'structure' => 'Структура',
            'data' => 'Данные',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $structure = new OpendataStructure([
                'id_opendata' => $this->id_opendata,
                'signature' => serialize(['upload' => Yii::$app->security->generateRandomString(32)]),
            ]);

            if ($structure->save()) {
                $data = new OpendataData([
                    'id_opendata_structure' => $structure->id_opendata_structure,
                    'is_manual' => true,
                ]);

                if ($data->save()) {
                    $data->structure->opendata->exportMeta();

                    if ($this->structure) {
                        $stream = fopen($this->structure->tempName, 'r+');
                        Yii::$app->publicStorage->writeStream($structure->path, $stream);
                        fclose($stream);
                    }

                    $stream = fopen($this->data->tempName, 'r+');
                    Yii::$app->publicStorage->writeStream($data->path, $stream);
                    fclose($stream);
                }
            }

            return true;
        } else {
            return false;
        }
    }
}
