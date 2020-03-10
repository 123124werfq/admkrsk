<div class="ibox">
	<div class="ibox-content text-center">
		<form action="">
			Вы выбрали <b id="selectCount">0</b> из <b><?=$dataProvider->getTotalCount()?></b>                      
			<div class="dropdown">
              <button class="btn btn-default dropdown-toggle" type="button" id="actionDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Действия
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" aria-labelledby="actionDropDown">
                  <li><a href="?action=0">Скопировать и отправить в архив</a></li>
                  <li><a href="?action=1">Отправить в архив</a></li>
                  <li><a href="?action=2">Скоировать</a></li>
              </ul>
            </div>
		</form>
	</div>
</div>