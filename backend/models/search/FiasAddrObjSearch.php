<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FiasAddrObj;

/**
 * AddressSearch represents the model behind the search form of `common\models\Address`.
 */
class FiasAddrObjSearch extends FiasAddrObj
{
    public $addressname;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['areacode', 'autocode', 'citycode', 'code', 'enddate', 'formalname', 'ifnsfl', 'ifnsul', 'offname', 'okato', 'oktmo', 'placecode', 'plaincode', 'postalcode', 'regioncode', 'shortname', 'startdate', 'streetcode', 'terrifnsfl', 'terrifnsul', 'updatedate', 'ctarcode', 'extrcode', 'sextcode', 'plancode', 'cadnum', 'aoguid', 'aoid', 'nextid', 'normdoc', 'parentguid', 'previd', 'addressname'], 'safe'],
            [['divtype'], 'number'],
            [['actstatus', 'aolevel', 'centstatus', 'currstatus', 'livestatus', 'operstatus'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = FiasAddrObj::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['formalname' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'enddate' => $this->enddate,
            'startdate' => $this->startdate,
            'updatedate' => $this->updatedate,
            'divtype' => $this->divtype,
            'actstatus' => $this->actstatus,
            'aolevel' => $this->aolevel,
            'centstatus' => $this->centstatus,
            'currstatus' => $this->currstatus,
            'livestatus' => $this->livestatus,
            'operstatus' => $this->operstatus,
            'aoguid' => $this->aoguid,
            'aoid' => $this->aoid,
            'nextid' => $this->nextid,
            'normdoc' => $this->normdoc,
            'parentguid' => $this->parentguid,
            'previd' => $this->previd,
        ]);

        $query->andFilterWhere(['ilike', 'areacode', $this->areacode])
            ->andFilterWhere(['ilike', 'autocode', $this->autocode])
            ->andFilterWhere(['ilike', 'citycode', $this->citycode])
            ->andFilterWhere(['ilike', 'code', $this->code])
            ->andFilterWhere(['ilike', 'formalname', $this->formalname])
            ->andFilterWhere(['ilike', 'ifnsfl', $this->ifnsfl])
            ->andFilterWhere(['ilike', 'ifnsul', $this->ifnsul])
            ->andFilterWhere(['ilike', 'offname', $this->offname])
            ->andFilterWhere(['ilike', 'okato', $this->okato])
            ->andFilterWhere(['ilike', 'oktmo', $this->oktmo])
            ->andFilterWhere(['ilike', 'placecode', $this->placecode])
            ->andFilterWhere(['ilike', 'plaincode', $this->plaincode])
            ->andFilterWhere(['ilike', 'postalcode', $this->postalcode])
            ->andFilterWhere(['ilike', 'regioncode', $this->regioncode])
            ->andFilterWhere(['ilike', 'shortname', $this->shortname])
            ->andFilterWhere(['ilike', 'streetcode', $this->streetcode])
            ->andFilterWhere(['ilike', 'terrifnsfl', $this->terrifnsfl])
            ->andFilterWhere(['ilike', 'terrifnsul', $this->terrifnsul])
            ->andFilterWhere(['ilike', 'ctarcode', $this->ctarcode])
            ->andFilterWhere(['ilike', 'extrcode', $this->extrcode])
            ->andFilterWhere(['ilike', 'sextcode', $this->sextcode])
            ->andFilterWhere(['ilike', 'plancode', $this->plancode])
            ->andFilterWhere(['ilike', 'cadnum', $this->cadnum]);

        if ($this->addressname) {
            $query->andFilterWhere(['ilike', 'formalname', $this->addressname]);
        }

        return $dataProvider;
    }
}
