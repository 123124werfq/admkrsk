<?php

namespace backend\widgets;

use common\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @property User $model
 */
class UserActiveDirectoryMapEsia extends Select2
{
    public function init()
    {
        parent::init();

        $this->options['multiple'] = true;

        $this->pluginOptions = [
            'allowClear' => true,
            'maximumSelectionLength' => 1,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => Url::toRoute(['/user/list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
        ];

        $userEsiaId = null;
        if ($this->model->id_esia_user) {
            $userEsia = User::findOne([
                'id_esia_user' => $this->model->id_esia_user,
                'id_ad_user' => null,
            ]);
            if ($userEsia) {
                $userEsiaId = $userEsia->id;
            }
        }

        $data = ArrayHelper::map(User::find()->where([
            'id' => $userEsiaId
        ])->all(), 'id', function (User $model) {
            return $model->getUsername() . ' (' . $model->email . ')';
        });

        $this->model->{$this->attribute} = array_keys($data);
        $this->data = $data;
    }
}