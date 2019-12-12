<?php

namespace frontend\modules\api\models;

use Yii;

class News extends \common\models\News
{
    public function fields()
    {
        return [
            'id_news',
            'id_page',
            'id_category',
            'id_rub',
            'media' => function (News $model) {
                return $model->media->url ?? null;
            },
            'title',
            'description',
            'content',
            'date_publish' => function (News $model) {
                return $model->date_publish ? Yii::$app->formatter->asDatetime($model->date_publish) : null;
            },
            'date_unpublish' => function (News $model) {
                return $model->date_unpublish ? Yii::$app->formatter->asDatetime($model->date_unpublish) : null;
            },
            'state',
            'main',
            'created_at' => function (News $model) {
                return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : null;
            },
            'updated_at' => function (News $model) {
                return $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : null;
            },
            'id_user_contact',
            'id_user',
            'id_record_contact',
            'highlight',
            'url' => function (News $model) {
                return $model->fullUrl;
            },
        ];
    }
}
