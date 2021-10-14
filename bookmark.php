<?php
include('dateBase.php');
include('memoData.php');
include('bookmarkData.php');

if(empty($_SESSION['username']) ){
    header('Location:login.php');
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
    
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
                connectWith:'.dragUl',
                placeholder:'memoDiv'
            });

            $('.dragUl').sortable({
                connectWith:'.bookUl',
                placeholder:'memoDiv'
            });

            $('.bookUl').disableSelection();
        });
    </script>
</body>
</html>