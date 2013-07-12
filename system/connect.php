<?php

// database connect

$username = 'deb68080_admin';
$password = '[@Gehe1m]';
$db_name  = 'deb68080_tlal';
$hostname = 'localhost';

$db = mysql_connect($hostname, $username, $password);

if (!$db) {
    die('Not connected: ' . mysql_error());
}

$db_selected = mysql_select_db($db_name, $db);
if (!$db_selected) {
    die ('Can\'t use: ' . mysql_error());
}

