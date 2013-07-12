<?php 
require 'system/connect.php'; 
require 'system/settings.php'; 

$check['name'] = $_POST['name'];
$check['password'] = $_POST['password'];

$pwd_q = "SELECT * FROM users WHERE username = '".$_POST['name']."' LIMIT 1;"; 

$check_result = mysql_query($pwd_q); 
if (mysql_num_rows($check_result) > 0) {
  $row = mysql_fetch_assoc($check_result);
  $check_pwd = md5($settings['pwd_salt'].$check['password']);

  if ($check_pwd == $row['password']) {
    //pass
    $_SESSION['login'] = TRUE;
    $_SESSION['user'] = $row['username'];
    header('Location: index.php');
  } else {
    header('Location: login.php');
  }
}

