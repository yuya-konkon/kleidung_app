<?php

require_once('config.php');
require_once('functions.php');

session_start();

if (empty($_SESSION['id'])) {
  header('Location: login.php');
  exit;
}

// ユーザー情報の取得
$dbh = connectDB();
$sql = "SELECT* FROM users WHERE id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = <<<SQL
SELECT
  i.*,
  u.user_name,
  u.image,
  f.id as favorite_id
FROM
  items i
LEFT JOIN
  users u
ON
  i.user_id = u.id
LEFT JOIN
  favorites f
ON
  i.id = f.item_id
AND
  f.user_id = :user_id
ORDER BY
  i.created_at desc;
SQL;

$stmt = $dbh->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <script src="https://kit.fontawesome.com/3ae6904195.js" crossorigin="anonymous"></script>
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
        <!-- 左側 -->
        <div class="col-md-4 d-none d-md-block">
          <div class="profile-image">
            <img src="user_image/<?php echo h($user['image'], ENT_QUOTES); ?>" alt="<?php echo h($user['name'], ENT_QUOTES); ?>" data-toggle="modal" data-target="#pf-image">
          </div>
          <div class="user-name">
            <?php echo h($user['user_name']); ?>
          </div>
          <div class="profile-description">
            <?php echo h($user['description']); ?>
          </div>
          <div class="edit">
            <a href="edit.php" class="edit-btn">Edit Profile</a>
          </div>
          <div class="logout">
            <a href="logout.php" class="logout-btn">Logout</a>
          </div>
        </div>
        <!-- 右側 -->
        <div class="col-md-8">
          <ul class="change-item">
            <li>
              <a href="profile.php" class="ci-btn btn">My Post</a>
            </li>
            <li>
              <a href="profile_fav.php" class="ci-ttn btn">Favorite</a>
            </li>
          </ul>
          <div class="row">
            <?php foreach ($items as $item) : ?>
              <?php if ($_SESSION['id'] == $item['user_id']) : ?>
                <div class="main-item">
                  <img src="items/<?php echo h($item['photo']); ?>" class="flex-item item-image" alt="image" data-toggle="modal" data-target="#show-article<?php echo ($item['id']); ?> ">
                  <div class="item-ov">
                    <div class="item-text">
                      <p class="item-date">
                        <?php echo date('y/m/d', strtotime(h($item['created_at']))); ?>
                      </p>
                      <p>
                        <?php if ($_SESSION['id']) : ?>
                          <?php if ($item['favorite_id']) : ?>
                            <a href="good_delete.php?id=<?php echo h($item['favorite_id']); ?>" class="flex-item good">♥</a>
                          <?php else : ?>
                            <a href="good.php?id=<?php echo h($item['id']); ?>" class="nomal-good">♡</a>
                          <?php endif; ?>
                        <?php else : ?>
                        <?php endif; ?>
                        <p><?php echo h($item['desceiption']); ?></p>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="modal fade" id="show-article<?php echo ($item['id']); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header modal-image">
                        <img src="items/<?php echo h($item['photo']); ?>" class="flex-item show-photo" alt="image">
                      </div>
                      </button>
                      <div class="modal-body">
                        <div class="user-image"><img src="user_image/<?php echo h($item['image']); ?>" alt="image"></div>
                        <p class="item-user-name"><?php echo h($item['user_name']); ?></p>
                        <p class="item-date">
                          <?php echo date('y/m/d', strtotime(h($item['created_at']))); ?>
                        </p>
                        <p><?php echo h($item['desceiption']); ?></p>
                        <div class="modal-footer">
                          <a href="delete.php?id=<?= h($item['id']) ?>" class="btn btn-warning">削除</a>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <?php endif; ?>
            <?php endforeach; ?>
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


  <div class="modal fade" id="pf-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        </button>
        <div class="modal-body">
          <div class="user-image">
            <img src="user_image/<?php echo h($user['image']); ?>" alt="image">
          </div>
          <form action="pf_edit.php" method="post" enctype="multipart/form-data">
            <div id='boxImage' class="new-item-font">Sample Image</div>
            <hr>
            <label for="selectImage" class="new-item-btn">
              Item Select
              <input type='file' id='selectImage' name="image" class="new-input-file" required>
            </label>
            <div class="modal-footer">
              <input type="submit" value="Edit Image" class="btn ei-btn">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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
        document.getElementById('boxImage').innerHTML = '<img src="' + fr.result + '" alt="" style="min-width:100px;min-height:100px;max-width:150px;max-height:500px;">'; //readAsDataURLで得た結果を、srcに入れたimg要素を生成して挿入
      }
    }
  }
</script>

</html>