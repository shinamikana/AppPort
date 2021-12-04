<?php
require_once('dateBase.php');
require_once('memoData.php');
require_once('bookmarkData.php');
require_once('mapData.php');

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
session_regenerate_id(TRUE);

//ブックマークアプリによるカラム表示SQL
$show = $mysqli->prepare('SELECT *,bookmark.id AS bookmark_id FROM bookmark LEFT JOIN book_memo ON bookmark.id = book_memo.book_id LEFT JOIN map_bookmark ON bookmark.id = map_bookmark.book_id WHERE bookmark.user_id = ? AND book_memo.id IS NULL AND map_bookmark.id IS NULL ORDER BY bookmark.id DESC');
$show->bind_param('i', $_SESSION['id']);
$show->execute();
$showResult = $show->get_result();
$bookCount = 0;
while ($showResult->fetch_assoc()) {
    ++$bookCount;
}

//ブックマーク->メモの表示SQL
$showMemoBook = $mysqli->prepare('SELECT *,book_memo.id AS bookMemoId FROM book_memo LEFT JOIN bookmark ON book_memo.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showMemoBook->bind_param('i', $_SESSION['id']);
$showMemoBook->execute();
$memoBookResult = $showMemoBook->get_result();

//ブックマーク->マップの表示SQL
$showBookMap = $mysqli->prepare('SELECT *,bookmark.id AS bookId  FROM map_bookmark LEFT JOIN bookmark ON map_bookmark.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showBookMap->bind_param('i', $_SESSION['id']);
$showBookMap->execute();
$bookMapResult = $showBookMap->get_result();

//ブックマークアプリでの地図あぷりのカラム表示SQL
$showMark = $mysqli->prepare('SELECT *,map.id AS mapId FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id WHERE user_id = ? ORDER BY map.id DESC');
$showMark->bind_param('i', $_SESSION['id']);
$showMark->execute();
$resultMark = $showMark->get_result();
$mapCount = 0;
while ($resultMark->fetch_assoc()) {
    ++$mapCount;
}

///ブックマークアプリでのメモカラムの表示SQL
$showMemo = $mysqli->prepare('SELECT *, memo.id AS memo_id FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id LEFT JOIN map_memo ON memo.id = map_memo.memo_id WHERE memo.user_id = ? ORDER BY memo.id DESC');
$showMemo->bind_param('i', $_SESSION['id']);
$showMemo->execute();
$memoResult = $showMemo->get_result();
$memoCount = 0;
while ($memoResult->fetch_assoc()) {
    ++$memoCount;
}

$showColumnMemoMap = $mysqli -> prepare('SELECT *,memo.id AS memoId FROM map_memo LEFT JOIN memo ON map_memo.memo_id = memo.id WHERE memo.user_id = ?');
$showColumnMemoMap -> bind_param('i',$_SESSION['id']);
$showColumnMemoMap -> execute();
$showCMeM = $showColumnMemoMap -> get_result();

$showColumnMapMemo = $mysqli -> prepare('SELECT *, map.id AS mapId FROM map_memo LEFT JOIN map ON map_memo.map_id = map.id WHERE map.user_id = ?');
$showColumnMapMemo -> bind_param('i',$_SESSION['id']);
$showColumnMapMemo -> execute();
$showCMMe = $showColumnMapMemo -> get_result();

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
    <?php require_once('miniLogo.php'); ?>
    <div id="bookMain">
        <?php require_once('bookmarkIndexL.php') ?>
        <div id="selectRightWrapper">
            <div id="emptyDiv"></div>
            <select id="selectR">
                <option value="memo">メモ(<?php echo $memoCount ?>)</option>
                <option value="map">マップ(<?php echo $mapCount ?>)</option>
            </select>
        </div>
        <?php require_once('mapIndex.php'); ?>
        <?php require_once('memoIndex.php'); ?>
    </div>

    <script src="memo.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly" async></script>
    <script src="map.js"></script>
    <script>
        $(function() {
            $('#bookConfirmNo').click(function() {
                $('#confirmBigWrap').hide();
            });

            const errorProcess = function(error) {
                $('#bookmarkError').text(error).show().delay(2000).queue(function() {
                    $(this).hide().dequeue();
                });
            }

            //ブックマークの送信処理
            $('#submit1').on('click', function(event) {
                let urlVal = $('#url').val();
                let linkNameVal = $('#linkName').val();
                $('#submit1').hide();
                $('#load1').show();
                console.log(urlVal);
                if (!urlVal && !linkNameVal) {
                    errorProcess('URLとリンク名が入力されていません！');
                    $('#submit1').show();
                    $('#load1').hide();
                } else if (!urlVal) {
                    errorProcess('URLが入力されていません！');
                    $('#submit1').show();
                    $('#load1').hide();
                } else if (!linkNameVal) {
                    errorProcess('リンク名が入力されていません！');
                    $('#submit1').show();
                    $('#load1').hide();
                } else if (!urlVal.match('http')) {
                    console.log(urlVal);
                    errorProcess('URLが正しくありません！');
                    $('#submit1').show();
                    $('#load1').hide();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'bookmark.php',
                        data: {
                            'url': urlVal,
                            'linkName': linkNameVal
                        },
                        dataType: 'json',
                    }).done(function(data) {
                        $('.bookUl').prepend('<li class="bookLi noDrag" id="' + data.id + '"><div class="bookmarking"><i class="fas fa-check"></i><i class="far fa-edit"></i><a href="' + data.url + '" target="_blank" rel="noopener noreferrer" class="bookA">' + data.linkName + '</a><i class="fas fa-times"></i><input type="text" value="' + data.linkName + '" class="bookNameInput"><input type="text" value="' + data.url + '" class="bookLinkInput"><button id="deltn1" value="' + data.id + '">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="' + data.id + '"><i class="fas fa-bars"></i></div></li>');
                        $('#submit1').show();
                        $('#load1').hide();
                        $('#url').val('');
                        $('#linkName').val('');
                        $delete();
                        $('.bookmarking').find('.fa-bars').first().click(function() {
                            $(this).parent().find('#deltn1').slideToggle();
                        });
                        bookEdit();
                        bookCancel();
                        bookSubmit();
                    }).fail(function(XMLHttpRequest, status, e) {
                        console.log('error number:' + XMLHttpRequest + ',status:' + status + ',thrown:' + e);
                        alert('fail');
                        $('#submit1').show();
                        $('#load1').hide();
                    });
                }
            });

            //ブックマークの削除処理
            let $delete = function() {
                $('.bookmarking').find('#deltn1').on('click', function(event) {
                    let $this = $(this).parent();
                    $this.css({
                        opacity: '0.5'
                    });
                    $('.bookmarking').find('.fa-bars').hide();
                    $('.deload1').show();
                    let delId = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: 'bookmark.php',
                        data: {
                            'delId': delId
                        },
                        dataType: 'json',
                    }).done(function(data) {
                        $this.hide();
                        $('.deload1').hide();
                        $('.bookmarking').find('.fa-bars').show();
                    }).fail(function(XMLHttpRequest, status, e) {
                        console.log('error number:' + XMLHttpRequest + ',status:' + status + ',thrown:' + e);
                        $this.css({
                            opacity: '1'
                        });
                        $('.deload1').hide();
                    });
                });
            }

            $delete();

            const bookEdit = function() {
                $('.fa-edit').click(function() {
                    $(this).hide();
                    $(this).parent().find('.bookA').hide();
                    $(this).parent().find('.bookNameInput').show();
                    $(this).parent().find('.bookLinkInput').show();
                    $(this).parent().find('.fa-check').show();
                    $(this).parent().find('.fa-bars').hide();
                    $(this).parent().find('.fa-times').show();
                });
            }
            bookEdit();

            //編集完了プロセス
            const bookEditShow = function($this) {
                $this.hide();
                $this.parent().find('.fa-edit').show();
                $this.parent().find('.bookNameInput').hide();
                $this.parent().find('.bookA').show();
                $this.parent().find('.bookLinkInput').hide();
                $this.parent().css('opacity', '1');
                $this.parent().find('.fa-bars').show();
                $this.parent().find('.fa-times').hide();
                $this.parent().find('.fa-check').hide();
            }

            //編集を中止した場合の処理
            const bookCancel = function() {
                $('.fa-times').click(function() {
                    let $this = $(this);
                    let bookName = $this.parent().find('.bookA').text();
                    let bookLink = $this.parent().find('.bookA').attr('href');
                    $this.parent().find('.bookNameInput').val(bookName);
                    $this.parent().find('.bookLinkInput').val(bookLink);
                    bookEditShow($this);
                });
            }
            bookCancel();

            //編集を送信する処理
            const bookSubmit = function() {
                $('.fa-check').click(function() {
                    let $this = $(this);
                    let bookNameVal = $this.parent().find('.bookNameInput').val();
                    let bookLinkVal = $this.parent().find('.bookLinkInput').val();
                    let bookName = $this.parent().find('.bookA').text();
                    let bookLink = $this.parent().find('.bookA').attr('href');
                    let bookId = $this.parent().find('.bookId').val();
                    //入力欄が空白の場合の処理
                    if (bookNameVal == '') {
                        errorProcess('ブックマーク名を入力してください！');
                    }
                    if (bookLinkVal == '') {
                        errorProcess('URLを入力してください！');
                    }

                    if (bookNameVal != bookName && bookLinkVal != bookLink) {
                        $('#confirmBigWrap').show();
                        $('#bookConfirmYes').click(function() {
                            $this.parent().css('opacity', '0.5');
                            $('#confirmBigWrap').hide();
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'bookName': bookNameVal,
                                    'bookLink': bookLinkVal,
                                    'bookId': bookId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                $this.parent().find('.bookA').text(bookNameVal);
                                $this.parent().find('.bookA').attr('href', bookLinkVal);
                                bookEditShow($this);
                            }).fail(function(XMLHttpRequest, status, e) {});
                        });

                    } else if (bookNameVal != bookName) {
                        $this.parent().css('opacity', '0.5');
                        $.ajax({
                            type: 'POST',
                            url: 'bookmark.php',
                            data: {
                                'bookName': bookNameVal,
                                'bookId': bookId
                            },
                            dataType: 'json',
                        }).done(function(data) {
                            $this.parent().find('.bookA').text(bookNameVal);
                            bookEditShow($this);
                            $this.parent().css('opacity', '1');
                        }).fail(function(XMLHttpRequest, status, e) {});

                    } else if (bookLinkVal != bookLink) {
                        $('#confirmBigWrap').show();
                        $('#bookConfirmYes').click(function() {
                            $this.parent().css('opacity', '0.5');
                            $('#confirmBigWrap').hide();
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'bookLink': bookLinkVal,
                                    'bookId': bookId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                $this.parent().find('.bookA').attr('href', bookLinkVal);
                                bookEditShow($this);
                            }).fail(function(XMLHttpRequest, status, e) {});
                        });
                    } else {
                        bookEditShow($this);
                    }
                });
            }
            bookSubmit();

            $('.bookmarking').find('.fa-bars').click(function() {
                $(this).parent().find('#deltn1').slideToggle();
            });

        });
    </script>
    <script>
        //ブックマークのドラッグ＆ドロップ処理
        $(() => {
            ///メモアプリからブックマークアプリに移動したら
            window.sortableLeft = function() {
                $('.bookUl').sortable({
                    connectWith: '.dragUl',
                    placeholder: 'memoDiv',
                    scroll: false,
                    out: function() {
                        $('.bookWrapper').css('overflow-y', 'visible');
                    },
                    over: function() {
                        $('.bookWrapper').css('overflow-y', 'scroll');
                    },
                    stop: function() {
                        $('.bookWrapper').css('overflow-y', 'scroll');
                    },
                    update: function(ev, ui) {
                        let $this = $(this);
                        let bookId = $(this).find('.drag').find('.bookId').val();
                        let mapId = $(this).find('.dragBM').find('.bookId').val();
                        console.log('mapId is' + mapId);
                        console.log('bookUl bookId is' + bookId);
                        if (bookId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'removeDrag': bookId
                                },
                                dataType: 'json',
                            }).done(function(data) {
                                //if($(this).parent().find('bookLi').hasClass('')
                                //クラスの付け外しで判定
                                $this.find('.drag').addClass('noDrag').removeClass('drag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('fail');
                            });
                        }
                        if (mapId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'removeBook': mapId
                                },
                                dataType: 'json',
                            }).done(function(data) {
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
                    stop: function() {
                        $('.bookWrapper').css('overflow-y', 'scroll');
                    },
                    update: function(ev, ui) {
                        let $this = $(this);
                        console.log('dragUl');
                        //ドロップされるメモのid
                        let memoId = $(this).parent().find('.memoId').val();
                        let mapId = $(this).parent().find('.mapId').val();
                        console.log('memoId is' + memoId);
                        console.log('mapId is' + mapId);
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
                                $this.find('.noDrag').addClass('drag').removeClass('noDrag');
                            }).fail(function(XMLHttpRequest, status, e) {
                                alert('dragUl fail');
                            });
                        }
                        if (dragId != undefined && mapId != undefined) {
                            $.ajax({
                                type: 'POST',
                                url: 'bookmark.php',
                                data: {
                                    'mapId': mapId,
                                    'dragId': dragId
                                },
                                dataType: 'json',
                            }).done(function(data) {
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

            $('#selectR').on('change', function() {
                let selectVal = $(this).val();
                if (selectVal === 'map') {
                    $('.wrapper').hide();
                    $('.mapWrapper').show();
                } else if (selectVal === 'memo') {
                    $('.wrapper').show();
                    $('.mapWrapper').hide();
                }
            });
        });
    </script>
</body>

</html>