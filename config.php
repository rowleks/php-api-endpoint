<?php 
define('HOST', 'localhost');
define('DB', 'api');
define('USER', 'root');
define('PASSWORD', 'rolexdb');

$conn = new mysqli(HOST, USER, PASSWORD, DB);

if ($conn->connect_error)
{ exit('DB connection failed: ' . $conn->connect_error); }


?>