<div class="press-list">
    <?php foreach ($news as $key => $data) {
    	echo $this->render('/views/_news',['data'=>$data]);
    }?>
</div>