<?php
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	$root = $_SERVER["DOCUMENT_ROOT"];
	require($root.'/server/classes/db.php');
	include($root.'/server/preloading.php');
	include($root.'/server/functions.php');
	require($root.'/server/classes/users.php');
	require($root.'/server/classes/garbage.php');
	$mainGarbageId = 20;

?>
<!DOCTYPE html>
<html>
	<head>
		<title>GoCleanWorld</title>
		<link rel="stylesheet" type="text/css" href="/css/main.css">
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
		<script src="/js/jquery.min.js"></script>
		<script src="/js/bootstrap.js"></script>
		<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
		<script src="/js/main.js"></script>
	</head>
	<body>
		<!-- Modals -->
		<div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				    <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="previewTitle"></h4>
				    </div>
				    <div class="modal-body" id="previewText">
					    
				    </div>
				    <div class="modal-footer" id="previewFooter"></div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="addNewEvent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				    <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Профиль пользователя <?=$user_login?></h4>
				    </div>
				    <div class="modal-body">
					    <form enctype="multipart/form-data" method="post" action="/event/add/">
						  <div class="form-group">
						    <label for="title">Заголовок</label>
						    <input type="text" class="form-control" name="title" id="title" placeholder="Заголовок">
						  </div>
						  <div class="form-group">
						    <label for="city">Город</label>
						    <input type="text" class="form-control" name="city" id="city" placeholder="Город">
						  </div>
						  <div class="form-group">
						    <label for="street">Улица</label>
						    <input type="text" class="form-control" name="street" id="street" placeholder="Улица">
						  </div>
						  <div class="form-group">
						    <label for="house">Дом</label>
						    <input type="text" class="form-control" name="house" id="house" placeholder="Дом">
						  </div>
						  <div class="form-group">
						    <label for="photo">Загрузите фотографию</label>
						    <input type="file" id="photo" name="photo">
						    <p class="help-block">Если Вы хотите, чтобы Ваше событие попало в шапку загружайте широкоформатные фотографии</p>
						  </div>
						  <div class="form-group">
						  	<label for="description">Описание</label>
						  	<textarea class="form-control" id="description" name="description" rows="3" placeholder="Короткое описание"></textarea>
						  </div>
						  <button type="submit" class="btn btn-default">Создать событие</button>
						</form>
				    </div>
				    <div class="modal-footer">
				    	
				    </div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="miniProfileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				    <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Профиль пользователя <?=$user_login?></h4>
				    </div>
				    <div class="modal-body">
					    <div class="row">
					    	<div class="col-xs-6">Почта</div>
					    	<div class="col-xs-6"><?=$user_email;?></div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-6">Время регистрации</div>
					    	<div class="col-xs-6"><?=new_time($user_regtime);?></div>
					    </div>
					    <div class="row">
					    	<div class="col-xs-6">Создал GCW</div>
					    	<div class="col-xs-6">0</div>
					    </div>
						<div class="row">
					    	<div class="col-xs-6">Помог в GCW</div>
					    	<div class="col-xs-6">0</div>
					    </div>
				    </div>
				    <div class="modal-footer">
				    	
				    </div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="regModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				    <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Регистрация</h4>
				    </div>
				    <div class="modal-body">
				    	<p class="bg-danger" id="regErrors" style="padding: 10px; display: none;"></p>
							<div class="form-group">
						    	<label for="reg_email">e-Mail адрес</label>
						    	<input type="email" class="form-control" id="reg_email" placeholder="Email">
							</div>
							<div class="form-group">
						    	<label for="reg_login">Логин</label>
						    	<input type="email" class="form-control" id="reg_login" placeholder="Логин">
							</div>
							<div class="form-group">
						    	<label for="password1">Пароль</label>
						    	<input type="password" class="form-control" id="password1" placeholder="Пароль">
							</div>
							<div class="form-group">
						    	<label for="password2">Повторите пароль</label>
						    	<input type="password" class="form-control" id="password2" placeholder="Пароль">
							</div>
						  	<button type="submit" class="btn btn-primary" onclick="users.reg();">Зарегистрироваться</button>
				    </div>
				    <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				        <button type="button" class="btn btn-info" onclick="users.showAuthForm();">Перейти в авторизацию</button>
				    </div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="authModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				    <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title" id="myModalLabel">Авторизация</h4>
				    </div>
				    <div class="modal-body">
				    	<p class="bg-danger" id="authErrors" style="display: none;"></p>
							<div class="form-group">
						    	<label for="auth_login">Логин</label>
						    	<input type="email" class="form-control" id="auth_login" placeholder="Логин">
							</div>
							<div class="form-group">
						    	<label for="auth_password">Пароль</label>
						    	<input type="password" class="form-control" id="auth_password" placeholder="Пароль">
							</div>
						  	<button type="submit" class="btn btn-primary" onclick="users.auth();">Авторизоваться</button>
				    </div>
				    <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
				        <button type="button" class="btn btn-info" onclick="users.showRegForm();">Перейти в регистрацию</button>
				    </div>
				</div>
			</div>
		</div>
		<?
			$mainGarbage = $garbage->get($mainGarbageId);
		?>
		<!-- Main -->
		<div id="container">
			<div class="header">
				<div class="col-lg-9">
					<div class="logo">GoCleanWorld</div>
				</div>
				<div class="col-lg-3">
					<p class="userMiniProfile text-right">
						<? if(isLogged()) {echo "<div class=\"btn btn-primary btn-sm\" data-toggle=\"modal\" data-target=\"#addNewEvent\">Добавить новое событие</div><span class=\"btn btn-sm btn-info\" style=\"float: right;\" data-toggle=\"modal\" data-target=\"#miniProfileModal\">Профиль</span>";} else {echo "<span data-toggle=\"modal\" data-target=\"#authModal\">Войти</span>";}?>
					</p>
				</div>
				
				<div class="lastGCW">
					<div class="row">
						<div class="col-lg-9" onclick="garbage.preview(<?=$mainGarbage["id"]?>);">
							<div class="img_description">
								<?=$mainGarbage["maintitle"]?>
							</div>
							<img src="<?=$mainGarbage["image"]?>" alt="<?=$mainGarbage["maintitle"]?>" style="width: 100%">
						</div>
						<div class="col-lg-3" style="padding: 5px;">
							<div id="map" class="lastMap"></div>
							<p style="width: 272px; margin-top: 10px;">
								<button type="button" class="btn btn-success btn-lg btn-block" onclick="garbage.preview(<?=$mainGarbage["id"]?>);">Я ПОМОГУ!<sup> <?=$garbage->countVisitors($mainGarbage["id"])?></sup></button>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="main">
				<div class="row">
					<?
						$quer = $db->query("SELECT `id`, `image`, `maintitle` FROM `garbage` WHERE `active` = 1 LIMIT 0, 9");
						while($el = $db->assoc($quer)) {
							echo '<div class="col-lg-4" style="max-height: 210px; overflow: hidden;" onclick="garbage.preview('.$el["id"].')"><div class="countVisitorsMini">'.$garbage->countVisitors($el["id"]).'</div><div class="maintitleOver">'.$el["maintitle"].'</div><img style="width: 100%;" src="'.$el["image"].'"></div>';
						}
					?>
				</div>
			</div>
			<div class="footer">
				(c) by Sketcher2010 for BarsHackathon 2016
			</div>
		</div>
		<script type="text/javascript">
			ymaps.ready(init);
			var myMap;

			function init(){     
			 myMap = new ymaps.Map("map", {
			  center: [<?=$mainGarbage["coord_x"]?>, <?=$mainGarbage["coord_y"]?>],
			   zoom: 7
			  });
			}
		</script>
	</body>
</html>
