<?php

require_once('config.php');
require_once('functions.php');

connectDb();


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
  </div>
  <!-- ここまでヘッダー -->

  <!-- ここからメイン -->
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg mx-auto">
        <div class="card card-signin my-5 bg-light">
          <form action="sign_up.php" method="post">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="name" name="name" required>
            </div>
            <div class="form-group">
              <label for="email">Mail Address</label>
              <input type="email" name="email" required>
            </div>
            <div class="form-group">
              <label for="password">password</label>
              <input type="password" name="password" required>
            </div>
            <div class="form-group">
              <label for="imgae">Profile Image</label>
              <input type="file" name="image" required>
            </div>
            <input type="submit" value="">
          </form>
        </div>
      </div>
    </div>

    <!-- ここはフッター -->
    <footer class="footer font-small">
      <div class="footer-copyright text-center py-3 footer-font">
        &copy;Copyright © 2020 KleidunG.All rights reserved
      </div>
    </footer>
    <!-- ここまでフッター -->

</body>

</html>