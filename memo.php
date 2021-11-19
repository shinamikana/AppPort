<?php
require_once('dateBase.php');
require_once('memoData.php');
require_once('bookmarkData.php');
require_once('mapData.php');
session_regenerate_id(TRUE);

//メモアプリでメモのカラム表示
$showMemo = $mysqli->prepare('SELECT *, memo.id AS memo_id FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id LEFT JOIN map_memo ON memo.id = map_memo.memo_id WHERE memo.user_id = ? AND book_memo.id IS NULL AND map_memo.id IS NULL ORDER BY memo.id DESC');
$showMemo->bind_param('i', $_SESSION['id']);
$showMemo->execute();
$memoResult = $showMemo->get_result();
$memoCount = 0;
while ($memoResult->fetch_assoc()) {
    ++$memoCount;
}

//メモアプリでのメモカラムの表示
$show = $mysqli->prepare('SELECT *,bookmark.id AS bookmark_id FROM bookmark LEFT JOIN map_bookmark ON bookmark.id = map_bookmark.book_id WHERE bookmark.user_id = ? AND map_bookmark.id IS NULL ORDER BY bookmark.id DESC');
$show->bind_param('i', $_SESSION['id']);
$show->execute();
$showResult = $show->get_result();
$bookCount = 0;
while ($showResult->fetch_assoc()) {
    ++$bookCount;
}

//メモ->ブックマークのSQL
$memoBook = $mysqli->prepare('SELECT *,memo.id AS memoId FROM book_memo LEFT JOIN memo ON book_memo.memo_id = memo.id WHERE memo.user_id = ?');
$memoBook->bind_param('i', $_SESSION['id']);
$memoBook->execute();
$memoBookShow = $memoBook->get_result();

//メモ->マップのSQL
$memoMap = $mysqli->prepare('SELECT *,memo.id AS memoId FROM map_memo LEFT JOIN memo ON map_memo.memo_id = memo.id WHERE memo.user_id = ?');
$memoMap->bind_param('i', $_SESSION['id']);
$memoMap->execute();
$memoMapResult = $memoMap->get_result();

//地図の表示SQL
$showMark = $mysqli->prepare('SELECT *,map.id AS mapId FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id LEFT JOIN map_bookmark ON map.id = map_bookmark.map_id WHERE user_id = ? AND map_bookmark.id IS NULL ORDER BY mapId DESC');
$showMark->bind_param('i', $_SESSION['id']);
$showMark->execute();
$resultMark = $showMark->get_result();
$mapCount = 0;
while ($resultMark->fetch_assoc()) {
    ++$mapCount;
}

if (empty($_SESSION['username'])) {
    header('Location:login.php');
}
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES|ENT_HTML5, 'UTF-8');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
    <link rel="stylesheet" href="/css/memoIndexL.css">
    <link rel="stylesheet" href="/css/bookmarkIndex.css">
    <link rel="stylesheet" href="/css/map.css">

</head>

<body>
    <?php require_once('miniLogo.php'); ?>
    <main>
        <?php require_once('memoIndexL.php'); ?>
        <div id="select">
            <div id="emptySelect">
            </div>
            <select name="memoR" id="memoR">
                <option value="bookmark">ブックマーク(<?php echo $bookCount ?>)</option>
                <option value="map">マップ(<?php echo $mapCount ?>)</option>
            </select>
        </div>
        <?php require_once('bookmarkIndex.php') ?>
        <?php require_once('mapIndex.php'); ?>
    </main>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly" async></script>
    <script src="book.js"></script>
    <script src="map.js"></script>
    <script>
    document.getElementById('byte').innerText = '0/500';
    const byteCount = function() {
        const memoByte = document.getElementById('text').value;
        let byte = (new Blob([memoByte])).size;
        document.getElementById('byte').innerText = `${byte}/500`;
        if (byte > 500) {
            document.getElementById('byte').innerText = '文字数オーバーです';
        }
    }
