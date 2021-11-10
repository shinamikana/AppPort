<?php
//東京のタイムゾーンをセット
date_default_timezone_set('Asia/Tokyo');

$date = date("Y/m/d H:i:s");
//メモ処理
//memoがポストされたなら

if(isset($_POST['text'])){
    $text = $_POST['text'];
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
    $data2 = array('del' => $delId);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data2);
    $delete = $mysqli -> prepare('DELETE memo,book_memo,map_memo FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id LEFT JOIN map_memo ON memo.id = map_memo.memo_id WHERE id = ?');
    $delete -> bind_param('i',$_POST['del']);
    $delete -> execute();
    $delete -> close();
    exit;
}

if(isset($_POST['memoId']) && isset($_POST['bookId'])){
    $memoId = $_POST['memoId'];
    $bookId = $_POST['bookId'];
    $memoBook = $mysqli -> prepare('INSERT INTO book_memo(memo_id,book_id) VALUES(?,?)');
    $memoBook -> bind_param('ii',$memoId,$bookId);
    $memoBook -> execute();
    $data = array('memoId' => $memoId ,'bookId' => $bookId);
    header('Countent-type:application/json;charset=UTF-8');
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
    header('Countent-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if(isset($_POST['rMemoId'])){
    $rMemoId = $_POST['rMemoId'];
    $rMemoBook = $mysqli -> prepare('DELETE FROM book_memo WHERE memo_id = ?');
    $rMemoBook -> bind_param('i',$rMemoId);
    $rMemoBook -> execute();
    $data = array('rMemoId' => $rMemoId);
    header('Countent-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if(isset($_POST['rMemoMap'])){
    $rMemoMap = $_POST['rMemoMap'];
    $removeMemoMap = $mysqli -> prepare('DELETE FROM map_memo WHERE memo_id = ?');
    $removeMemoMap -> bind_param('i',$rMemoMap);
    $removeMemoMap -> execute();
    $data = array('rMemoMap' => $rMemoMap);
    header('Countent-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}