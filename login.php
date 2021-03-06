<?php

require_once('config.php');
require_once('functions.php');

session_start();

$dbh = connectDb();

if ($_SESSION['id']) {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];
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

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $user['password'])) {
      $_SESSION['id'] = $user['id'];
      header('Location: index.php');
      exit;
    } else {
      $errors[] = 'メールアドレスかパスワードが間違っています';
    }
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
  <link rel="icon" href="/favicon.ico">
</head>

<body>

  <div class="flex-col-area">
    <!-- ここはheader -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-5">
      <a href="index.php" class="logo">KleidunG</a>
      <div class="collapse navbar-collapse" id="navbarToggle">
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
          <?php if ($_SESSION['id']) : ?>
            <li class="nav-item new-post">
              <a href="new.php">New Post</a>
            </li>
            <li class="nav-item">
              <a href="profile.php"><img src="user_image/<?php echo h($user['image'], ENT_QUOTES); ?>" alt="<?php echo h($user['name'], ENT_QUOTES); ?> " class="nav-image"></a>
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
        <form action="login.php" method="post" class="CA-form">
          <?php if ($errors) : ?>
            <ul class="alert alert-danger">
              <?php foreach ($errors as $error) : ?>
                <li><?php echo $error; ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <ul>
            <li>
              <label for="email" class="label-name">Mail Address</label>
              <input type="email" class="CAF-item" required name="email" placeholder="Mail Address">
            </li>
            <li>
              <label for="password" class="label-name">Password</label>
              <input type="password" class="CAF-item" required name="password" placeholder="Password">
            </li>
            <li>
              <input type="submit" value="Login" class="login-btn">
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