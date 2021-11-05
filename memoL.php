<?php
    include('dateBase.php');
    include('memoData.php'); 
    if(empty($_SESSION['username']) ){
        header('Location:login.php');
    }

    function h($str){
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEMO</title>
    <link rel="stylesheet" href="/css/memoAppIndex.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <?php include('memoIndexL.php'); ?>
</body>
</html>