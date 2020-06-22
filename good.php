<?php

require_once('config.php');
require_once('functions.php');

$dbh = connectDb();


session_start();
$ID = $_SESSION["id"];
$no = $_REQUEST["no"];

$sql = "INSERT INTO ユーザーアイディとアイテムアイディ VALUES ('$no', '$ID')";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);


$url = $_SERVER['HTTP_REFERER'];
header('Location: ' . $url);