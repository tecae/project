<?php
session_start();// ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������, ���� �� ��������� �� �����. ����� ����� ��������� �� � ����� ������ ���������!!!

if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //������� ��������� ������������� ����� � ���������� $login, ���� �� ������, �� ���������� ����������
if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
//������� ��������� ������������� ������ � ���������� $password, ���� �� ������, �� ���������� ����������

if (empty($login) or empty($password)) //���� ������������ �� ���� ����� ��� ������, �� ������ ������ � ������������� ������
{
exit ("�� ����� �� ��� ����������, �������� ����� � ��������� ��� ����!");
}
//���� ����� � ������ �������,�� ������������ ��, ����� ���� � ������� �� ��������, ���� �� ��� ���� ����� ������
$login = stripslashes($login);
$login = htmlspecialchars($login);

$password = stripslashes($password);
$password = htmlspecialchars($password);

//������� ������ �������
$login = trim($login);
$password = trim($password);


// login on sqlite

$dblite = new SQLite3('../../db/webdb.db');

$results = $dblite->query("SELECT * FROM user WHERE _user='$login'");
$first_row =  $results->fetchArray();
if (empty($first_row['_pass']))
{
//���� ������������ � ��������� ������� �� ����������
	header("Location: ../index.php?whotsey=error");	
}
else {
//���� ����������, �� ������� ������
        if ($first_row['_pass']==$password) {
          //���� ������ ���������, �� ��������� ������������ ������! ������ ��� ����������, �� �����!
          $_SESSION['_user']=$first_row['_user']; 
          $_SESSION['id']=$first_row['id'];//��� ������ ����� ����� ������������, ��� �� � ����� "������ � �����" �������� ������������
          header("Location: ../index.php"); 
		      exit();    // ��������� ������ �������, ����� ����� � �������
          }

       else {
       //���� ������ �� �������
		header("Location: ../index.php");	
		//exit();
	   }
}	

$dblite->close();

/*	
//
// ������������ � ����
include ("../db.php");// ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ���� 



$result = mysql_query("SELECT * FROM user WHERE _user='$login'",$db); //��������� �� ���� ��� ������ � ������������ � ��������� �������
$myrow = mysql_fetch_array($result);
if (empty($myrow['_pass']))
{
//���� ������������ � ��������� ������� �� ����������
exit ("��������, �������� ���� ����� ��� ������ ��������.");
}
else {
//���� ����������, �� ������� ������
          if ($myrow['_pass']==$password) {
          //���� ������ ���������, �� ��������� ������������ ������! ������ ��� ����������, �� �����!
          $_SESSION['_user']=$myrow['_user']; 
          $_SESSION['id']=$myrow['id'];//��� ������ ����� ����� ������������, ��� �� � ����� "������ � �����" �������� ������������
          echo "�� ������� ����� �� ����! <a href='index.php'>������� ��������</a>";
          }

       else {
       //���� ������ �� �������
       exit ("��������, �������� ���� ����� ��� ������ ��������.");
	   }
}*/
?>