<?php

require_once('config.php');
require_once('functions.php');

session_start();
$id = $_GET['id'];

$dbh = connectDb();
$sql = 'SELECT * FROM favarites WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$favorete = $stmt->fetch();

$sql_delete = 'DELETE FROM favorites WHERE id  = :id;';
$stmt_delete = $dbh->prepare($sql_delete);
$stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
$stmt_delete->execute();

$url = $_SERVER['HTTP_REFERER'];
header('Location: ' . $url);