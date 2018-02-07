<?php require "rb.php"; session_start();

	$connection = R::setup( 'mysql:host=localhost;dbname=test_db', 'root', '' ); //подключение к базе
	if (!$connection) {
		echo 'Не получилось соединиться с базой данных.';
		echo mysql_connect_error();
		exit();
	} 
?>
