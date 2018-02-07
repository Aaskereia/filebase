<?php require "includes/db.php"; ?>  <!-- подключаем базу данных и начало сессии -->

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
	<title>filebase</title>
	<link rel="stylesheet" type="text/css" href="styles/libs.min.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
	<div class="container">
		<div class="loginform-cont">
			<form class="loginform" method="post" action="includes/login.php">
				
				<!-- Проверка есть ли активная сессия -->
				<?php if( isset($_SESSION['logged_user']) ) : ?>
					<p class="autY">Вы авторизованы под логином <?php echo $_SESSION['logged_user']->login; ?>. Можете <a href="catalogs.php">Перейти в каталог!</a></p>
				<?php else : ?>
					<p class="autN">Вы не авторизованы. Войдите в систему. </p>
				<?php endif; ?>
				<!-- \ -->

				<div class="form-item form-log">
					<label for="login">Введите ваш логин: </label>
					<input type="text" name="login" id="login" placeholder="Логин">
				</div>
		
				<div class="form-item form-pas">
					<label for="password">Введите ваш пароль: </label>
					<input type="password" name="password" id="password" placeholder="Пароль">
				</div>
				
				<div class="btns">
					<input class="btn" type="submit" id="submit" name="do_login" value="Войти">
					<button class="btn pop" type="button" href="#reg-form">Регистрация</button>
				</div>
			</form>
		</div>
	</div>
	
	<div class="hidden">
		<form class="reg-form" method="post" action="includes/reg.php" id="reg-form">
			<label for="reg-login">Придумайте ваш логин: </label>
			<input id="reg-login" type="text" name="reg-login" placeholder="Ваш логин">

			<label for="reg-password">Придумайте пароль: </label>
			<input id="reg-password" type="password" name="reg-password" placeholder="Пароль">
			<div class="btns">
				<button class="btn" type="submit" name="do_signup">Готово!</button>
			</div>
		</form>
	</div>

	<script type="text/javascript" src="scripts/libs.min.js"></script>
	<script type="text/javascript" src="scripts/script.js"></script>
</body>
</html>