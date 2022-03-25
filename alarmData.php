<?php 
session_start();
require('dateBase.php');
if(isset($_POST['alarmVal'])){
    $userId = $_SESSION['id'];
    $getAlarmNum = $mysqli -> query('SELECT SUM(alarmChange) AS sumNum FROM alarm WHERE alarmChange = 1 AND user_id = '.$userId);
    $row = $getAlarmNum -> fetch_assoc();
    if($row['sumNum'] == NULL){
        $sum = 1;
    }else{
        $sum = $row['sumNum'] + 1;
    }
    $alarmName = 'アラーム'.$sum;

    $alarmVal = $_POST['alarmVal'];
    $date = new DateTime($alarmVal);
    $dateVal = $date -> format('Y-m-d H:i:s');
    if($alarmVal == $dateVal){
        $setDate = $mysqli -> prepare('INSERT INTO alarm(date,user_id,alarmName) VALUES(?,?,?)');
        $setDate -> bind_param('sis',$alarmVal,$userId,$alarmName);
        $setDate -> execute();
        $setDate -> close();
        $id = $mysqli -> insert_id;
        $data = ['alarmVal' => $alarmVal, 'id' => $id];
        header('Content-type:application/json;charset=UTF-8');
        echo json_encode($data);
        exit;
    }
}