</script>
<script>
    $(function() {
        let $memoDel = function() {
            $('.memo').find('#delbtn').on('click', function() {
                let delId = $(this).val();
                $('.memo').find('#delbtn').hide();
                $('#deload').show();
                let $this = $(this).parent()
                $this.css({
                    opacity: '0.5'
                });
                $.ajax({
                    type: 'POST',
                    url: 'memo.php',
                    data: {
                        'del': delId
                    },
                    dataType: 'json',
                }).done(function(data) {
                    $this.hide();
                    $('#deload').hide();
                }).fail(function(XMLHttpRequest, status, e) {
                    $this.css({
                        opacity: '1'
                    });
                    $('#deload').hide();
                });
            });
        }
        $memoDel();

        $('#submit').on('click', function(event) {
            let val = $('#text').val();
            if (val == '') {

            } else {
                $('#submit').hide();
                $('#load').show();
                $.ajax({
                    type: 'POST',
                    url: 'memo.php',
                    data: {
                        'text': val
                    },
                    dataType: 'json',
                }).done(function(data) {
                    $('#text').val('');
                    $('#memoWrapper').prepend('<div class="memo noDrag"><i class="fas fa-bars"></i><p id="mainText"><span>' + val + '</span></p><p id="date">' + data.date + '</p><button type="submit" value="' + data.insert + '" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="' + data.insert + '" class="memoId"></div>');
                    $('#load').hide();
                    $('#submit').show();
                    $memoDel();
                    sortableLeft();
                    sortableRight();
                    $('#byte').text('0/500');
                    $('.memo').first().find('.fa-bars').click(function() {
                        $(this).parent().find('#delbtn').slideToggle();
                    });
                }).fail(function(XMLHttpRequest, status, e) {
                    $('#memoWrapper').find('p').remove();

                });
            }
        });

        $('.memo').find('.fa-bars').click(function() {
            $(this).parent().find('#delbtn').slideToggle();
        });

    });
</script>
    <script>
        $(() => {
            window.sortableLeft = function() {
                $('#memoWrapper').sortable({
                    connectWith: '.dragUl',
                    placeholder: 'memoDiv',
                    scroll: false,
                    out: function() {
                        $('.wrapper').css('overflow-y', 'visible');
                    },
                    over: function() {
                        $('.wrapper').css('overflow-y', 'scroll');
                    },
                    stop: function() {
                        $('.wrapper').css('overflow-y', 'scroll');
                    },
                    update: function() {
                        let $this = $(this);
                        let rMemoId = $this.find('.dragMB').find('.memoId').val();
                        let rMemoMap = $this.find('.dragMM').find('.memoId').val();
                        if (rMemoId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'memo.php',
                                data: {
                                    'rMemoId': rMemoId,
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                $this.find('.dragMB').addClass('noDrag').removeClass('dragMB');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                        if(rMemoMap != undefined){
                            $.ajax({
                                type: 'POST',
                                url: 'memo.php',
                                data: {
                                    'rMemoMap': rMemoMap,
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                $this.find('.dragMM').addClass('noDrag').removeClass('dragMM');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                    }
                });
            }
            sortableLeft();

            window.sortableRight = function() {
                $('.dragUl').sortable({
                    connectWith: '#memoWrapper',
                    placeholder: 'memoDiv',
                    stop: function() {
                        $('.wrapper').css('overflow-y', 'scroll');
                    },
                    update: function() {
                        $this = $(this);
                        let memoId = $this.find('.noDrag').find('.memoId').val();
                        let bookId = $this.parent().find('.bookId').val();
                        let mapId = $this.parent().find('.mapId').val();
                        if (memoId != undefined && bookId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'memo.php',
                                data: {
                                    'memoId': memoId,
                                    'bookId': bookId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                $this.find('.noDrag').addClass('dragMB').removeClass('noDrag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                        if (mapId != undefined && memoId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'memo.php',
                                data: {
                                    'memoId': memoId,
                                    'mapId': mapId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                $this.find('.noDrag').addClass('dragMM').removeClass('noDrag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                    }
                });
            }
            sortableRight();

            $('.mapWrappper').hide();

            $('#memoR').on('change', function() {
                let memoRVal = $(this).val();
                if (memoRVal === 'bookmark') {
                    $('.bookWrapper').show();
                    $('.mapWrapper').hide();
                } else if (memoRVal === 'map') {
                    $('.mapWrapper').show();
                    $('.bookWrapper').hide();
                }
            });
        });
    </script>
</body>

</html>