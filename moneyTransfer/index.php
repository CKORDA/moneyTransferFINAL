<?php
// DETAIL
require_once('db.php');

$result=$db->query('SELECT * FROM users);
echo $result->rowCount();
$user=$result->fetch();
?>
<a href="index.php">Go back to index</a>
<h1><?= $user['email'] ?></h1>