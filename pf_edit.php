<?php

require_once('config.php');
require_once('functions.php');

session_start();

$dbh = connectDB();
$sql = "SELECT* FROM users WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $image = $_FILES['image']['name'];

  if ($image) {
    $ext = substr($image, -3);
    if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif' && $ext != 'JPG') {
      $errors[] = '画像ファイルは jpg png gif のいずれかを選択してください。';
    }
  }

  if (empty($errors)) {
    $profileImage = date('YmdHis') . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], 'user_image/' . $profileImage);
    $_SESSION['join']['image'] = $profileImage;

    $sql = <<<SQL
    UPDATE
      users
    SET
      image = :profileImage
    WHERE
      id = :id;
    SQL;

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':profileImage', $profileImage, PDO::PARAM_STR);
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
  }

  header('Location: profile.php');
  exit;
}
