<?php

require_once('config.php');
// アカウント登録

function ChkUser($user_param)
{
  $user_name = $user_param['name'];
  $email = $user_param['email'];
  $password = $user_param['password'];
  $picture = $user_param['picture'];

  $errors = [];

  if ($user_name == '') {
    $errors[] = 'User Name が未入力です。';
  }

  if ($email == '') {
    $errors[] = 'Mail Address が未入力です。';
  }

  if ($password == '') {
    $errors[] = 'Password が未入力です。';
  }

  if ($picture == '') {
    $errors[] = 'Profile Image が選択されていません';
  }
  return $errors;
}

function ChkEmail()
{
  $dbh = connectDb();
  $sql = 'SELECT * FROM users WHERE email = :email';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $errors[] = '既に使用されているメールアドレスです';
  }
  return $errors;
}

function insertUser($user_param)
{
  $user_name = $user_param['name'];
  $email = $user_param['email'];
  $password = $user_param['password'];
  $picture = $user_param['picture'];
  $dbh = connectDb();

  if (empty($errors)) {
    $sql = <<<SQL
    INSERT INTO
    users
  (
    email,
    user_name,
    password,
    picture
  )
    VALUES
  (
    :email,
    :user_name,
    :password,
    :picture
  )
  SQL;
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':email', $email, PDO::FETCH_ASSOC);
    $stmt->bindParam(':user_name', $user_name, PDO::FETCH_ASSOC);
    $pw_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam(':password', $pw_hash);
    $stmt->bindParam(':picture', $picture, PDO::FETCH_ASSOC);

    $stmt->execute();
  }
}