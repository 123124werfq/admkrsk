<?php
/* @var common\models\Page $page */

?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="" style="width: 100%;">
                    <div class="content">
                        <h1>Интерактивное голосование</h1>

                        <?php if(empty($data)) {?>
                            <p>В данный момент голосование не проводится</p>
                        <?php } else {

                            foreach($data as $ckey => $contest)
                            {
                                if($contest['count'])
                                    echo "<p>{$contest['name']}</p>";

                                echo "<ul>";
                                foreach($contest['profiles'] as $profile)
                                {
                                    if($contest['vote_type'] == 'Баллы')
                                    {
                                        $currentResult =  '<span class="badge badge-success">'.(int)$profile['vote_value'].'</span>';
                                    }
                                    else
                                    {
                                        switch($profile['vote_value'])
                                        {
                                            case 1: $currentResult = '<span class="badge badge-success">за</span>'; break;
                                            case -1: $currentResult = '<span class="badge badge-danger">против</span>'; break;
                                            default: $currentResult = '<span class="badge badge-warning">Вы ещё не выставили оценку</span>';
                                        }
                                    }

                                    echo "<li><a href='/contest/item/{$profile['project_id']}'>{$profile['name']} $currentResult</a></li>";
                                }
                                echo "</ul>";
                            }


                        } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
