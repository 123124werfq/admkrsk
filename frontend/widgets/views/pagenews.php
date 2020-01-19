<div class="press-list">
    <?php foreach ($news as $key => $data) {
    	echo $this->render('@frontend/views/news/_news',['data'=>$data]);
    }?>
</div>