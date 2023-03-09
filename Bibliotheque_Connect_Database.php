

<?php
/* Connect to a MySQL database using driver invocation */
$dsn = 'mysql:dbname=bibliotheque_bd_ul;host=localhost';
$user = 'root';
$password = '';

$pdo = new PDO($dsn, $user, $password);

?>