<?php
    include('dateBase.php');
    include('memoData.php'); 
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
    <title>MEMO</title>
    <link rel="stylesheet" href="/css/memo.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <?php include('memoAppIndex.php'); ?>

    
<script>
    document.getElementById('byte').innerText = '0/500';
        const byteCount = function(){
            const memoByte = document.getElementById('text').value;
            let byte = (new Blob([memoByte])).size;
            document.getElementById('byte').innerText = `${byte}/500`;
            if(byte > 500){
                document.getElementById('byte').innerText = '文字数オーバーです';
            }
        }

        $(()=>{
            $('#memoWrapper').sortable({

            });
        });
</script>
</body>
</html>