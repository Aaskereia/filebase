<?php require '/includes/db.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
	<title>filebase - catalogs</title>
	<link rel="stylesheet" type="text/css" href="styles/libs.min.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
	<?php 
	if(!isset($_SESSION['logged_user'])) : ?> 
		<h2>Вы не авторизованы!</h2>
		<a href="index.php">Вернуться на главную</a>
	<?php else : ?>

	Авторизован! <hr>
	<p>Привет, <?php echo $_SESSION['logged_user']->login;?>!</p>
	<br>
	<a class="btn" href="includes/logout.php">Выйти</a>

	<button class="btn pop" type="button" href="#newcat">Новая папка</button>
	<div class="hidden">
		<form class="reg-form" method="post" action="catalogs.php" id="newcat">
			<input type="text" name="catalogname" placeholder="Введите название папки">
			<div class="btns">
				<button class="btn" type="submit" name="save">Создать!</button>
			</div>
		</form>
	</div>
	
	<?php 
		$data = $_POST;
		
		if (isset($data['save'])) {

			$catalog = R::dispense('catalogs');
			$catalog->name = $data['catalogname'];
			
			$_SESSION['logged_user']->ownCatalogsList[] = $catalog;	
			R::store($_SESSION['logged_user']);
		}
		
	?>

	<div class="rez">
		<p>Ваши папки:</p>
		<ol class="list">
			
			<?php foreach ( $_SESSION['logged_user']->ownCatalogsList as $list ) { 
			$user_id = $_SESSION['logged_user']['id'];
			$folder_id = $list->id;
			@mkdir("files/$user_id/", 0777);
			@mkdir("files/$user_id/$folder_id", 0777);

			$direcory = "files/$user_id/$folder_id";

			$name = $list->id;
			echo '<li><a class="filelink" href="files.php?id='.$list->id.'"><span>'.$list->name.'</span></a><form class="filedel" action="" method="post"><input class="filedel-btn" type="submit" name="'.$name.'" value="✖" title="Удалить"></form></li>';	

				if( $_POST[$name] ){
					$fileobj = R::load('catalogs', $name); // получение этого файла в виде объекта
					R::trash($fileobj); // удаление с БД данного объекта 

					function removeDirectory($dir) {
					    if ($objs = glob($dir."/*")) {
					       foreach($objs as $obj) {
					         is_dir($obj) ? removeDirectory($obj) : unlink($obj);
					       }
					    }
					    rmdir($dir);
					  }
					removeDirectory($direcory);

				} 
			}
			?> 
		</ol>
	</div>

	<?php endif;?>
	<script type="text/javascript" src="scripts/libs.min.js"></script>
	<script type="text/javascript" src="scripts/script.js"></script>
</body>
</html>
