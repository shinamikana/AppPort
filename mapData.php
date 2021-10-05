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
    $insertId = $mysqli -> insert_id;
    $data = array('lat' => $lat,'lng' => $lng,'insert_id' => $insertId);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
    }

    if(isset($_POST['mapDel'])){
        $mapDel = $mysqli -> prepare('DELETE FROM map WHERE id = ?');
        $mapDel -> bind_param('i',$_POST['mapDel']);
        $mapDel -> execute();
        $mapDel -> close();
        $delId = $_POST['mapDel'];
        $data = array('mapdel' => $delId);
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit;
    }

    if(isset($_POST['mapEdit']) && isset($_POST['mapEditId'])){
        $edit = $_POST['mapEdit'];
        $editId = $_POST['mapEditId'];
        $mapEdit = $mysqli -> prepare('UPDATE map set field_name = ? WHERE id = ?');
        $mapEdit -> bind_param('si',$edit,$editId);
        $mapEdit -> execute();
        $data = array('mapEdit' => $edit ,'mapEditId' => $editId);
        header('Content-type:application/json;charset=UTF-8');
        echo json_encode($data);
        exit;
    }
?>