<?php

require_once('config.php');
require_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $picture = $_POST['picture'];

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

  // メールアドレスがかぶっていないかの確認
  $dbh = connectDb();
  $sql = 'SELECT * FROM users WHERE email = :email';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $errors[] = '既に使用されているメールアドレスです';
  }

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

    echo $user_name;

    // header('Location: login.php');
    // exit;
  }
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KleidunG</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <!-- ここはheader -->
  <div class="flex-col-area">
    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
      <a href="index.php" class="logo">KleidunG</a>
      <div class="collapse navbar-collapse" id="navbarToggle">
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
          <?php if ($_SESSION['id']) : ?>
            <li class="nav-item">
              <a href="log_out.php">ここに写真</a>
            </li>
            <li class="nav-item">
              <a href="new.php">ここにぷらす</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a href="sign_up.php">New Account</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>

    <!-- ここからメイン -->
    <div class="container CA-form">
      <div class="row">
        <form action="sign_up.php" method="post" class="CA-form">
          <ul>
            <li>
              <label for="name" class="label-name">User Name</label>
              <input type="name" name="name" placeholder="ユーザー名をしてください" required class="CAF-item">
            </li>
            <li>
              <label for="email" class="label-name">Mail Address</label>
              <input type="email" name="email" placeholder="メールアドレスを入力してください" required class="CAF-item">
            </li>
            <li>
              <label for="password" class="label-name">Password</label>
              <input type="password" name="password" placeholder="パスワードを入力してください" required class="CAF-item">
            </li>
            <li>
              <label for="picture" class="label-name">
                Profile Image
                <input type="file" name="picture" required class="CAF-item image-btn" id="picture">
              </label>
            </li>
            <li>
              <input type="submit" value="Create Account" class="CA-btn">
            </li>
          </ul>
        </form>
      </div>
    </div>

    <!-- ここからフッター -->
    <footer class="footer font-small">
      <div class="footer-copyright text-center py-3 footer-font">
        &copy;Copyright © 2020 KleidunG.All rights reserved
      </div>
    </footer>
  </div>
</body>

</html>