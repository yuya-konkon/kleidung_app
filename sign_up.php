<?php

require_once('config.php');
require_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user_name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $image = $_FILES['image']['name'];


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

  $dbh = connectDb();
  $sql = 'SELECT * FROM users WHERE email = :email';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $errors[] = '既にメールアドレスが登録されています';
  }

  if ($image) {
    $ext = substr($image, -3);
    if ($ext != 'jpg' && $ext != 'png' && $ext != 'heic') {
      $errors[] = '画像ファイルは jpg png heic のいずれかを選択してください。';
    }
  }

  if (empty($errors)) {
    $profileImage = date('YmdHis') . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], 'user_image/' . $profileImage);
    $_SESSION['join']['image'] = $profileImage;

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
      :profileImage
    )
    SQL;
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    $pw_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bindParam(':password', $pw_hash);
    $stmt->bindParam(':profileImage', $profileImage, PDO::PARAM_STR);

    $stmt->execute();

    header('Location: login.php');
    exit;
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
        <form action="sign_up.php" method="post" class="CA-form" enctype="multipart/form-data">
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
              <input type="name" name="name" placeholder="User Name を入力してください" required class="CAF-item">
            </li>
            <li>
              <label for="email" class="label-name">Mail Address</label>
              <input type="email" name="email" placeholder="Mail Address を入力してください" required class="CAF-item">
            </li>
            <li>
              <label for="password" class="label-name">Password</label>
              <input type="password" name="password" placeholder="Password を入力してください" required class="CAF-item">
            </li>
            <li>
              <div id='boxImage' class="new-item-font">Sample Image</div>
              <hr>
              <label for="selectImage" class="label-name">
                Select Profile
                <input type='file' id='selectImage' name="image" class="new-input-file" required>
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

<script>
  var elm = document.getElementById("selectImage");
  elm.onchange = function(evt) {
    var selectFiles = evt.target.files;
    if (selectFiles.length != 0) {
      var fr = new FileReader();
      fr.readAsDataURL(selectFiles[0]);
      fr.onload = function(evt) {
        document.getElementById('boxImage').innerHTML = '<img src="' + fr.result + '" alt="" style="min-width:100px;min-height:100px;max-width:300px;max-height:500px;">'; //readAsDataURLで得た結果を、srcに入れたimg要素を生成して挿入
      }
    }
  }
</script>

</html>