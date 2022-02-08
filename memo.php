<?php
require_once('dateBase.php');
require_once('memoData.php');
require_once('bookmarkData.php');
require_once('mapData.php');
session_regenerate_id(TRUE);

//メモアプリでメモのカラム表示
$showMemo = $mysqli->prepare('SELECT *, memo.id AS memo_id FROM memo WHERE user_id = ? AND showFlag = 1 ORDER BY memo.id DESC');
$showMemo->bind_param('i', $_SESSION['id']);
$showMemo->execute();
$memoResult = $showMemo->get_result();
$memoCount = 0;
while ($memoResult->fetch_assoc()) {
  ++$memoCount;
}

//メモアプリでのブックマークカラムの表示
$show = $mysqli->prepare('SELECT *,bookmark.id AS bookmark_id FROM bookmark LEFT JOIN book_memo ON bookmark.id = book_memo.book_id  WHERE bookmark.user_id = ? AND book_memo.book_id IS NULL ORDER BY bookmark.id DESC');
$show->bind_param('i', $_SESSION['id']);
$show->execute();
$showResult = $show->get_result();
$bookCount = 0;
while ($showResult->fetch_assoc()) {
  ++$bookCount;
}

//メモ内のブックマークの表示
$memoBook = $mysqli->prepare('SELECT *,bookmark.id AS bookId FROM book_memo LEFT JOIN bookmark ON book_memo.book_id = bookmark.id WHERE bookmark.user_id = ?');
$memoBook->bind_param('i', $_SESSION['id']);
$memoBook->execute();
$memoBookShow = $memoBook->get_result();

//メモ内のマップの表示
$memoMap = $mysqli->prepare('SELECT *,map.id AS mapId FROM map_memo LEFT JOIN map ON map_memo.map_id = map.id WHERE map.user_id = ?');
$memoMap->bind_param('i', $_SESSION['id']);
$memoMap->execute();
$memoMapResult = $memoMap->get_result();

//地図の表示SQL
$showMark = $mysqli->prepare('SELECT *,map.id AS mapId FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id WHERE map.user_id = ? AND map_memo.map_id IS NULL ORDER BY mapId DESC');
$showMark->bind_param('i', $_SESSION['id']);
$showMark->execute();
$resultMark = $showMark->get_result();
$mapCount = 0;
while ($resultMark->fetch_assoc()) {
  ++$mapCount;
}

/* $showMemoMap = $mysqli -> prepare('SELECT *,map.id AS memoId FROM map_bookmark LEFT JOIN map ON map_bookmark.map_id = map.id WHERE map.user_id = ?');
$showMemoMap -> bind_param('i',$_SESSION['id']);
$showMemoMap -> execute();
$resultShowMapBook = $showMemoMap -> get_result();

$showMemoBook = $mysqli -> prepare('SELECT *,bookmark.id AS bookId FROM map_bookmark LEFT JOIN bookmark ON map_bookmark.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showMemoBook -> bind_param('i',$_SESSION['id']);
$showMemoBook -> execute();
$resultShowMemoBook = $showMemoBook -> get_result(); */

if (empty($_SESSION['username'])) {
  header('Location:login.php');
}
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
  <link rel="stylesheet" href="/css/memoIndexL.css">
  <link rel="stylesheet" href="/css/bookmarkIndex.css">
  <link rel="stylesheet" href="/css/map.css">

</head>

