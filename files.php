<?php require '/includes/db.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
	<title>filebase - files</title>
	<link rel="stylesheet" type="text/css" href="styles/libs.min.css">
	<link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>

<?php 

	if (isset($_GET["id"])) {
		$data = $_GET; 
		$thiscatalog = R::findone( 'catalogs', 'id = ?', array($data['id']));
	}

	if(!isset($_SESSION['logged_user']) or ($_SESSION['logged_user']->id) != $thiscatalog->users_id) : ?> 
		<h2>Вы не авторизованы!</h2>
		<a href="index.php">Вернуться на главную</a>
	<?php else : ?>


	Авторизован! <hr>
	<p>Привет, <?php echo $_SESSION['logged_user']->login;?>!</p>
	<br>
	<a class="btn" href="includes/logout.php">Выйти</a>
	<a class="btn" href="catalogs.php">Назад к папкам</a>

	<p class="foldername">Папка - <?php echo $thiscatalog->name;?></p>

	<form action="<?php echo 'files.php?id='.$_GET["id"]?>" enctype="multipart/form-data" method="post">
		<input type="file" name="uploadfile" multiple>
		<input type="submit" name="send_file" class="btn" value="Загрузить">
	</form>
<?php
	$user_id = $_SESSION['logged_user']->id;
	$folder_id = $data['id'];
	$uploaddir = "files/$user_id/$folder_id/";

	if (isset($_POST["send_file"])) {
		@mkdir("files/$user_id/", 0777);
		@mkdir("files/$user_id/$folder_id", 0777);
		// Каталог, в который мы будем принимать файл:
		

		$uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);

		// Копируем файл из каталога для временного хранения файлов:
		if (copy($_FILES['uploadfile']['tmp_name'], iconv("UTF-8", "windows-1251", $uploadfile)))
		{
		echo "<h3 style='color: green;'>Файл успешно загружен на сервер</h3>";
		$file = R::dispense('files');
		$file->name = $_FILES['uploadfile']['name'];
		$file->user_id = $user_id;

		$thiscatalog->ownFilesList[] = $file;
		R::store($thiscatalog);
		}



		else { echo "<h3  style='color: red;'>Ошибка! Не удалось загрузить файл на сервер!</h3>"; exit; }

		// Выводим информацию о загруженном файле:
		echo "<hr><h3>Информация о загруженном на сервер файле: </h3>";
		echo "<p>Оригинальное имя загруженного файла: ".$_FILES['uploadfile']['name']."</p>";
		echo "<p>Тип загруженного файла: ".$_FILES['uploadfile']['type']."</p>";
		echo "<p>Размер загруженного файла: ".round($_FILES['uploadfile']['size']/1048576, 3)." мб</p>"; 
		echo "<p>Временное имя файла: ".$_FILES['uploadfile']['tmp_name']."</p>";
	}
?>
	<div class="rez">
		<p>Список файлов:</p>
		<ol class="list">
			<?php foreach ($thiscatalog->ownFilesList as $filenames) { 
			$name = $filenames->id;
			echo '<li><a class="filelink" href="'.$uploaddir.$filenames['name'].'" style="background: url('.$uploaddir.$filenames['name'].') no-repeat center/cover;"><span>'.$filenames['name'].'</span></a><a class="filesave" href="'.$uploaddir.$filenames['name'].'" title="Скачать" download>✚</a><form class="filedel" action="" method="post"><input class="filedel-btn" type="submit" name="'.$name.'" value="✖" title="Удалить"></form></li>';	

				if( $_POST[$name] ){
		 			$fileobj = R::load('files', $name); // получение этого файла в виде объекта
					R::trash($fileobj); // удаление с БД данного объекта 
					unlink($uploaddir.iconv("UTF-8", "windows-1251", $filenames->name)); // удаление файла с папки
					echo $uploaddir.$filenames->name;
					exit("<meta http-equiv='refresh' content='0; url= $_SERVER[PHP_SELF]?id=$folder_id'>");
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