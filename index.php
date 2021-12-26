<?php
session_start();
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}


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

    <h1 id="logo"><?php if (isset($_SESSION['username'])) : ?><span>こんにちは、<?= h($_SESSION['username']) ?>さん!</span><?php endif ?><a href="index.php">AppPort</a><a href="info.php" id="info">お問い合わせ</a></h1>

    <ul>
    </ul>
    </div>

    <div class="app">
        <div class="memo">
            <a href="memo.php">ToDo</a>
        </div>

    </div>
    <script>
        $(() => {
            $('.pass').hover(function() {
                    let passText = '';
                    let wordText = '';
                    let passInt = 0;
                    let wordInt = 0;
                    let passP = $(this).find('#pass').text().split('');
                    let wordP = $(this).find('#word').text().split('');
                    window.wordInterval = false;
                    window.passInterval = setInterval(() => {
                        passP[passInt] = '*';
                        passJoin = passP.join('');
                        $(this).find('#pass').text(passJoin);
                        passInt++;
                        if (passInt >= 4) {
                            clearInterval(passInterval);
                            window.wordInterval = setInterval(() => {
                                wordP[wordInt] = '*';
                                wordJoin = wordP.join('');
                                $(this).find('#word').text(wordJoin);
                                wordInt++;
                                if (wordInt >= 4) {
                                    clearInterval(wordInterval);
                                }
                            }, 200);
                        }
                    }, 200);
                },
                function() {
                    clearInterval(passInterval);
                    if (wordInterval) {
                        clearInterval(wordInterval);
                    }
                    $(this).find('#pass').text('PASS');
                    $(this).find('#word').text('WORD');
                });

            $('.fa-map-pin').hide();

            $('#mapA').hover(function() {
                    $(this).find('p').hide();
                    $(this).find('img').show();
                    $('#mapImg').addClass('mapAnime');
                },
                function() {
                    $(this).find('p').show();
                    $(this).find('img').hide();
                    $('#mapImg').removeClass('mapAnime');
                    $('.fa-map-pin').hide();
                });

            $('#mapImg').on('animationend', function() {
                $('.fa-map-pin').show();
            });
        });
    </script>
</body>

</html>
