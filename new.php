<?php

require_once('config.php');
require_once('functions.php');

session_start();

$dbh = connectDb();
// ユーザー情報の取得
$sql = "SELECT* FROM users WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// カテゴリーの取得
$sql = 'SELECT * FROM categories ORDER BY id';
$stmt = $dbh->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 性別の取得
$sql = 'SELECT * FROM gender ORDER BY id';
$stmt = $dbh->prepare($sql);
$stmt->execute();
$gender = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $category_id = $_POST['category_id'];
  $gender_id = $_POST['gender_id'];
  $desceiption = $_POST['desceiption'];
  $image = $_FILES['item']['name'];
  $user_id = $_SESSION['id'];

  $errors = [];

  if ($category_id == '') {
    $errors[] = 'カテゴリーを選択してください。';
  }
  if ($gender_id == '') {
    $errors[] = '性別を選択してください。';
  }

  if ($image) {
    $ext = substr($image, -3);
    if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif' && $ext != 'JPG') {
      $errors[] = '画像ファイルは jpg png gif のいずれかを選択してください。';
    }
  }

  if (empty($errors)) {
    $postItem = date('YmdHis') . $image;
    move_uploaded_file($_FILES['item']['tmp_name'], 'items/' . $postItem);
    $_SESSION['join']['item'] = $postItem;

    $sql = <<<SQL
    INSERT INTO
    items
    (
      user_id,
      category_id,
      gender_id,
      photo,
      desceiption
    )
      VALUES
    (
      :user_id,
      :category_id,
      :gender_id,
      :postItem,
      :desceiption
    )
    SQL;
    $stmt = $dbh->prepare($sql);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':gender_id', $gender_id, PDO::PARAM_INT);
    $stmt->bindParam(':postItem', $postItem, PDO::PARAM_STR);
    $stmt->bindParam(':desceiption', $desceiption, PDO::PARAM_STR);

    $stmt->execute();

    header('location: profile.php');
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
    <div class="container">
      <div class="row">
        <div class="col-sm-11 col-md-9 col-lg-7 mx-auto">
          <div class="card my-5">
            <div class="card-body">
              <?php if ($errors) : ?>
                <ul class="alert alert-danger">
                  <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error; ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <form action="new.php" method="post" enctype="multipart/form-data">
                <div id='boxImage' class="new-item-font">Sample Image</div>
                <hr>
                <input type='file' id='selectImage' name="item" class="new-item-font" required>
                <div class="form-group">
                  <label for="category_id">カテゴリー</label>
                  <select name="category_id" class="form-control" required>
                    <option value='' disabled selected>選択してください</option>
                    <?php foreach ($categories as $c) : ?>
                      <option value="<?php echo h($c['id']); ?>"><?php echo h($c['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                  <label for="gender_id">性別</label>
                  <select name="gender_id" class="form-control" required>
                    <option value='' disabled selected>選択してください</option>
                    <?php foreach ($gender as $g) : ?>
                      <option value="<?php echo h($g['id']); ?>"><?php echo h($g['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div>
                  <label for="desceiption" class="new-item-font">コメント</label>
                  <textarea name="desceiption" cols="30" rows="5" class="form-control" required></textarea>
                </div>
                <input type="submit" value="New Post" class="NP-btn">
            </div>
            </form>
          </div>
        </div>
      </div>
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
        document.getElementById('boxImage').innerHTML = '<img src="' + fr.result + '" alt="" style="min-width:300px;min-height:500px;max-width:300px;max-height:500px;">'; //readAsDataURLで得た結果を、srcに入れたimg要素を生成して挿入
      }
    }
  }
</script>

</html>