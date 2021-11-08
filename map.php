<?php
include('dateBase.php');
include('mapData.php');
include('memoData.php');
include('bookmarkData.php');

//マップアプリのカラム表示
$showMark = $mysqli->prepare('SELECT *,map.id AS mapId FROM map LEFT JOIN map_memo ON map.id = map_memo.map_id LEFT JOIN map_bookmark ON map.id = map_bookmark.map_id WHERE user_id = ? AND map_memo.id IS NULL AND map_bookmark.id IS NULL ORDER BY mapId DESC');
$showMark->bind_param('i', $_SESSION['id']);
$showMark->execute();
$resultMark = $showMark->get_result();
$mapCount = 0;
while ($resultMark->fetch_assoc()) {
    ++$mapCount;
}

$showMapMemo = $mysqli->prepare('SELECT* ,map_memo.id AS mapMemoId FROM map_memo LEFT JOIN map ON map_memo.map_id = map.id WHERE map.user_id = ?');
$showMapMemo->bind_param('i', $_SESSION['id']);
$showMapMemo->execute();
$mapMemoResult = $showMapMemo->get_result();

$showMapBook = $mysqli->prepare('SELECT *,map.id AS mapId FROM map_bookmark LEFT JOIN map ON map_bookmark.map_id = map.id WHERE map.user_id = ?');
$showMapBook->bind_param('i', $_SESSION['id']);
$showMapBook->execute();
$resultMapBook = $showMapBook->get_result();

//ブックマーク表示SQL
$show = $mysqli -> prepare('SELECT *,bookmark.id AS bookmark_id FROM bookmark LEFT JOIN book_memo ON bookmark.id = book_memo.book_id WHERE bookmark.user_id = ? AND book_memo.id IS NULL ORDER BY bookmark.id DESC');
$show -> bind_param('i',$_SESSION['id']);
$show -> execute();
$showResult = $show -> get_result();
$bookCount = 0;
while($showResult -> fetch_assoc()){
    ++$bookCount;
}

//マップアプリでのメモカラム表示
$showMemo = $mysqli -> prepare('SELECT *, memo.id AS memo_id FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id WHERE memo.user_id = ? AND book_memo.id IS NULL ORDER BY memo.id DESC');
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

function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>地図</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css">
  <link rel="stylesheet" href="/css/mapL.css">
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/css/memoIndex.css">
  <link rel="stylesheet" href="/css/bookmarkIndex.css">
</head>

<body>

  <?php include('miniLogo.php'); ?>
  <main>
    <?php include('mapIndexL.php'); ?>
    <div id="select">
      <div id="emptySelect">

      </div>
      <select name="bookR" id="bookR">
        <option value="memo">メモ(<?php echo $memoCount ?>)</option>
        <option value="bookmark">ブックマーク(<?php echo $bookCount ?>)</option>
      </select>
    </div>
    <?php include('bookmarkIndex.php'); ?>
    <?php include('memoIndex.php'); ?>
  </main>

  <script>
    $(function() {



      $('.mapColumn').sortable({
        connectWith: '.dragUl',
        placeholder: 'memoDiv',
        out: function() {
          $('.mapWrapper').css('overflow-y', 'visible');
        },
        over: function() {
          $('.mapWrapper').css('overflow-y', 'scroll');
        },
        stop: function() {
          $('.mapWrapper').css('overflow-y', 'scroll');
        },
        scroll: false,
        update: function() {
          let $this = $(this);
          let mapId = $this.find('.dragBMe').find('.mapId').val();
          let mapBookId = $this.find('.dragBM').find('.mapId').val();
          if (mapId != undefined) {
            $.ajax({
              type: 'POST',
              url: 'map.php',
              data: {
                'mapId': mapId
              },
              dataType: 'json',
            }).done(function(data) {
              alert('done');
              $this.find('.dragBMe').addClass('noDrag').removeClass('dragBMe');
            }).fail(function(XMLHttpRequest, status, e) {
              alert('fail');
            })
          }
          if (mapBookId != undefined) {
            $.ajax({
              type: 'POST',
              url: 'map.php',
              data: {
                'mapBookId': mapBookId
              },
              dataType: 'json',
            }).done(function(data) {
              alert('done');
              $this.find('.dragBM').addClass('noDrag').removeClass('dragBM');
            }).fail(function(XMLHttpRequest, status, e) {
              alert('fail');
            })
          }
        }
      });

      $('.dragUl').sortable({
        connectWith: '.mapColumn',
        placeholder: 'memoDiv',
        stop: function() {
          $('.mapWrapper').css('overflow-y', 'scroll');
        },
        update: function() {
          let $this = $(this);
          let mapVal = $this.find('.noDrag').find('.mapId').val();
          let memoVal = $this.parent().find('.memoId').val();
          let bookVal = $this.parent().find('.bookId').val();
          console.log(mapVal);
          console.log(bookVal);
          if (mapVal != undefined && memoVal != undefined) {
            $.ajax({
              type: 'POST',
              url: 'map.php',
              data: {
                'mapVal': mapVal,
                'memoVal': memoVal
              },
              dataType: 'json',
            }).done(function(data) {
              alert('done');
              $this.find('.noDrag').addClass('dragBMe').removeClass('noDrag');
            }).fail(function(XMLHttpRequest, status, e) {
              alert('fail');
            });
          }

          if (mapVal != undefined && bookVal != undefined) {
            $.ajax({
              type: 'POST',
              url: 'map.php',
              data: {
                'mapVal': mapVal,
                'bookVal': bookVal
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

      $('#bookR').on('change', function() {
        let selectVal = $(this).val();
        console.log(selectVal);
        if (selectVal === 'bookmark') {
          $('.bookWrapper').show();
          $('.wrapper').hide();
        }

        if (selectVal === 'memo') {
          $('.bookWrapper').hide();
          $('.wrapper').show();
        }
      });

    });

    $('.bookWrapper').hide();
  </script>
</body>

</html>