<?php
/* @var array $menu */
/* @var User $user */
/* @var string $active_url */

use backend\assets\AppAsset;
use common\models\User;
use yii\helpers\Html;

$bundle = AppAsset::register($this);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                    <img width="50" height="50" alt="" class="img-circle" src="<?=''//$user->getAvatar(['w'=>100,'h'=>100],true)?>"/>
                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?=$user->email?></strong>
                     </span> <span class="text-muted text-xs block">Администратор <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
<!--                        <li>--><?php //// echo Html::a('Профиль', ['/cabinet/user/update', 'id' => $user->id]) ?><!--</li>-->
<!--                        <li class="divider"></li>-->
                        <li><?= Html::a('Выйти', ['site/logout'], ['data' => ['method' => 'post']]) ?></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img src="<?= $bundle->baseUrl . '/img/logo-invert.svg' ?>" alt="" />
                </div>
            </li>
            <?php foreach ($menu as $key => $item) {
                $roles = isset($item['roles']) ? $item['roles'] : [];
                if (empty($roles) || $user->can($roles)) {
                    $active = (Yii::$app->controller->id==$key || (isset($item['submenu'][Yii::$app->controller->id])))?'active':'';

                    if (isset($item['submenu'])) {
                        echo '<li class="'.$active.'"><a href="#"><i class="'.$item['icon'].'"></i> <span class="nav-label">'.$item['title'].'</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">';

                        foreach ($item['submenu'] as $skey=>$child) {
                            $roles = isset($child['roles']) ? $child['roles'] : [];
                            if (empty($roles) || $user->can($roles)) {
                                $active = ($active_url==$skey)?'active':'';

                                if (isset($child['submenu'][$active_url])) {
                                    $active = 'menu-open';
                                }

                                echo  '<li class="'.$active.'">';

                                echo '<a href="/'.$skey.'">'.$child['title'];

                                /*if (!empty($child['submenu']))
                                echo '<span class="caret"></span>';*/

                                echo '</a>';

                                /*if (!empty($child['submenu'])) {

                                    echo '<ul class="nav sub-nav">';
                                    foreach ($child['submenu'] as $sskey=>$subchild) {
                                        $active = ($active_url==$sskey)?'class="active"':'';

                                        echo '<li '.$active.'><a href="/cabinet/'.$sskey.'"><span class="'.$subchild['icon'].'"></span>'.$subchild['title'].'</a></li>';
                                    }
                                    echo '</ul>';
                                }*/
                                echo '</li>';
                            }
                        }

                        echo "</ul>";
                    }
                    else
                        echo  '<li class="'.$active.'"><a href="/'.$key.'"><i class="'.$item['icon'].'"> </i> <span class="nav-label">'.$item['title'].'</span></a></li>';
                }
            } ?>
        </ul>
    </div>
</nav>