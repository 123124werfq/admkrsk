<?php

namespace backend\models\forms;

use common\models\Page;
use Yii;
use yii\base\Model;

class CopyPageForm extends Model
{
    public $id_page;
    public $id_parent;

    public function rules()
    {
        return [
            [['id_page', 'id_parent'], 'integer'],
            [['id_page'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['id_page' => 'id_page']],
            [['id_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['id_parent' => 'id_page']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_page' => 'Копируемый раздел',
            'id_parent' => 'Новая родительская страница',
        ];
    }

    public function cloneNode()
    {
        $exclude = null;
        $copy = Page::findOne($this->id_page);
        $appendTo = Page::findOne($this->id_parent);

        return $this->copyPage($copy, $appendTo, $exclude);
    }

    public function copyPage(Page $copy, Page $appendTo, &$exclude)
    {
        $model = new Page($copy->getAttributes(null, [
            'id_page',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ]));
        $model->id_parent = $appendTo->id_page;
        $model->alias .= '_copy';
        $model->appendTo($appendTo);

        $exclude[] = $model->id_page;

        $copy->refresh();
        foreach ($copy->children(1)->andFilterWhere(['not', ['id_page' => $exclude]])->all() as $child) {
            $this->copyPage($child, $model, $exclude);
        }

        return $model;
    }
}
