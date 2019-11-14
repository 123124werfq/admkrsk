<?php

namespace common\models;

use common\behaviors\UserAccessControlBehavior;
use common\modules\log\behaviors\LogBehavior;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "cnt_vars".
 *
 * @property string $id_var
 * @property string $name
 * @property string $alias
 * @property string $content
 * @property array $access_user_ids
 */
class Vars extends \yii\db\ActiveRecord
{
    use MetaTrait;

    public $label = 'Переменная';
    public $labelPlural = 'Переменные';

    public $access_user_ids;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cnt_vars';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['content'], 'string'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],

            ['access_user_ids', 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'log' => LogBehavior::class,
            'ac' => [
                'class' => UserAccessControlBehavior::class,
                'permission' => 'backend.vars',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_var' => 'Id',
            'name' => 'Название',
            'alias' => 'Алиас',
            'content' => 'Содержание',
            'access_user_ids' => 'Доступ',
        ];
    }

    public static function getVar($alias)
    {
        $cache = Yii::$app->cache;

        $output = $cache->get("var_$alias");

        if (!empty($output))
            return $output;

        $var = Vars::find()->where([
            'alias'=>$alias,
        ])->one();

        if (empty($var))
        {
            $var = new Vars;
            $var->alias = $alias;
            $var->name = $alias;
            $var->content = '';
            $var->save();
        }

        $cache->add("var_$alias", $var->content, 365*24*3600);

        return $var->content;
    }
}
