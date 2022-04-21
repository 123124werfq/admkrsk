<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
$this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <ol class="breadcrumbs">
            <li class="breadcrumbs_item"><a href="/">Главная</a></li>
            <li class="breadcrumbs_item"><span>Вход на сайт</span></li>
        </ol>
    	<div class="row">
            <div class="col-2-third order-xs-1">
                <div class="pagetitle">
    	            <h1>
    	                Вход на официальный сайт администрации города Красноярска
    	            </h1>
                </div>

                <div style="display: none;">
            	Вход
    		    </div>
    		    <div id="SslWarning" style="color: red; display: none; padding-bottom: 10px;">
    		        Предупреждение. Эта страница не зашифрована по правилам безопасной связи. Имена пользователей, пароли и другие сведения будут передаваться открытым текстом. За дополнительными сведениями обратитесь к администратору.
    		    </div>

    			<div class="content">
    			    <p>    После авторизации в Личном кабинете жителя города с использованием ЕСИА&nbsp;у вас появятся возможности:</p>
    				<p>- просмотра статуса обращений;<br>- подачи заявок на предоставление муниципальных&nbsp;	услуг, получения информации по зарегистрированным заявкам;<br>- заполнения и редактирования интерактивной анкеты кандидата для включения в резерв управленческих кадров администрации города.</p>
    				<p>
<<<<<<< HEAD
    				   <strong><a href="https://www.youtube.com/watch?v=J2VH7o-q5L8" target="_blank">Как зарегистрироваться в ЕСИА​</a></strong>
=======
    				   <strong>Авторизация на сайте без использования&nbsp;ЕСИА в ближайшее время будет прекращена.</strong></p>
    				<p>
    				   <strong> Рекомендуем использовать для авторизации на сайте ЕСИА (www.gosuslugi.ru). </strong></p>
    				<p>
    				   <strong><a href="https://www.youtube.com/watch?v=J2VH7o-q5L8" target="_blank">Как зарегистрироваться в ЕСИА </a></strong>
>>>>>>> 8c7b5f6829b23884bb4e71c167eee488f7a41fb0
    				</p>
    				<br>
    			</div>

                <p>Авторизация через портал государственных услуг Российской Федерации (www.gosuslugi.ru)</p>
                <input type="image" name="ctl00$PlaceHolderMain$LoginESIAImageButton" id="ctl00_PlaceHolderMain_LoginESIAImageButton" class="image" src="http://www.admkrsk.ru/Style%20Library/res/images/gosuslugi_logo-esia.png">
    			<table id="ctl00_PlaceHolderMain_signInControl" cellspacing="0" cellpadding="0" style="width:100%;border-collapse:collapse;">
                	<tbody><tr>
                		<td>
                        <div class="row mt-3 mb-4">
                            <div class="col-fourth">
                                <a href="https://esia.gosuslugi.ru/sia-web/rf/registration/lp/Index.spr" class="btn btn__block btn__border">Регистрация</a>
                            </div>
                            <div class="col-fourth ibutton2">
                                <a class="btn btn__block btn__border" style="background: #8F1A1E !important; color: #FFF !important;" href="<?=$esiaurl?>">Войти</a>
                            </div>
                        </div>
    				</td>
                	</tr>
                    </tbody>
                </table>
             </div>
            <div class="col-third order-xs-0">

            </div>
        </div>
        <hr class="hr hr__md">
    </div>
    <!--div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following fields to login:</p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div style="color:#999;margin:1em 0">
                        If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                        <br>
                        Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div-->
</div>
