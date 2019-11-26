<?php
session_start();
require('dbconnect.php');

//最後のアクションから１時間ログイン状態を維持する
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();

} else {
  // print ("ダメだよ");
  header('Location: login.php');
  exit();
}


// 投稿の内容をDBに保存
// 投稿するボタンがクリックされた時
if (!empty($_POST)) {
  if ($_POST['message'] !== ''){
    // var_dump("メッセージ おくれたよ！");
    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['message'],
      $_POST['reply_post_id']
    ));
  }
    header('Location: index.php');
    exit();
  // }
}

// DBのテーブル同士の接続と投稿内容の出力準備
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');


// REをクリックした場合
if (isset($_REQUEST['res'])){
// 返信の処理
$response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
$response->execute(array($_REQUEST['res']));

$table =$response->fetch();
$message = '@' . $table['name'] . ' ' .$table['message'];

}

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
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    

</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>掲示板もどき</h1>
  </div>
  <div id="content">
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post">
      <dl>
        <dt><?php print(htmlspecialchars($member['name'],ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" cols="50" rows="5"><?php print(htmlspecialchars($message,ENT_QUOTES)); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'],ENT_QUOTES)); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" class="btn btn-default form_btn"/>
        </p>
      </div>
    </form>
  <?php foreach ($posts as $post): ?>
    <div class="msg">
    <img src="member_picture/<?php print(htmlspecialchars($post['picture'],ENT_QUOTES)); ?>" width="48" height="48" alt="" />
    <p><?php print(htmlspecialchars($post['message'],ENT_QUOTES)); ?>
    <span class="name">（<?php print(htmlspecialchars($post['name'],ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'],ENT_QUOTES)); ?>">Re</a>]</p>
    <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'],ENT_QUOTES)); ?></a>
      
      <?php if ($post['reply_message_id'] > 0): ?>
      <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'],ENT_QUOTES)); ?>">
      返信元のメッセージ</a>
      <?php endif; ?>

      <?php if ($_SESSION['id'] == $post['member_id']): ?>
        [<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>"
        style="color: #F33;">削除</a>]
      <?php endif; ?>
    </p>
    </div>
    <?php endforeach; ?>

<!-- <ul class="paging">
<li><a href="index.php?page=">前のページへ</a></li>
<li><a href="index.php?page=">次のページへ</a></li>
</ul> -->
  </div>
</div>
</body>
</html>
