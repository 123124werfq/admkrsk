<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "search_sitemap".
 *
 * @property int $id_sitemap
 * @property int $url
 * @property string $content
 * @property string $content_date
 * @property int $active
 * @property int $modified_at
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class SearchSitemap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'search_sitemap';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url', 'active', 'modified_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [[ 'active', 'modified_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['url', 'content', 'tsvector', 'header'], 'string'],
            [['content_date'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sitemap' => 'Id Sitemap',
            'url' => 'Url',
            'content' => 'Content',
            'content_date' => 'Content Date',
            'active' => 'Active',
            'modified_at' => 'Modified At',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    static public function fulltext($query, $bydate = false)
    {
        $query = strip_tags(addslashes($query));

        if($bydate)
            $sql = 'SELECT * FROM
                    (
                    SELECT id_sitemap, header, url, content_date, ts_rank(to_tsvector("content"), plainto_tsquery(\''.$query.'\'))  as rank, ts_headline(content, keywords) as headline
                    FROM search_sitemap, plainto_tsquery(\''.$query.'\') as keywords
                    WHERE to_tsvector("content") @@ plainto_tsquery(\''.$query.'\')
                    ORDER BY ts_rank(to_tsvector("content"), plainto_tsquery(\''.$query.'\')) DESC) t1
                    WHERE rank>0.01';
        else
            $sql = 'SELECT * FROM(
                        SELECT id_sitemap, header, url, content_date, ts_rank(to_tsvector("content"), plainto_tsquery(\''.$query.'\')) as rank, ts_headline(content, keywords) as headline
                        FROM search_sitemap, plainto_tsquery(\''.$query.'\') as keywords
                        WHERE to_tsvector("content") @@ plainto_tsquery(\''.$query.'\')
                        ORDER BY content_date DESC, ts_rank(to_tsvector("content"), plainto_tsquery(\''.$query.'\')) DESC) t1
                    WHERE rank>0.01';

        //var_dump($sql);die();

        $result = Yii::$app->db->createCommand($sql)->queryAll();

        return $result;

    }

}
