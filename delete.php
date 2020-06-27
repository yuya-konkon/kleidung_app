<?php

require_once('config.php');
require_once('functions.php');

$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM items WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$item = $stmt->fetch();

if (empty($item)) {
  header('Location: profile.php');
  exit;
}

$sql_delete = 'DELETE FROM items WHERE id = :id';
$stmt_delete = $dbh->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_delete->execute();

header('Location: profile.php');
exit;
