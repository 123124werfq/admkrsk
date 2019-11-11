<?php use yii\helpers\Html;?>
<div class="ibox block" data-id="<?=$data->id_block?>">
    <input type="hidden" name="ord[<?=$data->id_block?>]" value="<?=$data->ord?>"/>
	<div class="ibox-title">
        <h5><?=$data->type?></small></h5>
        <div class="ibox-tools">
            <!--a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a-->
            <a href="/block/update?id=<?=$data->id_block?>">
                <i class="fa fa-wrench"></i>
            </a>
            <!--ul class="dropdown-menu dropdown-user">
                <li><a href="#" class="dropdown-item">Config option 1</a>
                </li>
                <li><a href="#" class="dropdown-item">Config option 2</a>
                </li>
            </ul-->
            <?= Html::a('<i class="fa fa-times"></i>', ['block/delete', 'id' => $data->id_block], [
                'class' => 'close-link',
                'data' => [
                    'confirm' => 'Вы уверены?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
	<div class="ibox-content text-center">
		<?=$data->getName()?>
	</div>
</div>