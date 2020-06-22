<?php

require_once('config.php');
require_once('functions.php');

session_start();
$dbh = connectDb();

$item_id = $_GET['id'];
$user_id = $_SESSION['id'];

$sql = "INSERT INTO favorites (item_id, user_id) VALUES (:item_id, :user_id)";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':item_id',$item_id,PDO::PARAM_INT);
$stmt->bindParam('user_id',$user_id, PDO::PARAM_INT);
$stmt->execute();

$url = $_SERVER['HTTP_REFERER'];
header('Location: ' . $url);