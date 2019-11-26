<?php
// 関数の呼び込み
session_start();
require('dbconnect.php');

// 初めて画面を読み出された時にもCOOKIEの中身を確認
if($_COOKIE['email'] !==''){
 $email = $_COOKIE['email'];
}

if (!empty($_POST)) {
  // メールアドレスの更新があった場合、変更する
  $email = $_POST['email'];
  if ($_POST['email'] !== '' && $_POST['password'] !== '' ){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?'); 
    $login->execute(array(
      $_POST['email'],
      // DBに保管された暗号化されたパスワードに合わせて暗号化して照合する
      sha1($_POST['password'])
    
    ));
    $member = $login->fetch();

    if ($member){
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();

      // COOKIEにログイン情報を保存する（14日間）
      if($_POST['save'] === 'on') {
        setcookie('email',$_POST['email'],time()+60*60*24*14);
      }

      header('Location: index.php');
      exit();

    } else {
      $error['login'] = 'failed';
    }
  } else {
      $error['login'] = 'blank';
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ログインする</title>
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
    <h1>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>メールアドレスとパスワードを記入してログインしてください。</p>
      <p>入会手続きがまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="join/">アカウントを作成する</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php print (htmlspecialchars($email,ENT_QUOTES)); ?>" />
          <?php if ($error['login'] === 'blank'): ?>
          <p class ="error">メールアドレスとパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if ($error['login'] === 'failed'): ?>
          <p class ="error">ログインに失敗しました。正しくご記入ください</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php print (htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>" />
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" class="btn btn-default form_btn"/>
      </div>
    </form>
  </div>
  <div id="foot">
   
  </div>
</div>
</body>
</html>
