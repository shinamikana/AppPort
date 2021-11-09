<?php

$showMark = $mysqli->prepare('SELECT *,map.id AS mapId FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id LEFT JOIN map_bookmark ON map.id = map_bookmark.map_id WHERE user_id = ? AND map_memo.id IS NULL ORDER BY mapId DESC');
$showMark->bind_param('i', $_SESSION['id']);
$showMark->execute();
$resultMark = $showMark->get_result();
$mapCount = 0;
while ($resultMark->fetch_assoc()) {
    ++$mapCount;
}



if (isset($_POST['lat']) && isset($_POST['lng'])) {
    $mark = $mysqli->prepare('INSERT INTO map(lat,lng,user_id,field_name) VALUES(?,?,?,"登録した地点")');
    $mark->bind_param('ssi', $_POST['lat'], $_POST['lng'], $_SESSION['id']);
    $mark->execute();
    $mark->close();
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $insertId = $mysqli->insert_id;
    $data = array('lat' => $lat, 'lng' => $lng, 'insert_id' => $insertId);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
}

if (isset($_POST['mapDel'])) {
    $mapDel = $mysqli->prepare('DELETE map,map_memo,map_bookmark FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id LEFT JOIN map_bookmark ON map.id = map_bookmark.map_id WHERE map.id = ?');
    $mapDel->bind_param('i', $_POST['mapDel']);
    $mapDel->execute();
    $mapDel->close();
    $delId = $_POST['mapDel'];
    $data = array('mapdel' => $delId);
    header("Content-type: application/json; charset=UTF-8");
    echo json_encode($data);
    exit;
}

if (isset($_POST['mapEdit']) && isset($_POST['mapEditId'])) {
    $edit = $_POST['mapEdit'];
    $editId = $_POST['mapEditId'];
    $mapEdit = $mysqli->prepare('UPDATE map set field_name = ? WHERE id = ?');
    $mapEdit->bind_param('si', $edit, $editId);
    $mapEdit->execute();
    $mapEdit->close();
    $data = array('mapEdit' => $edit, 'mapEditId' => $editId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if (isset($_POST['mapVal']) && isset($_POST['memoVal'])) {
    $mapVal = $_POST['mapVal'];
    $memoVal = $_POST['memoVal'];
    $mapMemo = $mysqli->prepare('INSERT INTO map_memo(map_id,memo_id) VALUES(?,?)');
    $mapMemo->bind_param('ii', $mapVal, $memoVal);
    $mapMemo->execute();
    $mapMemo->close();
    $data = array('mapVal' => $mapVal, 'memoVal' => $memoVal);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if (isset($_POST['mapVal']) && isset($_POST['bookVal'])) {
    $mapVal = $_POST['mapVal'];
    $bookVal = $_POST['bookVal'];
    $mapBook = $mysqli->prepare('INSERT INTO map_bookmark(map_id,book_id) VALUES(?,?)');
    $mapBook->bind_param('ii', $mapVal, $bookVal);
    $mapBook->execute();
    $mapBook->close();
    $data = array('mapVal' => $mapVal, 'bookVal' => $bookVal);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if (isset($_POST['mapId'])) {
    $mapId = $_POST['mapId'];
    $mapMemoDel = $mysqli->prepare('DELETE FROM map_memo WHERE map_id = ?');
    $mapMemoDel->bind_param('i', $mapId);
    $mapMemoDel->execute();
    $mapMemoDel->close();
    $data = array('mapId' => $mapId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}

if (isset($_POST['mapBookId'])) {
    $mapBookId = $_POST['mapBookId'];
    $mapBookDel = $mysqli->prepare('DELETE FROM map_bookmark WHERE map_id = ?');
    $mapBookDel->bind_param('i', $mapBookId);
    $mapBookDel->execute();
    $mapBookDel->close();
    $data = array('mapBookId' => $mapBookId);
    header('Content-type:application/json;charset=UTF-8');
    echo json_encode($data);
    exit;
}
