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
	<div class="col-sm-5">
		<div class="ibox">
			<div class="ibox-content">
				<div class="form-group">					
					<input class="form-control" id="treesearch" type="text" placeholder="Поиск"/>					
				</div>
				<div id="tree"></div>
			</div>
		</div>
	</div>	
	<div id="treeView" class="col-sm-7">
	</div>
</div>
<?php /*foreach ($tree[0] as $key => $data) {
	echo $this->render('_tree',['data'=>$data,'tree'=>$tree,'offset'=>0]);
}*/
$tree = json_encode($tree);
$script = <<< JS
$('#tree').jstree({
	'core' : {
    	'data' : $tree,
	}, 
	"plugins" : [
   	 	"search",
  	],
	"search": {
		show_only_matches:true,
	}
});

var to = false;
//$('#treesearch').submit(function () {
$('#treesearch').keyup(function (){
	if (to) { clearTimeout(to); }
	to = setTimeout(function () {
		var v = $('#treesearch').val();
		$('#tree').jstree(true).search(v);
		
	}, 500);
});

$('#tree').on("select_node.jstree", function (e, data) { openNode(data.node.id); });

JS;

$this->registerJs($script, View::POS_END);

?>