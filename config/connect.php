<?php

$hostname = "localhost";
$username = 'csc210user';
$password = 'CSC210!';
$dbname = 'group2';

$con = mysqli_connect($hostname, $username, $password, $dbname);

if (!$con) {
    die("Connection failed : " . mysqli_connect_error());
}

?>
