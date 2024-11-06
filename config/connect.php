<?php

$hostname = "10.10.10.157";
$username = 'csc210user';
$password = 'CSC!2qwasZX';
$dbname = 'group2';

$con = mysqli_connect($hostname, $username, $password, $dbname);

if (!$con) {
    die("Connection failed : " . mysqli_connect_error());
}

?>
