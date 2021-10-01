<?php

    $showMark = $mysqli -> prepare('SELECT * FROM map WHERE user_id = ?');
    $showMark -> bind_param('i',$_SESSION['id']);
    $showMark -> execute();
    $resultMark = $showMark -> get_result();


    if(isset($_POST['lat']) && isset($_POST['lng'])){
    $mark = $mysqli -> prepare('INSERT INTO map(lat,lng,user_id,field_name) VALUES(?,?,?,"登録した地点")');
    $mark -> bind_param('ssi',$_POST['lat'],$_POST['lng'],$_SESSION['id']);
    $mark -> execute();
    $mark -> close();
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $data = array('lat' => $lat,'lng' => $lng);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
    }
?>