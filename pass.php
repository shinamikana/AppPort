<?php
include('dateBase.php');
session_regenerate_id(TRUE);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/pass.css">
    <title>パスワードアプリ</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <div class="passWrapper">
        <h1>パスワード生成</h1>
        <input type="text" id="genPass">
        <button id="passBtn">生成</button>
        <button id="passKeep">保存</button>
        <div class="pass">
        </div>
    </div>
    

    <script>
        let str = 'abcdefghijklmnopqrstuvxwyz';
        let num = '1234567890';
        let sym = '!#$%&?()';
        let string = str + str.toUpperCase() + num + sym;
        let len = 12;
        let pass = '';
        let passBtn = document.getElementById('passBtn');
        let genPass = document.getElementById('genPass');
        passBtn.addEventListener('click',function(){
            genPass.value = '';
            pass = '';
            for(let i=0;i < len;i++){
                pass += string.charAt(Math.floor(Math.random() * string.length));
                console.log(i);
                if(i < len){
                genPass.value = pass;
                }
            }
        });


        $(()=>{
            $('#passKeep').on('click',function(){
                let passValue = $('#genPass').val();
                $.ajax({
                    type:'POST',
                    url:'pass.php',
                    data:{'pass':passValue},
                    dataType:'json',
                }).done(function(data){
                    alert('done');
                }).fail(function(HMLHttpRequest,status,e){
                    console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                    alert('fail');
                });
            });

        }):
    </script>
</body>
</html>