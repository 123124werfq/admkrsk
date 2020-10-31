<?php

use yii\web\View;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дерево разделов';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.page'))
    $this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);
?>

<div class="row">
	<div class="col-sm-6">
		<div class="ibox">
			<div class="ibox-content">
				<div id="tree"></div>
			</div>
		</div>
	</div>
</div>
<?php /*foreach ($tree[0] as $key => $data) {
	echo $this->render('_tree',['data'=>$data,'tree'=>$tree,'offset'=>0]);
}*/
$tree = json_encode($tree);
$script = <<< JS
$('#tree').jstree({ 'core' : {
    'data' : $tree
} });

JS;

$this->registerJs($script, View::POS_END);

?>