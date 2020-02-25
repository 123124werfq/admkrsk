<?php

namespace backend\controllers;

use common\models\Action;
use common\models\Country;
use Yii;
use common\models\House;
use backend\models\search\HouseSearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use common\models\City;
use common\models\District;
use common\models\Region;
use common\models\Street;
use common\models\Subregion;

/**
 * AddressController implements the CRUD actions for House model.
 */
class AddressController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'history' => [
                'class' => 'backend\modules\log\actions\IndexAction',
                'modelClass' => House::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => House::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => House::class,
            ],
        ];
    }

    /**
     * Lists all House models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HouseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single House model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new House model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new House();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!$model->lat || !$model->lon) {
                if (!$model->sputnik_updated_at) {
                    $model->updateLocation();
                }
            }
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_house]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing House model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!$model->lat || !$model->lon) {
                if (!$model->sputnik_updated_at) {
                    $model->updateLocation();
                }
            }
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_house]);
        }

        if (!$model->lat || !$model->lon) {
            if (!$model->sputnik_updated_at) {
                $model->updateLocation();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing House model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            $model->createAction(Action::ACTION_DELETE);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUndelete($id)
    {
        $model = $this->findModel($id);

        if ($model->restore()) {
            $model->createAction(Action::ACTION_UNDELETE);
        }

        return $this->redirect(['index', 'archive' => 1]);
    }

    /**
     * Finds the House model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return House the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = House::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param string $search
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionCountry($search = '')
    {
        $query = Country::find()
            ->select(['id_country', 'name'])
            ->asArray();

        if ($search) {
            $query->filterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $country) {
            $results[] = [
                'id' => $country['id_country'],
                'text' => $country['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_country
     * @param int $is_active
     * @param string $search
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionRegion($id_country = null, $is_active = 1, $search = '')
    {
        $query = Region::find()
            ->select(['id_region', 'name'])
            ->groupBy('id_region')
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

        if (!empty($id_country)) {
            $query->filterWhere(['id_country' => $id_country]);
        }

        if ($is_active) {
            $query->andFilterWhere(['is_active' => $is_active]);
        }

        if ($search) {
            $query->filterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $region) {
            $results[] = [
                'id' => $region['id_region'],
                'text' => $region['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_region
     * @param int $is_active
     * @param string $search
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionSubregion($id_region = null, $is_active = 1, $search = '')
    {
        $query = Subregion::find()
            ->select(['id_subregion', 'name'])
            ->groupBy('id_subregion')
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

        if (!empty($id_region)) {
            $query->filterWhere(['id_region' => $id_region]);
        }

        if ($is_active) {
            $query->andFilterWhere(['is_active' => $is_active]);
        }

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $subregion) {
            $results[] = [
                'id' => $subregion['id_subregion'],
                'text' => $subregion['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_region
     * @param int $id_subregion
     * @param int $is_active
     * @param string $search
     * @return Response
     * @throws BadRequestHttpException
     * @throws InvalidConfigException
     */
    public function actionCity($id_region = null, $id_subregion = null, $is_active = 1, $search = '')
    {
        if (!$id_region && !$id_subregion) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => 'id_region or id_subregion',
            ]));
        }

        $query = City::find()
            ->select(['id_city', 'name'])
            ->filterWhere([
                'id_region' => $id_region,
                'id_subregion' => $id_subregion,
            ])
            ->groupBy('id_city')
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

        if ($is_active) {
            $query->andFilterWhere(['is_active' => $is_active]);
        }

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $city) {
            $results[] = [
                'id' => $city['id_city'],
                'text' => $city['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_city
     * @param int $is_active
     * @param string $search
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionDistrict($id_city = null, $is_active = 1, $search = '')
    {
        $query = District::find()
            ->select(['id_district', 'name']);

        if (!empty($id_city)) {
            $query->filterWhere(['id_city' => $id_city]);
        }

        $query->groupBy('id_district')
            ->orderBy(['name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($is_active) {
            $query->andFilterWhere(['is_active' => $is_active]);
        }

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $district) {
            $results[] = [
                'id' => $district['id_district'],
                'text' => $district['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_city
     * @param $id_district
     * @param int $is_active
     * @param string $search
     * @return Response
     * @throws BadRequestHttpException
     * @throws InvalidConfigException
     */
    public function actionStreet($id_city = null, $id_district = null, $is_active = 1, $search = '')
    {
        if (!$id_city && !$id_district) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => 'id_city or id_district',
            ]));
        }

        $query = Street::find()
            ->select(['map_street.id_street', 'map_street.name'])
            ->joinWith('districts', false)
            ->filterWhere([Street::tableName() . '.id_city' => $id_city])
            ->filterWhere([
                Street::tableName() . '.id_city' => $id_city,
                District::tableName() . '.id_district' => $id_district,
            ])
            ->groupBy(Street::tableName() . '.id_street')
            ->orderBy([Street::tableName() . '.name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($is_active) {
            $query->andFilterWhere([Street::tableName() . '.is_active' => $is_active]);
        }

        if ($search) {
            $query->andFilterWhere(['ilike', Street::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $street) {
            $results[] = [
                'id' => $street['id_street'],
                'text' => $street['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_street
     * @param int $is_active
     * @param string $search
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionHouse($id_street, $is_active = 1, $search = '')
    {
        if (empty($id_street)) {
            return $this->asJson(['results' => []]);
        };

        $query = House::find()
            ->select(['id_house', 'name', 'postalcode'])
            ->filterWhere(['id_street' => $id_street])
            ->groupBy('id_house')
            ->orderBy(['name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($is_active) {
            $query->andFilterWhere(['is_active' => $is_active]);
        }

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $house) {
            $results[] = [
                'id' => $house['id_house'],
                'text' => $house['name'],
                'postalcode' => $house['postalcode'],
            ];
        }

        if (empty($results)) {
            $results = [
                'id' => null,
                'text' => $search,
                'postalcode' => ''
            ];
        }

        return $this->asJson(['results' => $results]);
    }
}
