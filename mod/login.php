<?php
session_start();// вся процедура работает на сессиях. Именно в ней хранятся данные пользователя, пока он находится на сайте. Очень важно запустить их в самом начале странички!!!

if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную

if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
exit ("Вы ввели не всю информацию, венитесь назад и заполните все поля!");
}
//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
$login = stripslashes($login);
$login = htmlspecialchars($login);

$password = stripslashes($password);
$password = htmlspecialchars($password);

//удаляем лишние пробелы
$login = trim($login);
$password = trim($password);


// login on sqlite

$dblite = new SQLite3('../../db/webdb.db');

$results = $dblite->query("SELECT * FROM user WHERE _user='$login'");
$first_row =  $results->fetchArray();
if (empty($first_row['_pass']))
{
//если пользователя с введенным логином не существует
	header("Location: ../index.php?whotsey=error");	
}
else {
//если существует, то сверяем пароли
        if ($first_row['_pass']==$password) {
          //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
          $_SESSION['_user']=$first_row['_user']; 
          $_SESSION['id']=$first_row['id'];//эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
          header("Location: ../index.php"); 
		      exit();    // прерываем работу скрипта, чтобы забыл о прошлом
          }

       else {
       //если пароли не сошлись
		header("Location: ../index.php");	
		//exit();
	   }
}	

$dblite->close();

/*	
//
// подключаемся к базе
include ("../db.php");// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь 



$result = mysql_query("SELECT * FROM user WHERE _user='$login'",$db); //извлекаем из базы все данные о пользователе с введенным логином
$myrow = mysql_fetch_array($result);
if (empty($myrow['_pass']))
{
//если пользователя с введенным логином не существует
exit ("Извините, введённый вами логин или пароль неверный.");
}
else {
//если существует, то сверяем пароли
          if ($myrow['_pass']==$password) {
          //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
          $_SESSION['_user']=$myrow['_user']; 
          $_SESSION['id']=$myrow['id'];//эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
          echo "Вы успешно вошли на сайт! <a href='index.php'>Главная страница</a>";
          }

       else {
       //если пароли не сошлись
       exit ("Извините, введённый вами логин или пароль неверный.");
	   }
}*/
?>