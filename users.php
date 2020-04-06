<?php

require_once('config.php');
// アカウント登録

function ChkUser($user_param)
{
  
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
  $image = $user_param['image'];
  $dbh = connectDb();

  if (empty($errors)) {
    $sql = <<<SQL
    INSERT INTO
    users
  (
    email,
    user_name,
    password,
    image
  )
    VALUES
  (
    :email,
    :user_name,
    :password,
    :image
  )
  SQL;
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':email', $email, PDO::FETCH_ASSOC);
    $stmt->bindParam(':user_name', $user_name, PDO::FETCH_ASSOC);
    $pw_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam(':password', $pw_hash);
    $stmt->bindParam(':image', $image, PDO::FETCH_ASSOC);

    $stmt->execute();
  }
}

// ログイン
function LoginChk($user_param)
{
  $email = $user_param['email'];
  $password = $user_param['password'];
  $dbh = connectDb();
  $errors = [];

  if ($email == '') {
    $errors[] = 'Mail Address が未入力です。';
  }

  if ($password == '') {
    $errors[] = 'Password が未入力です。';
  }

  if (empty($errors)) {
    $sql = 'SELECT * FROM users WHERE email = :email';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}

function getPostFindById($id)
{
  $dbh = connectDB();
  // ヒアドキュメント <<<でSQLと同じ文字出てくるまで1つなぎとする
  $sql = "SELECT* FROM users WHERE id = :id";
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}