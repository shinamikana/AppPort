<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

include('dateBase.php');
include('memoData.php');
include('bookmarkData.php');
$showMemoBook = $mysqli -> prepare('SELECT *,book_memo.id AS bookMemoId FROM book_memo LEFT JOIN bookmark ON book_memo.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showMemoBook -> bind_param('i',$_SESSION['id']);
$showMemoBook -> execute();
$memoBookResult = $showMemoBook -> get_result();

if (empty($_SESSION['username'])) {
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
    <link rel="stylesheet" href="/css/bookmark.css">
    <link rel="stylesheet" href="/css/bookmarkIndexL.css">

</head>

<body>
    <?php include('miniLogo.php'); ?>
    <div id="bookMain">
        <?php include('bookmarkIndexL.php') ?>
        <div id="selectRightWrapper">
            <div id="emptyDiv"></div>
            <select id="selectR">
                <option value="memo">メモ</option>
                <option value="map">マップ</option>
            </select>
        </div>
        <?php include('memoIndex.php'); ?>
    </div>

    <script>
        //ブックマークのドラッグ＆ドロップ処理
        $(() => {
            ///メモアプリからブックマークアプリに移動したら
            window.bookSortable = function() {
                $('.bookUl').sortable({
                    connectWith: '.dragUl',
                    placeholder: 'memoDiv',
                    scroll:false,
                    out:function(){
                        $('.bookWrapper').css('overflow-y','visible');
                    },
                    over:function(){
                        $('.bookWrapper').css('overflow-y','scroll');
                    },
                    stop:function(){
                        $('.bookWrapper').css('overflow-y','scroll');
                    },
                    update: function(ev, ui) {
                        let $this = $(this);
                        let bookId = $(this).parent().find('.drag').find('.bookId').val();
                        console.log('bookUl bookId is'+bookId);
                        if (bookId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'removeDrag': bookId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                alert('done');
                                //if($(this).parent().find('bookLi').hasClass('')
                                //クラスの付け外しで判定
                                $this.find('.drag').addClass('noDrag').removeClass('drag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                    }
                });
            }
            bookSortable();

            //ブックマークからメモアプリに移動したら
            window.memoSortable = function() {
                $('.dragUl').sortable({
                    connectWith: '.bookUl',
                    placeholder: 'memoDiv',
                    stop:function(){
                        $('.bookWrapper').css('overflow-y','scroll');
                    },
                    update: function(ev, ui) {
                        let $this = $(this);
                        console.log('dragUl');
                        console.log('')
                        ///ドラッグされた要素と分るようにdragクラスを付与
                        //メモアプリ自体にもブックマークが紐付けしていればdragクラスを持ったまま表示するようにしてある
                        $(this).find('.bookmarking').addClass('drag');
                        //ドロップされるメモのid
                        let memoId = $(this).parent().find('.memoId').val();
                        console.log('memoId is' + memoId);
                        //ドロップされたブックマークのid
                        let dragId = $(this).parent().find('.noDrag').find('.bookId').val();
                        console.log('dragId is' + dragId);
                        if (dragId != 0 && dragId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'memoId': memoId,
                                    'dragId': dragId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                alert('dragUl done');
                                //$(this).find('.noDrag').addClass('drag');
                                $this.find('.noDrag').addClass('drag').removeClass('noDrag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('dragUl fail');
                            });
                        }
                    }
                });
            }
            memoSortable();

            $('.bookUl').disableSelection();

            $('#selectR').on('change',function(){
                let selectVal = $(this).val();
                if(selectVal === 'map'){
                    console.log('map');
                }else if(selectVal === 'memo'){
                    console.log('memo');
                }
            });
        });
    </script>
</body>

</html>