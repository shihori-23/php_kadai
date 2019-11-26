<?php
    try {
         $db = new PDO('mysql:dbname=shihori23_gs_db;charset=utf8;host=mysql57.shihori23.sakura.ne.jp','shihori23','Rsk010523');
     //     $db = new PDO('mysql:dbname=mini_bbs;charset=utf8;host=localhost','root','root');
    // $pdo = new PDO('mysql:dbname=gs_db3;charset=utf8;host=localhost','root','root'); //本番用
    // return $pdo; 2種類目
  } catch (PDOException $e) {
    exit('DB Connection Error:'.$e->getMessage());
  }
?>