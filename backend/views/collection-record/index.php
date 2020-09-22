<?php

use backend\models\forms\InstitutionUpdateSettingForm;
use common\jobs\InstitutionImportJob;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var InstitutionUpdateSettingForm $settingForm */
/* @var $model common\models\Collection */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['collection/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['index', 'id' => $model->id_collection]];

$this->params['button-block'][] = Html::a('Добавить', ['create', 'id' => $model->id_collection], ['class' => 'btn btn-success create-collection','data-toggle'=>"modal",'data-target'=>"#CollectionRecord"]);

$this->params['action-block'][] = Html::a('Редактировать', ['collection/update', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Удалить', ['collection/delete', 'id' => $model->id_collection],[
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
$this->params['action-block'][] = Html::a('История', ['collection/history', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Создать представление', ['collection/create-view', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Создать копию', ['/collection/copy', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Связать данные', ['/collection/assign', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Конвертировать данные', ['/collection/convert-type', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Импортировать данные', ['/collection/import', 'id' => $model->id_collection]);

/*$archive = Yii::$app->request->get('archive');

$this->params['action-block'][] = Html::a('Удаленные записи', ['index', 'id' => $page->id_collection, 'archive' => 1]);
}*/

if (!empty($model->form->template))
  $this->params['action-block'][] = Html::a('Скачать архив документов', ['/collection-record/all-doc', 'id' => $model->id_collection]);

if ($model->alias == 'institution')
{
    $jobId = InstitutionImportJob::getJobId();

    if (!$jobId || (!Yii::$app->queue->isWaiting($jobId) && !Yii::$app->queue->isReserved($jobId) && Yii::$app->queue->isDone($jobId))) {
        $this->params['button-block'][] = Html::a('Обновить организации', ['institution-import'],
            ['class' => 'btn btn-primary']);
    } else {
        $this->params['button-block'][] = Html::tag('span', 'Идет обновление..', ['class' => 'btn btn-warning']);
    }
}
?>
<?php if ($model->alias == 'institution'): ?>
    <?= $this->render('_form_institution_setting', ['settingForm' => $settingForm])?>
<?php endif; ?>

<?= $this->render('_form_select',['dataProvider'=>$dataProvider])?>

<div class="tabs-container">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active">
            <?=Html::a('Данные', ['collection-record/index', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Колонки', ['collection-column/index', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Главная форма', ['form/view', 'id' => $model->id_form], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Формы', ['form/collection', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Страницы', ['collection/pages', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active">
        <div class="panel-body">
          <div class="table-responsive">
              <?php yii\widgets\Pjax::begin([
                'id' => 'collection_grid',
                'enablePushState' => false,
                'scrollTo' => '#collection_grid',
                'timeout'=>5000,
              ]) ?>

              <?= GridView::widget([
                  'filterModel' => $searchModel,
                  //'filterSelector'=>'123',
                  'dataProvider' => $dataProvider,
                  'columns'=>$columns,
                  'tableOptions'=>[
                      'emptyCell '=>'',
                      'class'=>'table table-striped valign-middle table-hover ids-style'
                  ]
              ]); ?>
              <?php Pjax::end(); ?>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="CollectionRecord" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
