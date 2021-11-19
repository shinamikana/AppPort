<?php
include('dateBase.php');
include('passData.php');
session_regenerate_id(TRUE);

if(empty($_SESSION['username']) ){
    header('Location:login.php');
  }

  function h($str){
    return htmlspecialchars($str,ENT_QUOTES|ENT_HTML5,'UTF-8');
  }

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <div class="passWrapper">
        <h1>パスワード生成</h1>
        <div id="passError">
            <p id="passErrorText"></p>
        </div>
        <span id="dummyPass1">ここはダミーです</span>
        <span id="dummyLeft">ダミーです</span><p id="genPass">生成ボタンを押してください</p><span id="dummyRight">ダミーです</span>
        <span id="dummyPass2">ここはダミーです</span>
        <br>
        <input type="text" id="passLen" value="12" autofocus><span id="lenSpan">桁</span><button id="passBtn">パスワード生成</button>
        <button id="passInput">気に入った！</button>
        <div id="passAfterView">
            <input type="text" id="passTitle" placeholder="パスワード名（サイト名は禁止）">
            <button id="passKeep">保存</button><img src="/img/load.gif" alt="" id="passLoad">
        </div>
        <div class="pass">
            <?php foreach($passResult as $pass): ?>
                <div class="passColumn">
                <i class="fas fa-check"></i><i class="fas fa-edit"></i><img src="/img/load.gif" alt="" class="passCheckLoad"><span class="passName"><?= h($pass['passName']) ?></span><input type="text" class="passNameInput" value="<?= h($pass['passName']) ?>" autofocus><i class="far fa-copy wcopy"></i><i class="fas fa-copy bcopy"></i><input type="hidden" value="<?= h($pass['pass']) ?>" class="password"><input type="hidden" value="<?= h($pass['id']) ?>" class="passId"><i class="fas fa-bars"></i><ul class="passEdit"><li class="passDel">削除</li></ul>
                </div>
                <?php endforeach ?>
        </div>
    </div>
    

    <script>
        let str = 'abcdefghijklmnopqrstuvxwyz';
        let num = '1234567890';
        let sym = '!#$%&?()';
        let string = str + str.toUpperCase() + num + sym + sym;
        let dummyLen = 50;
        let pass = '';
        let passBtn = document.getElementById('passBtn');
        let genPass = document.getElementById('genPass');
        let dummyPassText1 = document.getElementById('dummyPass1');
        let dummyPassText2 = document.getElementById('dummyPass2');
        let dummyPassTextLeft = document.getElementById('dummyLeft');
        let dummyPassTextRight = document.getElementById('dummyRight');
        let passLen = document.getElementById('passLen');
        let passErrorText = document.getElementById('passErrorText');
        let dummyPass1 = '';
        let dummyPass2 = '';
        let dummyPassLeft = '';
        let dummyPassRight = '';
        let len = 12;
        passBtn.addEventListener('click',function(){
            len = passLen.value;
            console.log(len);
            if(isNaN(len) || len == '' || len == 0){
                passErrorText.innerText = '半角数字で入力してください！';
            }else{
                passErrorText.innerText = '';
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
            }
        });


        $(()=>{
            $('.passEdit').slideUp(0);

            function slideToggle(){
                $('.fa-bars').click(function(){
                $(this).parent().find('.passEdit').slideToggle(200);
                });
            }

            let $error = $('#passErrorText');
            let $dbError = 'データベースに問題があります！<br />管理人に問い合わせるか気長に待っていてください！';
            

            slideToggle();

            //パスワードの保存処理
            $('#passKeep').on('click',function(){
                let passText = $('#genPass').text();
                let passTitle = $('#passTitle').val();
                let $this = $(this);
                if(passTitle == ''){
                    $error.text('パスワード名を入力してください！');
                }else{
                    $this.hide();
                    $('#passLoad').show();
                    $error.text('');
                    $.ajax({
                        type:'POST',
                        url:'pass.php',
                        data:{'pass':passText,'passTitle':passTitle},
                        dataType:'json',
                    }).done(function(data){
                        $('.pass').prepend('<div class="passColumn"><i class="fas fa-check"></i><i class="fas fa-edit"></i><span class="passName">'+data.passTitle+'</span><input type="text" class="passNameInput" value="'+data.passTitle+'" autofocus><i class="far fa-copy wcopy"></i><i class="fas fa-copy bcopy"></i><input type="hidden" value="'+data.pass+'" class="password"><input type="hidden" value="'+data.insertId+'" class="passId"><i class="fas fa-bars"></i><ul class="passEdit"><li class="passDel">削除</li></ul></div>');
                        $('.bcopy').hide();
                        $('.fa-check').hide();
                        $('.passColumn').first().find('.fa-bars').click(function(){
                            $(this).parent().find('.passEdit').slideToggle(200);
                        });
                        $('.passEdit').first().slideUp(0);
                        passCopy();
                        passEdit();
                        passCheck();
                        passDel();
                        $this.show();
                        $('#passLoad').hide();
                        $('#passAfterView').hide();
                        $('#passTitle').text('');
                    }).fail(function(HMLHttpRequest,status,e){
                        console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                        $error.text($dbError);
                    });
                }
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

            const passCopy = function(){
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
            }
            passCopy();

            const passEdit = function(){
                $('.fa-edit').click(function(){
                    $(this).parent().find('.passName').hide();
                    $(this).parent().find('.passNameInput').show();
                    $(this).hide();
                    $(this).parent().find('.fa-check').show();
                });
            }
            passEdit();

            const passCheck = function(){
                $('.fa-check').click(function(){
                    $(this).hide();
                    let $this = $(this);
                    $this.parent().find('.passCheckLoad').show();
                    let $thisParent = $(this).parent();
                    let passName = $thisParent.find('.passName').text();
                    let passNameInput = $thisParent.find('.passNameInput').val();
                    let passId = $thisParent.find('.passId').val();
                    if(passName == passNameInput){
                        $thisParent.find('.passNameInput').hide();
                        $thisParent.find('.passName').show();
                        $(this).hide();
                        $thisParent.find('.fa-edit').show();
                        $this.parent().find('.passCheckLoad').hide();
                    }else{
                        $.ajax({
                            type:'POST',
                            url:'pass.php',
                            data:{'passUp':passNameInput,'passId':passId},
                            dataType:'json'
                        }).done(function(data){
                            $thisParent.find('.passName').text(passNameInput);
                            $thisParent.find('.passName').show();
                            $thisParent.find('.passNameInput').hide();
                            $thisParent.find('.fa-edit').show();
                            $thisParent.find('.fa-check').hide();
                            $this.parent().find('.passCheckLoad').hide();
                        }).fail(function(XMLHttpRequest,status,e){
                            $error.text($dbError);
                        });
                    }
                });
            }
            passCheck();

            const passDel = function(){
                $('.passDel').click(function(){
                    let $this = $(this);
                    let $thisParent = $(this).parent();
                    let passDel = $thisParent.parent().find('.passId').val();
                    $thisParent.parent().css('opacity','0.5');
                    $.ajax({
                        type:'POST',
                        url:'pass.php',
                        data:{'passDel':passDel},
                        dataType:'json'
                    }).done(function(data){
                        $thisParent.parent().remove();
                    }).fail(function(XMLHttpRequest,status,e){
                        $error.text($dbError);
                    });
                });
            }
            passDel();

            $('.pass').sortable({

            });

            

        });

        
    </script>
</body>
</html>