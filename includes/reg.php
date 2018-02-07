<?php require 'db.php'; 

// $reglog = $_POST['reg-login'];
// $regpas = $_POST['reg-password'];
// лучше сразу использовать $дата и с тем и с другим 
$data = $_POST;

$finduser = R::findone( 'users', 'login = ?', array($data['reg-login'])); //ищем в базе данных запись с таким логином

if (isset($data['do_signup'])) {
//если кнопка была нажата - проверяем на ошибки и регестрируем
    $errors = array();
    if( trim($data['reg-login']) == '' ) //trim удаляет пробелы
    {
        $errors[] = 'Введите логин';
    }
    if( $data['reg-password'] == '' )
    {
        $errors[] = 'Вы не ввели пароль';
    }
	if ($finduser) { // тут вызов того, что написал выше
		$errors[] = 'Пользователь с логином ' .$data['reg-login']. ' уже существует. Введитей другой логин.';
	} 
	if( empty($errors) ) {
		$user = R::dispense('users');
		$user->login = $data['reg-login'];
		$user->password = password_hash($data['reg-password'], PASSWORD_DEFAULT); //кодировка пароля для хранения в бд
		R::store($user);
		echo ('<div style="color: green;">Пользователь ' .$data['reg-login']. ' добавлен в базу. <br></div>');
	} else {
       echo '<div style="color: red;">'.array_shift($errors).'</div><hr>'; // если есть ошибки-вывести 1ое из массива ошибок
    }



}
?>

<form action="../index.php" method="get">
	<button type="submit" href="http://osnovyphp/index.php">Назад</button>
</form>
