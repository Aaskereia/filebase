<?php require 'db.php'; 

$data = $_POST;

if( isset($data['do_login']) ) {

	$errors = array();
	$user = R::findone( 'users', 'login = ?', array($data['login']));

	if ( $user ) {
		if ( password_verify($data['password'], $user->password) ) {

			$_SESSION['logged_user'] = $user;
			header('Location: ../catalogs.php');

		} else {
			$errors[] = 'Неверно введен пароль!';
		}	
	} else {
		$errors[] = 'Пользователь не найден!';
	}


if( ! empty($errors) ) {
	echo '<div style="color: red;">'.array_shift($errors).'</div><hr>';
}


}
 ?>
 
<form action="../index.php" method="get">
	<button type="submit" href="http://osnovyphp/index.php">Назад</button>
</form>



