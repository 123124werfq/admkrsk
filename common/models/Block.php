<?php

namespace common\models;

use common\traits\ActionTrait;
use Yii;

/**
 * This is the model class for table "db_block".
 *
 * @property int $id_block
 * @property int $id_page
 * @property string $widget
 * @property string $code
 * @property int $state
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Block extends \yii\db\ActiveRecord
{
    use ActionTrait;

    public $blocks = [
        'header'=> [
            'label'=>'Шапка сайта',
            'vars'=>[
                'menu'=>[
                    'name'=>'Меню',
                    'type'=>BlockVar::TYPE_MENU,
                ],
                'dropdown_menu'=>[
                    'name'=>'Разворачивающееся меню',
                    'type'=>BlockVar::TYPE_MENU,
                ],
            ],
            'layout'=>true,
        ],
        'footer'=> [
            'label'=>'Подвал сайта',
            'vars'=>[
                'menu'=>[
                    'name'=>'Меню',
                    'type'=>BlockVar::TYPE_MENU,
                ],
            ],
            'layout'=>true,
        ],
        'news'=> [
            'label'=>'Новостной блок',
            'widget'=>'frontend\widgets\NewsWidget',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'menu'=>[
                    'name'=>'Меню в табах',
                    'type'=>BlockVar::TYPE_MENU,
                ],
            ]
        ],
        'news_single'=> [
            'label'=>'Новостной блок, без меню',
            'widget'=>'frontend\widgets\NewsWidget',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'button_text'=>[
                    'name'=>'Подпись кнопки',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'button_color'=>[
                    'name'=>'Цвет кнопки',
                    'type'=>BlockVar::TYPE_SELECT,
                    'values'=>[
                        ''=>'Белая',
                        'btn__primary'=>'Золотой'
                    ]
                ],
                'background'=>[
                    'name'=>'Фон',
                    'type'=>BlockVar::TYPE_SELECT,
                    'values'=>[
                        ''=>'Серый',
                        'press_invert'=>'Белый'
                    ]
                ],
                'id_page'=>[
                    'name'=>'Раздел новостей',
                    'type'=>BlockVar::TYPE_PAGE,
                ],
            ]
        ],
        'photoflip'=> [
            'label'=>'Гид по городу',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'menu'=>[
                    'name'=>'Меню',
                    'type'=>BlockVar::TYPE_MENU,
                ],
                'medias'=>[
                    'name'=>'Галерея',
                    'type'=>BlockVar::TYPE_MEDIAS,
                ]
            ]
        ],
        'poll'=> [
            'label'=>'Голосование',
            'widget'=> 'frontend\widgets\PollWidget',
            'vars'=>[
                'menu'=>[
                    'name'=>'Меню справо',
                    'type'=>BlockVar::TYPE_MENU,
                ],
                'id_poll_question'=>[
                    'name'=>'Вопрос для вывода',
                    'type'=>BlockVar::TYPE_QUESTION,
                ],
            ]
        ],
        'video_section'=> [
            'label'=>'Видео блок',
            //'widget'=> 'frontend\widgets\ServiceSearchWidget',
            'vars'=>[
                'name'=>[
                    'name'=>'Название',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'cover'=>[
                    'name'=>'Обложка',
                    'type'=>BlockVar::TYPE_MEDIA,
                ],
                'youtube'=>[
                    'name'=>'Ссылка Youtube',
                    'type'=>BlockVar::TYPE_STRING,
                ],
            ]
        ],
        'event_blockquote'=> [
            'label'=>'Цитата',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'content'=>[
                    'name'=>'Описание',
                    'type'=>BlockVar::TYPE_RICHTEXT,
                ],
                'autor'=>[
                    'name'=>'Автор',
                    'type'=>BlockVar::TYPE_COLLECTION_RECORD,
                    'alias'=>'press_people',
                    'multiple'=>'multiple',
                ],
            ]
        ],
        'event_body'=> [
            'label'=>'Вывод события на всю ширину',
            'vars'=>[
                'collection'=>[
                    'name'=>'Список',
                    'type'=>BlockVar::TYPE_COLLECTION,
                ],
            ]
        ],
        'event_main'=> [
            'label'=>'Шапка события',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'cover'=>[
                    'name'=>'Обложка',
                    'type'=>BlockVar::TYPE_MEDIAS,
                ],
                'cover_mobile'=>[
                    'name'=>'Обложка мобильная',
                    'type'=>BlockVar::TYPE_MEDIAS,
                ],
                'content'=>[
                    'name'=>'Описание',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'countdown_title'=>[
                    'name'=>'Заголовок обратного отсчета',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'countdown'=>[
                    'name'=>'Дата начала',
                    'type'=>BlockVar::TYPE_DATE,
                ],
                'programm'=>[
                    'name'=>'Программа мероприятия',
                    'type'=>BlockVar::TYPE_COLLECTION,
                ],
                'id_page'=>[
                    'name'=>'Раздел для программ мероприятий',
                    'type'=>BlockVar::TYPE_PAGE,
                ],
                'background'=>[
                    'name'=>'Подложка под текст',
                    'type'=>BlockVar::TYPE_CHECKBOX,
                ],
            ]
        ],
        'service_search'=> [
            'label'=>'Поиск муниципальных услуг',
            'widget'=> 'frontend\widgets\ServiceSearchWidget',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'services'=>[
                    'name'=>'Слайды сервисов',
                    'type'=>BlockVar::TYPE_MENU,
                ],
            ]
        ],
        'service_menu'=> [
            'label'=>'Жизненные ситуации',
            'widget'=> 'frontend\widgets\ServiceSituationWidget',
            'vars'=>[
                'bytype'=>[
                    'name'=>'Разделение на Юр/Физ лица',
                    'type'=>BlockVar::TYPE_CHECKBOX,
                ],
            ]
        ],
        'partners'=> [
            'label'=>'Партнеры',
            'widget'=>'frontend\widgets\MenuWidget',
            'vars'=>[
                'menu'=>[
                    'name'=>'Партнеры',
                    'type'=>BlockVar::TYPE_MENU,
                ],
                'template'=>[
                    'name'=>'Шаблон',
                    'type'=>BlockVar::TYPE_SELECT,
                    'values'=>[
                        'partners'=>'Как блок на странице',
                        'partners_full'=>'На всю страницу'
                    ]
                ],
            ]
        ],
        'grid'=> [
            'label'=>'Сетка ссылок',
            'widget'=>'frontend\widgets\MenuWidget',
            'vars'=>[
                'menu'=>[
                    'name'=>'Меню',
                    'type'=>BlockVar::TYPE_MENU,
                ],
                'template'=>[
                    'name'=>'Шаблон',
                    'type'=>BlockVar::TYPE_HIDDEN,
                    'value'=>'grid'
                ],
            ]
        ],
        'tabs'=> [
            'label'=>'Белый блок с табами',
            'widget'=>'frontend\widgets\MenuWidget',
            'vars'=>[
                'menu'=>[
                    'name'=>'Меню',
                    'type'=>BlockVar::TYPE_MENU,
                ],
            ]
        ],
        'events'=> [
            'label'=>'Городские проекты и события',
            'widget'=>'frontend\widgets\ProjectWidget',
            'vars'=>[
                'title'=>[
                    'name'=>'Заголовок',
                    'type'=>BlockVar::TYPE_STRING,
                ],
                'id_page'=>[
                    'name'=>'Раздел',
                    'type'=>BlockVar::TYPE_PAGE,
                ],
            ]
        ],
        'people_grid'=> [
            'label'=>'Сетка с контактами',
            'vars'=>[
                'peoples'=>[
                    'name'=>'Люди',
                    'type'=>BlockVar::TYPE_COLLECTION_RECORD,
                    'alias'=>'press_people_en',
                    'multiple'=>'multiple',
                ],
            ]
        ],
        'map'=> [
            'label'=>'Несколько списков на карте ',
            'widget'=>'frontend\widgets\MapsWidget',
            'vars'=>[
                'collections'=>[
                    'name'=>'Списки',
                    'type'=>BlockVar::TYPE_COLLECTIONS,
                ],
            ]
        ],
        'content'=> [
            'label'=>'Содержение',
        ],
        'html'=> [
            'label'=>'HTML блок',
            'vars'=>[
                'html'=>[
                    'name'=>'HTML',
                    'type'=>BlockVar::TYPE_RICHTEXT,
                ],
            ]
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_block';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page', 'id_page_layout', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['state'], 'default', 'value' => 1],
            [['id_page', 'id_page_layout', 'state', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],

            [['code','type'], 'string'],
            [['widget'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_block' => 'ID',
            'id_page' => 'Раздел',
            'widget' => 'Виджет',
            'code' => 'Код',
            'state' => 'Активен',
            'type'=>'Тип блока',
            'ord' => 'Позиция',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getName()
    {
        //return $this->type;
        return $this->blocks[$this->type]['label'];
    }

    public function getTypesLabels($layout=false)
    {
        $types = [];

        foreach ($this->blocks as $key => $block)
        {
            if ($layout && !empty($block['layout']))
                $types[$key] = $block['label'];
            elseif (!$layout && empty($block['layout']))
                $types[$key] = $block['label'];
        }

        return $types;
    }

    public function getWidget()
    {
        if (!empty($this->blocks[$this->type]['widget']))
            return $this->blocks[$this->type]['widget'];

        return false;
    }

    public function getVars()
    {
        $exist_vars = $this->getBlockVars()->indexBy('alias')->all();

        $vars = [];

        foreach ($this->blocks[$this->type]['vars'] as $key => $var)
        {
            if (!isset($exist_vars[$key]))
            {
                $newVar = new BlockVar;
                $newVar->id_block = $this->id_block;
                $newVar->alias = $key;
                $newVar->type = $var['type'];
                $newVar->name = $var['name'];

                if (isset($var['value']))
                    $newVar->value = $var['value'];

                $vars[$newVar->alias] = $newVar;
            }
            else
                $vars[$key] = $exist_vars[$key];
        }

        return $vars;
    }

    public function getBlockVars()
    {
        return $this->hasMany(BlockVar::className(), ['id_block' => 'id_block']);
    }

    public function getPage()
    {
        if (!empty($this->id_page))
            return $this->hasOne(Page::class, ['id_page' => 'id_page']);
        else
            return $this->hasOne(Page::class, ['id_page' => 'id_page_layout']);
    }

}
