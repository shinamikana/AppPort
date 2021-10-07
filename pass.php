<?php
include('dateBase.php');
include('passData.php');
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
        <span id="dummyPass1">ここはダミーです</span>
        <span id="dummyLeft">ダミーです</span><p id="genPass">生成ボタンを押してください</p><span id="dummyRight">ダミーです</span>
        <span id="dummyPass2">ここはダミーです</span>
        <br>
        <button id="passBtn">パスワード生成</button>
        <button id="passInput">気に入った！</button>
        <div id="passAfterView">
            <input type="text" id="passTitle" placeholder="サイト名ではなく識別できる名前">
            <button id="passKeep">保存</button>
        </div>
        <div class="pass">
            <?php foreach($passResult as $pass): ?>
                <div class="passColumn">
                    <p><?php echo $pass['passName'] ?></p>
                </div>
                <?php endforeach ?>
        </div>
    </div>
    

    <script>
        let str = 'abcdefghijklmnopqrstuvxwyz';
        let num = '1234567890';
        let sym = '!#$%&?()';
        let string = str + str.toUpperCase() + num + sym;
        let len = 12;
        let dummyLen = 50;
        let pass = '';
        let passBtn = document.getElementById('passBtn');
        let genPass = document.getElementById('genPass');
        let dummyPassText1 = document.getElementById('dummyPass1');
        let dummyPassText2 = document.getElementById('dummyPass2');
        let dummyPassTextLeft = document.getElementById('dummyLeft');
        let dummyPassTextRight = document.getElementById('dummyRight');
        let dummyPass1 = '';
        let dummyPass2 = '';
        let dummyPassLeft = '';
        let dummyPassRight = '';
        passBtn.addEventListener('click',function(){
            genPass.innerText = '';
            pass = '';
            dummyPass1 = '';
            dummyPass2 = '';
            dummyPassLeft = '';
            dummyPassRight = '';
            for(let i=0;i < len;i++){
                pass += string.charAt(Math.floor(Math.random() * string.length));
                dummyPassLeft += string.charAt(Math.floor(Math.random() * string.length));
                dummyPassRight += string.charAt(Math.floor(Math.random() * string.length));
                if(i < len){
                genPass.innerText = pass;
                dummyPassTextLeft.innerText = dummyPassLeft;
                dummyPassTextRight.innerText = dummyPassRight;
                }
            }

            for(let i=0;i<dummyLen;i++){
                dummyPass1 += string.charAt(Math.floor(Math.random() * string.length));
                dummyPass2 += string.charAt(Math.floor(Math.random() * string.length));
                if(i < dummyLen){
                    dummyPassText1.innerText = dummyPass1;
                    dummyPassText2.innerText = dummyPass2;
                }
            }
        });


        $(()=>{
            $('#passKeep').on('click',function(){
                let passText = $('#genPass').text();
                let passTitle = $('#passTitle').val();
                $.ajax({
                    type:'POST',
                    url:'pass.php',
                    data:{'pass':passText,'passTitle':passTitle},
                    dataType:'json',
                }).done(function(data){
                    alert('done');
                }).fail(function(HMLHttpRequest,status,e){
                    console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                    alert('fail');
                });
            });

            $('#passInput').click(function(){
                $('#passAfterView').show();
            });

        });
    </script>
</body>
</html>