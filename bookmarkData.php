<?php

$show = $mysqli -> prepare('SELECT * FROM bookmark WHERE user_id = ? ORDER BY id DESC');
$show -> bind_param('i',$_SESSION['id']);
$show -> execute();
$showResult = $show -> get_result();

    if(isset($_POST['url']) && isset($_POST['linkName'])){
        $mark = $mysqli -> prepare('INSERT INTO bookmark(link,user_id,link_name) VALUES(?,?,?)');
        $mark -> bind_param('sis',$_POST['url'],$_SESSION['id'],$_POST['linkName']);
        $mark -> execute();
        $mark -> close();
        $url = $_POST['url'];
        $linkName = $_POST['linkName'];
        $id = $mysqli -> insert_id;
        $data = array('url' => $url , 'linkName' => $linkName , 'id' => $id);
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit;
    }

    if(isset($_POST['delId'])){
        $delete = $mysqli -> prepare('DELETE FROM bookmark WHERE id = ?');
        $delete -> bind_param('i',$_POST['delId']);
        $delete -> execute();
        $delete -> close();
        $delId = $_POST['delId'];
        $data = array('delId' => $delId);
        header("Content-type:application/json;charset=UTF-8");
        echo json_encode($data);
        exit;
    }
?>