<?php

//エラー表示
ini_set("display_errors", 1);
//１．PHP
//select.phpのPHPコードをマルっとコピーしてきます。
//※SQLとデータ取得の箇所を修正します。
include("funcs.php");
$pdo = db_conn();

//２．データ登録SQL作成
$sql = "SELECT * FROM gs_bm_table WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_GET["id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
    sql_error($stmt);
  }
  
//全データ取得
$v =  $stmt->fetch(); //1行だけ取る！（一番上の行）

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <script src="js/jquery-2.1.3.min.js"></script>
    <link rel="stylesheet" href="css/detail.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Tint&family=Cinzel+Decorative:wght@400;700;900&family=Cinzel:wght@400..900&family=Dongle:wght@300;400;700&family=Galada&family=Montserrat+Alternates:wght@100;200;300;400&family=Poiret+One&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Zen+Kaku+Gothic+New:wght@300;400;500;700&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
  </head>

  <body>
    <header>
      <h1 class="logo">OUR BOOKSHELF</h1>
      <img src="img/本アイコン.png" alt="" class="home">
    </header>

  <div class="form-wrapper">
    <div class="form1-msg">
      <h2>投稿内容を編集</h2>
      <p>変更内容を入力し、更新ボタンを押してください</p>
    </div>

    <form action="update.php" method="POST">
      <div class="form1">
        <div class="form">
          <p>投稿者名</p>
          <input type="text" name="name" value="<?=$v["name"]?>">
        </div>
        <div class="form">
          <p>年代</p>
          <select name="age">
            <option value="10代" <?= $v["age"] === "10代" ? "selected" : "" ?>>10代</option>
            <option value="20〜30代" <?= $v["age"] === "20〜30代" ? "selected" : "" ?>>20〜30代</option>
            <option value="40〜50代" <?= $v["age"] === "40〜50代" ? "selected" : "" ?>>40〜50代</option>
            <option value="60代以上" <?= $v["age"] === "60代以上" ? "selected" : "" ?>>60代以上</option>
            <option value="非公開" <?= $v["age"] === "年齢非公開" ? "selected" : "" ?>>年齢非公開</option>
          </select>
        </div>
        <div class="form">
          <p>性別</p>
          <select name="gender">
            <option <?= $v["gender"] === "女性" ? "selected" : "" ?>>女性</option>
            <option <?= $v["gender"] === "男性" ? "selected" : "" ?>>男性</option>
            <option <?= $v["gender"] === "性別非公開" ? "selected" : "" ?>>性別非公開</option>
          </select>
        </div>
       <div class="form">
         <p>おすすめの本</p>
         <input type="text" class="book_name" name="book_name" placeholder="本のタイトルを入力し、下の候補から選んでください" value="<?=$v["book_name"]?>">
         <div id="book-dropdown" class="dropdown" style="display:none;"></div>
         <input type="hidden" id="bookId" name="book_id" value="<?=$v["book_id"]?>"> <!-- 隠しフィールド -->
       </div>
       <div class="form">
         <p>おすすめコメント</p>
         <textarea name="comment"><?=$v["comment"]?></textarea>
       </div>

       <input type="submit" value="更新" class="submit">
       <input type="hidden" name="id" value="<?=$v["id"]?>">
      </div>
    </form>
  </div>

    <script src="index.js"></script>
    <script>
      $(".home").on("click",function(){
        window.location.href = "home.html";
      });
    </script>
  </body>
</html>
