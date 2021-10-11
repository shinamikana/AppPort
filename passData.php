<?php
$passShow = $mysqli -> prepare('SELECT * FROM pass WHERE user_id = ?');
$passShow -> bind_param('i',$_SESSION['id']);
$passShow -> execute();
$passResult = $passShow -> get_result();

if(isset($_POST['pass']) && isset($_POST['passTitle'])){
    $passKeep = $mysqli -> prepare('INSERT INTO pass(pass,user_id,passName) VALUES(?,?,?)');
    $passKeep -> bind_param('sis',$_POST['pass'],$_SESSION['id'],$_POST['passTitle']);
    $passKeep -> execute();
    header('Content-type:aplication/json;charset=UTF-8');
    $passPost = $_POST['pass'];
    $passTitle = $_POST['passTitle'];
    $data = array('pass' => $passPost,'passTitle' => $passTitle);
    echo json_encode($data);
    exit;
    
}

if(isset($_POST['passUp']) && isset($_POST['passId'])){
    $passUp = $_POST['passUp'];
    $passId = $_POST['passId'];
    $passNameUp = $mysqli -> prepare('UPDATE pass SET passName = ? WHERE id = ?');
    $passNameUp -> bind_param('si',$passUp,$passId);
    $passNameUp -> execute();
}

?>