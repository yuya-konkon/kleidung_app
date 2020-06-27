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
  $user_name = $_POST['name'];
  $email = $_POST['email'];
  $description = $_POST['description'];


  $errors = [];

  if ($user_name == '') {
    $errors[] = 'User Name が未入力です。';
  }

  if ($email == '') {
    $errors[] = 'Mail Address が未入力です。';
  }

  $dbh = connectDb();
  $sql = 'SELECT * FROM users WHERE email = :email AND id != :id';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $errors[] = '既にメールアドレスが登録されています';
  }

  $sql = <<<SQL
    UPDATE
      users
    SET
      email = :email,
      user_name = :user_name,
      description = :description
    WHERE
      id = :id;
    SQL;
  $stmt = $dbh->prepare($sql);

  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);

  $stmt->execute();

  header('Location: profile.php');
  exit;
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
  <script src="https://kit.fontawesome.com/3ae6904195.js" crossorigin="anonymous"></script>
  <link rel="icon" href="/favicon.ico">
</head>

<body>

  <!-- ここはheader -->
  <div class="flex-col-area">
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
        <form action="edit.php" method="post" class="CA-form" enctype="multipart/form-data">
          <?php if ($errors) : ?>
            <ul class="alert alert-danger">
              <?php foreach ($errors as $error) : ?>
                <li><?php echo $error; ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <ul>
            <li>
              <label for="name" class="label-name">User Name</label>
              <input type="name" name="name" value="<?php echo h($user['user_name']); ?>" required class="CAF-item">
            </li>
            <li>
              <label for="email" class="label-name">Mail Address</label>
              <input type="email" name="email" value="<?php echo h($user['email']); ?>" required class=" CAF-item">
            </li>
            <li>
              <label for="description" class="label-name">description</label>
              <input type="text" name="description" value="<?php echo h($user['description']); ?>" class=" CAF-item">
            </li>
            <li>
              <input type="submit" value="Update Account" class="CA-btn UD-btn">
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