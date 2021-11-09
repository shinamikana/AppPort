<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

include('dateBase.php');
include('memoData.php');
include('bookmarkData.php');
include('mapData.php');

//ブックマークアプリによるカラム表示SQL
$show = $mysqli -> prepare('SELECT *,bookmark.id AS bookmark_id FROM bookmark LEFT JOIN book_memo ON bookmark.id = book_memo.book_id LEFT JOIN map_bookmark ON bookmark.id = map_bookmark.book_id WHERE bookmark.user_id = ? AND book_memo.id IS NULL AND map_bookmark.id IS NULL ORDER BY bookmark.id DESC');
$show -> bind_param('i',$_SESSION['id']);
$show -> execute();
$showResult = $show -> get_result();
$bookCount = 0;
while($showResult -> fetch_assoc()){
    ++$bookCount;
}

//ブックマーク->メモの表示SQL
$showMemoBook = $mysqli -> prepare('SELECT *,book_memo.id AS bookMemoId FROM book_memo LEFT JOIN bookmark ON book_memo.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showMemoBook -> bind_param('i',$_SESSION['id']);
$showMemoBook -> execute();
$memoBookResult = $showMemoBook -> get_result();

//ブックマーク->マップの表示SQL
$showBookMap = $mysqli -> prepare('SELECT *,bookmark.id AS bookId  FROM map_bookmark LEFT JOIN bookmark ON map_bookmark.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showBookMap -> bind_param('i',$_SESSION['id']);
$showBookMap -> execute();
$bookMapResult = $showBookMap -> get_result();

//ブックマークアプリでの地図あぷりのカラム表示SQL
$showMark = $mysqli->prepare('SELECT *,map.id AS mapId FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id WHERE user_id = ? AND map_memo.id IS NULL ORDER BY map.id DESC');
$showMark->bind_param('i', $_SESSION['id']);
$showMark->execute();
$resultMark = $showMark->get_result();
$mapCount = 0;
while ($resultMark->fetch_assoc()) {
    ++$mapCount;
}

///ブックマークアプリでのメモカラムの表示SQL
$showMemo = $mysqli -> prepare('SELECT *, memo.id AS memo_id FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id LEFT JOIN map_memo ON memo.id = map_memo.memo_id WHERE memo.user_id = ? AND map_memo.id IS NULL ORDER BY memo.id DESC');
$showMemo -> bind_param('i',$_SESSION['id']);
$showMemo -> execute();
$memoResult = $showMemo -> get_result();
$memoCount = 0;
while($memoResult -> fetch_assoc()){
    ++$memoCount;
}

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
    <link rel="stylesheet" href="/css/map.css">

</head>

<body>
    <?php include('miniLogo.php'); ?>
    <div id="bookMain">
        <?php include('bookmarkIndexL.php') ?>
        <div id="selectRightWrapper">
            <div id="emptyDiv"></div>
            <select id="selectR">
                <option value="memo">メモ(<?php echo $memoCount ?>)</option>
                <option value="map">マップ(<?php echo $mapCount ?>)</option>
            </select>
        </div>
        <?php include('mapIndex.php'); ?>
        <?php include('memoIndex.php'); ?>
    </div>

    <script>
        //ブックマークのドラッグ＆ドロップ処理
        $(() => {
            ///メモアプリからブックマークアプリに移動したら
            window.sortableLeft= function() {
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
                        let bookId = $(this).find('.drag').find('.bookId').val();
                        let mapId = $(this).find('.dragBM').find('.bookId').val();
                        console.log('mapId is'+mapId);
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
                                alert('bookId done');
                                //if($(this).parent().find('bookLi').hasClass('')
                                //クラスの付け外しで判定
                                $this.find('.drag').addClass('noDrag').removeClass('drag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                        if(mapId != undefined){
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'removeBook': mapId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                alert('mapId done');
                                $this.find('.dragBM').addClass('noDrag').removeClass('dragBM');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                    }
                });
            }
            sortableLeft();

            //ブックマークからメモアプリに移動したら
            window.sortableRight = function() {
                $('.dragUl').sortable({
                    connectWith: '.bookUl',
                    placeholder: 'memoDiv',
                    stop:function(){
                        $('.bookWrapper').css('overflow-y','scroll');
                    },
                    update: function(ev, ui) {
                        let $this = $(this);
                        console.log('dragUl');
                        //ドロップされるメモのid
                        let memoId = $(this).parent().find('.memoId').val();
                        let mapId = $(this).parent().find('.mapId').val();
                        console.log('memoId is'+memoId);
                        console.log('mapId is'+mapId);
                        //ドロップされたブックマークのid
                        let dragId = $(this).parent().find('.noDrag').find('.bookId').val();
                        if (dragId != undefined && memoId != undefined) {
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
                                $this.find('.noDrag').addClass('drag').removeClass('noDrag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('dragUl fail');
                            });
                        }
                        if(dragId != undefined && mapId != undefined){
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'mapId': mapId,
                                    'dragId': dragId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                alert('done');
                                $this.find('.noDrag').addClass('dragBM').removeClass('noDrag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                    }
                });
            }
            sortableRight();

            $('.bookUl').disableSelection();

            $('#selectR').on('change',function(){
                let selectVal = $(this).val();
                if(selectVal === 'map'){
                    $('.wrapper').hide();
                    $('.mapWrapper').show();
                }else if(selectVal === 'memo'){
                    $('.wrapper').show();
                    $('.mapWrapper').hide();
                }
            });
        });
    </script>
</body>

</html>