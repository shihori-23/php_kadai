<?php
session_start();
require('dbconnect.php');

// URLパラメーターが空だったら
if (empty($_REQUEST['id'])) {
  header('Location: index.php');
  exit();
}

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>掲示板もどき</title>
   <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/reset.css" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />

</head>

<body>
<div id="wrap">
    <div id="head">
      <h1>掲示板もどき</h1>
    </div>
    <div id="content">
    <p>&laquo;<a href="index.php">一覧にもどる</a></p>
  <?php if($post = $posts->fetch()): ?>
      <div class="msg">
      <img src="member_picture/<?php print(htmlspecialchars($post['picture'])); ?>" />
      <p><span class="name">（<?php print(htmlspecialchars($post['name'])); ?>）</span></p>
      <p class="day"><?php print(htmlspecialchars($post['created'])); ?></p>
      </div>
  <?php else: ?>
    <p>その投稿は削除されたか、URLが間違えています</p>
  <?php endif; ?>
    </div>
</div>
</body>
</html>
