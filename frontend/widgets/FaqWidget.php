<?php

namespace frontend\widgets;

use common\models\Faq;
use common\models\FaqCategory;
use Yii;
use yii\base\Widget;

class FaqWidget extends Widget
{
    public $id_faq_category;
    public $attributes;
    public $page;
    public $objectData; // данные CollectionRecord объекста если идет его рендер

    public function run()
    {
        if (!empty($this->attributes['id_faq_category']))
            $this->id_faq_category = (int)$this->attributes['id_faq_category'];

        if (($category = FaqCategory::findOne($this->id_faq_category)) === null)
        {
            return false;
        }

        $query = Faq::find()
            ->alias('f')
            ->joinWith('categories c')
            ->andFilterWhere(['c.id_faq_category' => $category->id_faq_category])
            ->groupBy(['f.id_faq']);

        Yii::$app->view->registerJs("$('.b-quotes_item .b-quotes_header').click(function () {
            var block = $(this).parent();
            block.toggleClass('active');
            block.find('.b-quotes_text').slideToggle();
        })");

        return $this->render('faq',[
            'category' => $category,
            'faqs' => $query->all(),
        ]);
    }
}
