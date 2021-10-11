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
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
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
            <input type="text" id="passTitle" placeholder="サイト名は禁止です">
            <button id="passKeep">保存</button>
        </div>
        <div class="pass">
            <?php foreach($passResult as $pass): ?>
                <div class="passColumn">
                <i class="fas fa-check"></i><i class="fas fa-edit"></i><span class="passName"><?php echo $pass['passName'] ?></span><input type="text" class="passNameInput" value="<?php echo $pass['passName'] ?>" autofocus><i class="far fa-copy wcopy"></i><i class="fas fa-copy bcopy"></i><input type="hidden" value="<?php echo $pass['pass'] ?>" class="password"><input type="hidden" value="<?php echo $pass['id'] ?>" class="passId">
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
                $('#genPass').css('background-color','black');
            });

            $('#passBtn').click(function(){
                $('#passAfterView').hide();
                $('#genPass').css('background-color','white');
                $(this).text('パスワード再生成');
                $('#passInput').show();
            });

            //font awesomeはcssの設定が面倒
            //(displayはfaやfasで指定されている)(cssでdisplayを指定するならimportantが必要だがjQueryでshowできない)
            //なので、jQueryで消した方が早い
            $('.bcopy').hide();
            $('.fa-check').hide();

            $('.wcopy').click(function(){
                let text = $(this).parent().find('.password').val();
                $(this).append('<textarea id="clip">'+text+'</textarea>');
                $('#clip').select();
                document.execCommand('copy');
                $('#clip').remove();
                $(this).hide().delay(1000).queue(function(){
                    $(this).show().dequeue();
                });

                $(this).parent().find('.bcopy').show().delay(1000).queue(function(){
                    $(this).hide().dequeue();
                });

            });

            $('.fa-edit').click(function(){
                $(this).parent().find('.passName').hide();
                $(this).parent().find('.passNameInput').show();
                $(this).hide();
                $(this).parent().find('.fa-check').show();
            });

            $('.fa-check').click(function(){
                let $thisParent = $(this).parent();
                let passName = $thisParent.find('.passName').text();
                let passNameInput = $thisParent.find('.passNameInput').val();
                let passId = $thisParent.find('.passId').val();
                if(passName == passNameInput){
                    $thisParent.find('.passNameInput').hide();
                    $thisParent.find('.passName').show();
                    $(this).hide();
                    $thisParent.find('.fa-edit').show();

                }else{
                    $.ajax({
                        type:'POST',
                        url:'pass.php',
                        data:{'passUp':passNameInput,'passId':passId},
                        dataType:'json'
                    }).done(function(data){
                        alert('done');
                    }).fail(function(XMLHttpRequest,status,e){
                        console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                        alert('fail');
                    });
                }

            });

        });
    </script>
</body>
</html>