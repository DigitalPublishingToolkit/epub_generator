<?php

// database connect

$username = '';
$password = '';
$db_name  = '';
$hostname = '';

$db = mysql_connect($hostname, $username, $password);

if (!$db) {
    die('Not connected: ' . mysql_error());
}

$db_selected = mysql_select_db($db_name, $db);
if (!$db_selected) {
    die ('Can\'t use: ' . mysql_error());
}

