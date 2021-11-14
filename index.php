<?php
    session_start();
    //$memo = $mysqli -> prepare('INSERT INTO(memo,date,user_id) VALUE(?,?,?)');



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppPort</title>
    <link rel="stylesheet" href="/css/top.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
</head>
<body>
    <?php include('sidebar.php'); ?>

    <h1 id="logo"><?php if(isset($_SESSION['username'])): ?><span>こんにちは、<?php echo $_SESSION['username'] ?>!</span><?php endif ?><a href="index.php">AppPort</a><a href="info.php" id="info">お問い合わせ</a></h1>
    
        <ul>
        </ul>
    </div>
    
    <div class="app">

        <div class="pass">
            <a href="pass.php">PASS
                WORD</a>
        </div>

        <div class="memo">
            <a href="memo.php">MEMO</a>
        </div>

        <div class="bookmark">
            <a href="bookmark.php">BOOK
                MARK</a>
        </div>

        <div class="map">
            <a href="map.php" id="mapA">MAP</a>
            <a href="map.php" id="mapImgA"><i class="fas fa-map-pin"></i><img src="/img/map.png" alt="" height="150px" width="150px" id="mapImg"></a>
        </div>

    </div>
    <script>
        $(()=>{

            $('#mapA').hover(function(){
                $(this).hide();
                $('#mapImgA').show();
                $('#mapImg').addClass('mapAnime');
            });

            $('#mapImg').on('animationend',function(){
                $('.fa-map-pin').show();
            });

        });
    </script>
</body>
</html>