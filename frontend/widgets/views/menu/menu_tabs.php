<div class="smart-menu">
    <div class="container">
        <div class="smart-menu-tabs slide-hover tab-controls tab-controls__responsive">
            <div class="tab-controls-holder">
                <span class="slide-hover-line"></span>
                <?php foreach ($menu->links as $key => $link) {?>
                	<div class="smart-menu-tabs_item tab-control <?=$key==0?'tab-control__active':''?> slide-hover-item" data-href="#tabs<?=$menu->id_menu?>-tab<?=$key?>"><a  class="smart-menu-tabs_control"><?=$link->label?></a></div>
                <?php }?>
            </div>
        </div>
        <div class="smart-menu-content">
            <?php foreach ($menu->links as $key => $link) {?>
            <div id="tabs<?=$menu->id_menu?>-tab<?=$key?>" class="tab-content <?=$key==0?'active':''?>">
            	<?php
            		if (!empty($link->template))
                    {
            			echo $this->render("@app/widgets/views/".$link->template,['menu'=>$link->subMenu]);
                    }
            		elseif (!empty($link->id_page))
                    {
                        if (!empty($link->template))
                        {
                            $news = $link->getNews()->limit(4)->all();
                            if (!empty($news))
                                echo '<div class="news-list">'.$this->render("@app/widgets/views/".$link->template,['news'=>$news,'page'=>$link->page]).'</div>';
                        }
                        else
                        {
                            $news = $link->getNews()->limit(12)->all();
                            echo '<div class="news-list">';
                            foreach ($news as $rkey => $data) {
                                echo $this->render('../news/_news',['data'=>$data,'page'=>$link->page]);
                            }
                            echo "</div>";
                        }
                    }
                    elseif (!empty($link->content))
                        echo $link->content;
            	?>
            </div>
            <?php }?>
        </div>
    </div>
</div>