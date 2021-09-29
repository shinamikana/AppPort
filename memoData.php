<?php
function h($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

$showMemo = $mysqli -> prepare('SELECT * FROM memo WHERE user_id = ? ORDER BY id DESC');
$showMemo -> bind_param('i',$_SESSION['id']);
$showMemo -> execute();
$memoResult = $showMemo -> get_result();
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
    $data = array('text' => $text,'insert' => $insert);
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
    $delete = $mysqli -> prepare('DELETE FROM memo WHERE id = ?');
    $delete -> bind_param('i',$_POST['del']);
    $delete -> execute();
    $delete -> close();
    exit;
}

?>