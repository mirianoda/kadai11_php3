<?php
//PHP:コード記述/修正の流れ
//1. insert.phpの処理をマルっとコピー。
//   POSTデータ受信 → DB接続 → SQL実行 → 前ページへ戻る
//2. $id = POST["id"]を追加
//3. SQL修正
//   "UPDATE テーブル名 SET 変更したいカラムを並べる WHERE 条件"
//   bindValueにも「id」の項目を追加
//4. header関数"Location"を「select.php」に変更

//1. POSTデータ取得
$name      = $_POST["name"];
$gender    = $_POST["gender"];
$age       = $_POST["age"];
$book_name = $_POST["book_name"];
$book_id   = $_POST["book_id"]; // 隠しフィールドから受け取る
$comment   = $_POST["comment"];
$id        = $_POST["id"];


//2. DB接続します
include("funcs.php");
$pdo = db_conn();


//３．データ登録SQL作成
$sql = "UPDATE gs_bm_table SET name=:name, gender=:gender, age=:age, book_name=:book_name, book_id=:book_id, comment=:comment WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name',      $name,      PDO::PARAM_STR);
$stmt->bindValue(':gender',    $gender,    PDO::PARAM_STR);
$stmt->bindValue(':age',       $age,       PDO::PARAM_STR);
$stmt->bindValue(':book_name', $book_name, PDO::PARAM_STR);
$stmt->bindValue(':book_id',   $book_id,   PDO::PARAM_STR);
$stmt->bindValue(':comment',   $comment,   PDO::PARAM_STR);
$stmt->bindValue(':id',        $id,        PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); // 実行


//４．データ登録処理後
if($status==false){
    sql_error($stmt);
}else{
    redirect("home.html");
}