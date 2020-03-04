<?php foreach ($rows as $key => $row){?>
	<div class="form-row flex-row" data-id="<?=$row->id_row?>">
		<?php foreach ($row->elements as $ikey => $element) {?>
		<?php
            if (!empty($element->input))
                echo $this->render('_input',['element'=>$element]);
            else if (!empty($element->subForm))
                echo $this->render('_subform',['element'=>$element,'form'=>$form]);
            else
                echo $this->render('_element',['element'=>$element]);
    ?>
		<?php }?>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            ...
        </button>
        <ul class="dropdown-menu pull-right">
          <?php if ($form->isMainForm()){?>
          <li><a href="/form-input/create?id_row=<?=$row->id_row?>" class="create-form-input">Добавить поле</a></li>
          <?php }else {?>
          <li><a href="/form-input/assign?id_row=<?=$row->id_row?>" class="create-form-input">Привязать поле</a></li>
          <?php }?>
          <li><a href="/form-element/create?id_row=<?=$row->id_row?>" class="create-element">Добавить текст</a></li>
          <li><a href="/form/update-row?id_row=<?=$row->id_row?>" class="update-row">Редактировать стили</a></li>
          <?php if (count($row->elements)==0){?>
            <li><a href="/form/delete-row?id_row=<?=$row->id_row?>" class="delete-row" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post">Удалить строку</a></li>
          <?php }?>
          <?php if ($form->isMainForm()){?>
            <li><a href="/form/assign-form?id_row=<?=$row->id_row?>" class="create-subform">Добавить подформу</a></li>
          <?php }?>
        </ul>
    </div>
	</div>
<?php }?>