<div class="ibox hide selectActionForm">
	<div class="ibox-content text-center">
		<form action="">
			Вы выбрали <b id="selectCount">0</b> из <b id="selectTotal"><?=$dataProvider->getTotalCount()?></b>
      &nbsp;
			<div class="dropdown selectActionDropDown">
        <button class="btn btn-default dropdown-toggle" type="button" id="selectActionDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          Действие
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="selectActionDropDown">
            <li><a href="javascript:" data-action="1">Скопировать и отправить в архив</a></li>
            <li><a href="javascript:" data-action="2">Отправить в архив</a></li>
            <li><a href="javascript:" data-action="3">Скопировать</a></li>
        </ul>
      </div>
		</form>
	</div>
</div>