<body>
  <div id="settingWrapper">
    <i class="fas fa-times"></i>
    <p>メモの大きさ</p>
    <input type="range" id="memoSize" min="0.8" max="1.5" step="0.01" val="1">
    <p>フォントの大きさ</p>
    <input type="range" id="fontSize" min="0.8" max="1.5" step="0.01" val="1">
  </div>

  <i class="far fa-arrow-alt-circle-left" id="rightOpen"></i>
  <i class="far fa-arrow-alt-circle-right" id="rightClose"></i>
  <?php require_once('miniLogo.php'); ?>
  <main>
    <?php require_once('memoIndexL.php'); ?>
    <div id="rightContent">
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
    </div>
  </main>

  <script src="book.js"></script>
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
            $this.parent().hide();
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
            $('#memoWrapper').prepend('<div class="memo memoShadow" data-toggle="buttons"><div class="memos"><i class="fas fa-map-pin"></i><p id="mainText">' + val + '</p><p id="date">' + data.date + '</p><i class="fas fa-bars"></i><input type="checkbox" id="check' + data.insert + '"><label for="check' + data.insert + '" class="label"></label><button type="submit" value="' + data.insert + '" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="' + data.insert + '" class="memoId"></div><ul class="dragUl">ここにドロップ</ul></div>')
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
        $(this).parent().find('#delbtn').slideToggle(120);
      });

    });
  </script>
  <script>
    $(() => {
      ///ToDO側
      window.sortableLeft = function() {
        $('.dragUl').sortable({
          connectWith: '.sortUl',
          placeholder: 'memoDiv',
          scroll: false,
          start: function(event, ui) {
            let item = ui.item;
            if (item.hasClass('showMark')) {
              $('.bookWrapper').hide();
              $('.mapWrapper').show();
              $('#rightOpen').hide();
              $('.wrapper').removeClass('leftOpenA').addClass('leftHalf');
              $('#rightContent').removeClass('rightCloseA').addClass('rightHalf');
              $('#memoR').val('map');
              $('#rightClose').show();
              let mapTop = $('#mapMark').offset().top;
              $('body').scrollTop(mapTop);
            } else {
              $('.bookWrapper').show();
              $('.mapWrapper').hide();
              $('#rightOpen').hide();
              $('.wrapper').removeClass('leftOpenA').addClass('leftHalf');
              $('#rightContent').removeClass('rightCloseA').addClass('rightHalf');
              $('#memoR').val('bookmark');
              $('#rightClose').show();
            }
          },
          out: function(item) {
            console.log(item);
            $('.wrapper').css('overflow-y', 'visible');
          },
          over: function() {
            $('.wrapper').css('overflow-y', 'scroll');
          },
          stop: function() {
            $('.wrapper').css('overflow-y', 'scroll');
          },
          update: function() {
            $this = $(this);
            let memoId = $this.parent().find('.memoId').val();
            let bookId = $this.find('.noDragB').find('.bookId').val();
            let mapId = $this.find('.noDragM').find('.mapId').val();
            //ブックマークのカラムをToDoに紐付けたら
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
                $this.find('.noDragB').addClass('dragB').removeClass('noDragB');
              }).fail(function(XMLHttpRequest, status, e) {
                alert('fail');
              });
            }
            //マップのカラムをToDoに紐づけたら
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
                $this.find('.noDragM').addClass('dragM').removeClass('noDragM');
              }).fail(function(XMLHttpRequest, status, e) {
                alert('fail');
              });
            }
          }
        });
      }
      sortableLeft();

      //右側
      window.sortableRight = function() {
        $('.sortUl').sortable({
          opacity: 1,
          connectWith: '.dragUl',
          placeholder: 'memoDiv',
          stop: function() {
            $('.wrapper').css('overflow-y', 'scroll');
          },
          out: function() {
          },
          update: function() {
            let $this = $(this);
            let rBookId = $this.find('.dragB').find('.bookId').val();
            let rMapId = $this.find('.dragM').find('.mapId').val();
            //ブックマークのカラムを右側に戻したら
            if (rBookId != undefined) {
              $.ajax({
                type: 'POST',
                url: 'memo.php',
                data: {
                  'rBookId': rBookId,
                },
                dataType: 'json',
              }).done(function(data) {
                $this.find('.dragB').addClass('noDrag').removeClass('dragB');
              }).fail(function(XMLHttpRequest, status, e) {
                alert('fail');
              });
            }
            ///マップのカラムを右側に戻したら
            if (rMapId != undefined) {
              $.ajax({
                type: 'POST',
                url: 'memo.php',
                data: {
                  'rMapId': rMapId,
                },
                dataType: 'json',
              }).done(function(data) {
                $this.find('.dragM').addClass('noDrag').removeClass('dragM');
              }).fail(function(XMLHttpRequest, status, e) {
                alert('fail');
              });
            }
          }
        });
      }
      sortableRight();

      $('.mapWrappper').hide();

      //これは右アイコンを追加した後の処理
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

      //右矢印をクリックしたときの右ラッパーの表示処理
      $('#rightOpen').on('click', function() {
        $(this).hide();
        $('.wrapper').removeClass('leftOpenA').addClass('leftHalf');
        $('#rightContent').removeClass('rightCloseA').addClass('rightHalf');
        $('#rightClose').show();
      });

      //左矢印をクリックしたときの右ラッパーの非表示処理
      $('#rightClose').on('click', function() {
        $(this).hide();
        $('.wrapper').addClass('leftOpenA').removeClass('leftHalf');
        $('#rightContent').addClass('rightCloseA').removeClass('rightHalf');
        $('#rightOpen').show();
      });

      $('.leftOpenA').on('animationend', function() {
      });

      //ToDoのチェックをクリックしたら
      $('.wrapper').on('click', '.label', function() {
        let $this = $(this);
        let flagId = $this.parent().find('.memoId').val();
        $this.parent().parent().removeClass('memoShadow');  //ToDoカラムの影を非表示に
        $this.parent().parent().fadeOut();  //ToDoカラムを非表示に
        $.ajax({
          type: 'POST',
          url: 'memo.php',
          data: {
            'flagId': flagId
          },
          dataType: 'json'
        }).done(function(data) {

        }).fail(function(XMLHttpRequest, status, e) {

        });
      });

      //ToDoにある各カラムをクリックした時の右ラッパー表示処理
      $('.memo').find('.columnMark').on('click', function() {
        if (!($('#rightContent').hasClass('rightHalf'))) {
          $('.bookWrapper').hide();
          $('.mapWrapper').show();
          $('#rightOpen').hide();
          $('.wrapper').removeClass('leftOpenA').addClass('leftHalf');
          $('#rightContent').removeClass('rightCloseA').addClass('rightHalf');
          $('#memoR').val('map');
          $('#rightClose').show();
        } else {
          $('.bookWrapper').hide();
          $('.mapWrapper').show();
          $('#memoR').val('map');
        }
      });

      let memoSize = document.getElementById('memoSize');
      let fontSizeEle = document.getElementById('fontSize');
      let memosHeight = $('.memos').height();
      let memoWidth = $('.memo').width();
      let fontSize = $('.memos').css('font-size');
      fontSize = parseInt(fontSize);
      //ToDoカラムのスライダーを動かしたときにToDoのカラムサイズを変更s
      memoSize.addEventListener('input', function() {
        let memoVal = memoSize.value;
        $('.memo').width(function() {
          return memoWidth * memoVal;
        });
        let changeFont = fontSize * memoVal;

        $('.memos').css('font-size', changeFont + 'px');
      });

      //ToDoカラムのスライダーを動かしたときにフォントサイズを変更
      fontSizeEle.addEventListener('input', function() {
        let fontSizeValue = fontSizeEle.value;
        $('.memos').css('font-size', fontSize * fontSizeValue);
      });

      $('#setting').on('click', function() {
        $('#settingWrapper').show();
      });

      //設定ラッパーの「×」をクリックしたときの非表示処理
      $('#settingWrapper').find('.fa-times').on('click',function(){
        $('#settingWrapper').hide();
      });
    });
  </script>
  <script src="map.js"></script>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly"></script>
</body>

</html>
