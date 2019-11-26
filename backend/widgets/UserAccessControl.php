<?php

namespace backend\widgets;

use common\models\AuthEntity;
use common\models\User;
use kartik\select2\Select2;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @property ActiveRecord $model
 */
class UserAccessControl extends Select2
{
    public function init()
    {
        parent::init();

        $this->options['multiple'] = true;

        $this->pluginOptions = [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => Url::toRoute(['/user/list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
        ];

        $data = ArrayHelper::map(User::find()->where([
            'id' => AuthEntity::find()->select('id_user')->where([
                'class' => get_class($this->model),
                'entity_id' => $this->model->primaryKey,
            ]),
        ])->all(), 'id', function (User $model) {
            return $model->getUsername() . ' (' . $model->email . ')';
        });

        $this->model->{$this->attribute} = array_keys($data);
        $this->data = $data;
    }
}
