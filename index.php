<?php

require_once('config.php');
require_once('functions.php');

session_start();

$dbh = connectDB();
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

// アイテムの取得
$category_id = $_GET['category_id'];
$gender_id = $_GET['gender_id'];

if (isset($_SESSION['id'])) {
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
  SQL;
} else {
  $sql = <<<SQL
  SELECT
    i.*,
    u.user_name,
    u.image
  FROM
    items i
  LEFT JOIN
    users u
  ON
    i.user_id = u.id
  SQL;
}

if (($category_id) && is_numeric($category_id)) {
  $sql_where = ' WHERE i.category_id = :category_id';
} else {
  $sql_where = "";
}

if (($gender_id) && is_numeric($gender_id)) {
  $sql_gender = ' WHERE i.gender_id = :gender_id';
} else {
  $sql_gender = "";
}

$sql_order = ' ORDER BY i.created_at DESC';

$sql = $sql . $sql_where . $sql_gender . $sql_order;
$stmt = $dbh->prepare($sql);

if (isset($_SESSION['id'])) {
  $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
}
if (($category_id) && is_numeric($category_id)) {
  $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
}
if (($gender_id) && is_numeric($gender_id)) {
  $stmt->bindParam(':gender_id', $gender_id, PDO::PARAM_INT);
}
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
</head>

<body>

  <!-- ここはheader -->
  <div class="flex-col-area">
    <nav class="navbar navbar-expand-lg navbar-dark mb-3 header">
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
    <!-- 性別選択 -->
    <div class="gender-bar">
      <ul>
        <li><a href="index.php" class="gender">ALL</a></li>
        <?php foreach ($gender as $g) : ?>
          <li>
            <a href="index.php?gender_id=<?php echo h($g['id']); ?>" class="gender"><?php echo h($g['name']); ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <hr class="gender-border">
    </div>
    <!-- カテゴリー選択 -->
    <div class="row">
      <div class="col-md-4 mt-5 category">
        <ul>
          <?php foreach ($categories as $c) : ?>
            <li class="category-item">
              <a href="index.php?category_id=<?php echo h($c['id']); ?>"><?php echo h($c['name']); ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- アイテム表示 -->
      <div class="col-md-8 d-none d-md-block mt-5 mb-3 item-box">
        <div class="row">
          <?php foreach ($items as $item) : ?>
            <div class="main-item mt-2">
              <img src="items/<?php echo h($item['photo']); ?>" class="flex-item item-image" alt="image" data-toggle="modal" data-target="#show-article<?php echo ($item['id']); ?> ">
              <div class="item-ov">
                <div class="user-image"><img src="user_image/<?php echo h($item['image']); ?>" alt="image">
                </div>
                <div class="item-text">
                  <a href="profile.php?=<?php echo h($user['id']); ?>">
                    <p class="item-user-name"><?php echo h($item['user_name']); ?></p>
                  </a>
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
                    <p><?php echo nl2br(h($item['desceiption'])); ?></p>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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

</body>

</html>