<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class WorkflowForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;
    public $rawtext;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'maxFiles' => 10],
            ['rawtext', 'safe']
        ];
    }
}