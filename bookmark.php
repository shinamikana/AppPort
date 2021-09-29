<?php
include('dateBase.php');
include('memoData.php');
include('bookmarkData.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/memoIndex.css">
    
    <title>ブックマーク</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti:wght@700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <main>
    <?php include('bookmarkIndex.php') ?>
    <?php include('memoIndex.php'); ?>

    </main>

    <script>
        //ブックマークのドラッグ＆ドロップ処理
        $(()=>{
            $('.bookUl').sortable({
                connectWith:'.bookUl',
                placeholder:'memoDiv'
            });
        });
    </script>
</body>
</html>