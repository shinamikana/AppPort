<?php
function h($str){
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    
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
            ///ブックマークアプリからメモアプリに移動したら
            window.bookSortable = function(){
            $('.bookUl').sortable({
                connectWith:'.dragUl',
                placeholder:'memoDiv',
                update:function(ev,ui){
                    let $this = $(this);
                    let bookId = $(this).parent().find('.drag').find('.bookId').val();
                    console.log('bookId is'+ bookId);
                    if(bookId != undefined){
                    $.ajax({
                        type:'POST',
                        url:'bookmark.php',
                        data:{'removeDrag':bookId},
                        dataType:'json',
                    }).done(function(data){
                        alert('done');
                        //if($(this).parent().find('bookLi').hasClass('')
                        //クラスの付け外しで判定
                        $this.find('.drag').addClass('noDrag').removeClass('drag');
                    }).fail(function(XMLHttpRequest,status,e){
                        alert('fail');
                    });
                    }
                }
            });
            }
            bookSortable();

            //ブックマークからメモアプリに移動したら
            window.memoSortable = function(){
            $('.dragUl').sortable({
                connectWith:'.bookUl',
                placeholder:'memoDiv',
                update:function(ev,ui){
                    let $this = $(this);
                    ///ドラッグされた要素と分るようにdragクラスを付与
                    //メモアプリ自体にもブックマークが紐付けしていればdragクラスを持ったまま表示するようにしてある
                    $(this).find('.bookmarking').addClass('drag');
                    //ドロップされるメモのid
                    let memoId = $(this).find('.noDrag').find('.memoId').val();
                    //ドロップされたブックマークのid
                    let dragId = $(this).sortable('toArray').join(',');
                    if(dragId != 0){
                        $.ajax({
                        type:'POST',
                        url:'bookmark.php',
                        data:{'memoId':memoId,'dragId':dragId},
                        dataType:'json',
                    }).done(function(data){
                        alert('done');
                        //$(this).find('.noDrag').addClass('drag');
                        $this.find('.noDrag').addClass('drag').removeClass('noDrag');
                    }).fail(function(XMLHttpRequest,status,e){
                        alert('fail');
                    });
                    }
                }
            });
            }
            memoSortable();

            $('.bookUl').disableSelection();
        });
    </script>
</body>
</html>