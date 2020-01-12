<?php
    use yii\helpers\Html;
?>
<div class="ibox m-t">
    <div class="ibox-content">
       <div class="tabs-container">
            <ul class="nav nav-tabs" role="tablist">
                <?php
                    $i = 0;
                    foreach ($table as $sheetname => $sheet) {?>
                    <li class="<?=$i==0?'active':''?>"><a class="nav-link" data-toggle="tab" href="#tab-<?=$i?>"><?=$sheetname?></a></li>
                <?php $i++; }?>
            </ul>
            <div class="tab-content">
                <?php
                    $i = 0;
                    foreach ($table as $sheetname => $sheet) {?>
                    <div role="tabpanel" id="tab-<?=$i?>" class="tab-pane <?=$i==0?'active':''?>">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <?=Html::activeTextInput($model, "[$i]keyrow",['placeholder'=>'Ключи','class'=>'form-control import-collection-key','type'=>'number','min'=>0])?>
                                </div>
                                <div class="col-sm-3">
                                    <?=Html::activeTextInput($model, "[$i]skip",['placeholder'=>'Пропустить строк','class'=>'form-control import-collection-start','type'=>'number','min'=>0])?>
                                </div>
                                <div class="col-sm-3">
                                    <?=Html::activeCheckBox($model, "[$i]firstRowAsName")?>
                                </div>
                                <div class="col-sm-3">
                                    <?=Html::submitButton('Импортировать', ['class' => 'btn btn-primary','value'=>$sheetname,'name'=>'CollectionImportForm[sheet]']) ?>
                                </div>
                            </div>
                            <br/>
                            <div class="table-responsive">
                                <table class="table">
                                <?php foreach ($sheet as $rkey => $row) {?>
                                    <tr>
                                        <?php
                                            foreach ($row as $tkey => $td)
                                                echo "<td>".Html::encode($td)."</td>";
                                        ?>
                                    </tr>
                                <?php
                                    if ($rkey>6)
                                        break;
                                    }
                                ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php $i++; }?>
            </div>
        </div>
    </div>
</div>