<?php
//東京のタイムゾーンをセット
date_default_timezone_set('Asia/Tokyo');

$date = date("Y/m/d H:i:s");
//メモ処理
//memoがポストされたなら

if(isset($_POST['text'])){
  $text = trim($_POST['text']);
  $textLen = mb_strlen($text);
}
if(isset($text) && !empty($text) && $textLen != 0){
    $memoPost = $mysqli -> prepare('INSERT INTO memo(text,date,user_id) VALUES(?,?,?)');
    $memoPost -> bind_param('ssi',$text,$date,$_SESSION['id']);
    $memoPost -> execute();
    $memoPost -> close();
    $insert = $mysqli -> insert_id;
    $data = array('text' => $text,'insert' => $insert,'date' => $date);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
}

//メモ削除
if(isset($_POST['del'])){
    $delId = $_POST['del'];
    $delete = $mysqli -> prepare('DELETE memo,book_memo,map_memo FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id LEFT JOIN map_memo ON memo.id = map_memo.memo_id WHERE memo.id = ?');
    $delete -> bind_param('i',$_POST['del']);
    $delete -> execute();
    $delete -> close();
    $data = array('del' => $delId);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
}

if(isset($_POST['memoId']) && isset($_POST['bookId'])){
    $memoId = $_POST['memoId'];
    $bookId = $_POST['bookId'];
    $memoBook = $mysqli -> prepare('INSERT INTO book_memo(memo_id,book_id) VALUES(?,?)');
    $memoBook -> bind_param('ii',$memoId,$bookId);
    $memoBook -> execute();
    $data = array('memoId' => $memoId ,'bookId' => $bookId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if(isset($_POST['memoId']) && isset($_POST['mapId'])){
    $memoId = $_POST['memoId'];
    $mapId = $_POST['mapId'];
    $memoMap = $mysqli -> prepare('INSERT INTO map_memo(memo_id,map_id) VALUES(?,?)');
    $memoMap -> bind_param('ii',$memoId,$mapId);
    $memoMap -> execute();
    $data = array('memoId' => $memoId,'mapId' => $mapId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if(isset($_POST['rBookId'])){
    $rBookId = $_POST['rBookId'];
    $rMemoBook = $mysqli -> prepare('DELETE FROM book_memo WHERE book_id = ?');
    $rMemoBook -> bind_param('i',$rBookId);
    $rMemoBook -> execute();
    $data = array('rBookId' => $rBookId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if(isset($_POST['rMapId'])){
    $rMapId = $_POST['rMapId'];
    $removeMemoMap = $mysqli -> prepare('DELETE FROM map_memo WHERE map_id = ?');
    $removeMemoMap -> bind_param('i',$rMapId);
    $removeMemoMap -> execute();
    $data = array('rMapId' => $rMapId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if(isset($_POST['flagId'])){
  $flagId = $_POST['flagId'];
  $changeFlag = $mysqli -> prepare('UPDATE memo SET showFlag = 0 WHERE id = ?');
  $changeFlag -> bind_param('i',$flagId);
  $changeFlag -> execute();
  $data = array('flagId' => $flagId);
  header('Content-type:application/json;charset=UTF-8');
  echo json_encode($data);
  exit;
}
