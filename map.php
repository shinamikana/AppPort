<?php
require_once('dateBase.php');
require_once('mapData.php');
require_once('memoData.php');
require_once('bookmarkData.php');
session_regenerate_id(TRUE);

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
$show = $mysqli->prepare('SELECT *,bookmark.id AS bookmark_id FROM bookmark LEFT JOIN book_memo ON bookmark.id = book_memo.book_id WHERE bookmark.user_id = ? ORDER BY bookmark.id DESC');
$show->bind_param('i', $_SESSION['id']);
$show->execute();
$showResult = $show->get_result();
$bookCount = 0;
while ($showResult->fetch_assoc()) {
  ++$bookCount;
}

//マップアプリでのメモカラム表示
$showMemo = $mysqli->prepare('SELECT *, memo.id AS memo_id FROM memo LEFT JOIN book_memo ON memo.id = book_memo.memo_id WHERE memo.user_id = ? ORDER BY memo.id DESC');
$showMemo->bind_param('i', $_SESSION['id']);
$showMemo->execute();
$memoResult = $showMemo->get_result();
$memoCount = 0;
while ($memoResult->fetch_assoc()) {
  ++$memoCount;
}

$showColumnBookMemo = $mysqli -> prepare('SELECT *,bookmark.id AS bookId FROM book_memo LEFT JOIN bookmark ON book_memo.book_id = bookmark.id WHERE bookmark.user_id = ?');
$showColumnBookMemo -> bind_param('i',$_SESSION['id']);
$showColumnBookMemo -> execute();
$showBMe = $showColumnBookMemo -> get_result();

$showColumnMemoBook = $mysqli -> prepare('SELECT *,memo.id AS memoId FROM book_memo LEFT JOIN memo ON book_memo.memo_id = memo.id WHERE memo.user_id = ?');
$showColumnMemoBook -> bind_param('i',$_SESSION['id']);
$showColumnMemoBook -> execute();
$showMeB = $showColumnMemoBook -> get_result();

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

  <?php require_once('miniLogo.php'); ?>
  <main>
    <?php require_once('mapIndexL.php'); ?>
    <div id="select">
      <div id="emptySelect">

      </div>
      <select name="bookR" id="bookR">
        <option value="memo">メモ(<?php echo $memoCount ?>)</option>
        <option value="bookmark">ブックマーク(<?php echo $bookCount ?>)</option>
      </select>
    </div>
    <?php require_once('bookmarkIndex.php'); ?>
    <?php require_once('memoIndex.php'); ?>
  </main>

  <script src="memo.js"></script>
  <script src="book.js"></script>
  <script>
  let map;

  //地図の読み込み関数
  function initMap() {
    alert('ok!');
    let lat = 34.73373029238828;
    let lng = 135.50025469752734;
    map = new google.maps.Map(document.getElementById("map"), {
      center: {
        lat: lat,
        lng: lng
      },
      zoom: 8,
    });
    window.mapInfo = map;
    google.maps.event.addListener(map, 'click', event => clickListener(event, map));
  }

  //マーカーをクリックで削除処理
  function markerDelete(event, marker) {
    marker.setMap(null);
  }

  //地図をクリックでマーカー追加
  function clickListener(event, map) {
    const lat = event.latLng.lat();
    const lng = event.latLng.lng();
    const marker = new google.maps.Marker({
      position: {
        lat,
        lng
      },
      map
    });
    insert(lat, lng);
    google.maps.event.addListener(marker, 'click', event => markerDelete(event, marker));

  }

  $(() => {
    function mapDelete() {
      $('.mapDel').on('click', function() {
        let mapDel = $(this).parent().parent().find('.mapId').val();
        $('.mapEdit').hide();
        $('.loadGif').show();
        $('.fa-bars').hide();
        $('.fa-edit').hide();
        let $this = $(this);
        $.ajax({
          type: 'POST',
          url: 'map.php',
          data: {
            'mapDel': mapDel
          },
          dataType: 'json',
        }).done(function(data) {
          $this.parent().parent().parent().remove();
          $('.loadGif').hide();
          $('.fa-bars').show();
          $('.fa-edit').show();
        }).fail(function(XMLHttpRequest, status, e) {
          alert('fail');
        });
      });
    }

    window.insert = function(lat, lng) {
      $('.load').css({
        'display': 'flex'
      });
      $.ajax({
        type: 'POST',
        url: 'map.php',
        data: {
          'lat': lat,
          'lng': lng
        },
        dataType: 'json',
      }).done(function(data) {
        $('.load').hide();
        $('.mapColumn').prepend('<div class="showMark noDrag"><div class="columns"><i class="fas fa-check"></i><i class="fas fa-edit"></i><p class="columnMark">登録した地点</p><input type="hidden" value="' + data.lat + '" class="mapLat"><input type="hidden" value="' + data.lng + '" class="mapLng"><input type="text" class="markInput" value="登録した地点"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="' + data.insert_id + '" class="mapId"><i class="fas fa-bars"></i><ul class="mapEdit"><li class="mapDel">削除</li></ul></div></div>');
        $('.showMark').first().find('.mapEdit').slideUp(0);
        $('.fa-bars').first().click(function() {
          $(this).parent().find('.mapEdit').slideToggle(200);
        });
        mapDelete();
        mapEdit();
        mapCheck();
      }).fail(function(HMLHttpRequest, status, e) {
        console.log('error number:' + XMLHttpRequest + ',status:' + status + ',thrown:' + e);
        alert('fail');
      });
    }

    mapDelete();

    function slideToggle() {
      $('.showMark').find('.fa-bars').click(function() {
        $(this).parent().find('.mapEdit').slideToggle(200);
      });
    }

    slideToggle();

    $('.showMark').find('.mapEdit').slideUp(0);

    function mapEdit() {
      $('.showMark').find('.fa-edit').click(function() {
        let val = $(this).parent().find('.columnMark').text();
        $(this).parent().find('.columnMark').hide();
        $(this).parent().find('.markInput').show();
        $(this).parent().find('.markInput').val(val);
        $(this).hide();
        $(this).parent().find('.fa-check').show();
      });
    }

    mapEdit();

    function mapCheck() {
      $('.showMark').find('.fa-check').on('click', function() {
        let $this = $(this);
        mapEditDone($this);
      });
    }

    mapCheck();

    $('.markInput').keypress(function(e) {
      if (e.keyCode == 13) {
        let $this = $(this).parent().find('.fa-check');
        mapEditDone($this);
      }
    });

    function mapEditDone($this) {
      let mapEdit = $this.parent().find('.markInput').val();
      let mapEditId = $this.parent().find('.mapId').val();
      let mapText = $this.parent().find('.columnMark').text();
      $this.parent().find('.loadGif1').show();
      $this.hide();
      $this.parent().find('.markInput').hide();
      $this.parent().find('.columnMark').show();
      console.log(mapEdit);
      console.log(mapText);
      if (mapEdit === mapText) {
        $this.parent().find('.loadGif1').hide();
        $this.parent().find('.fa-edit').show();
      } else {
        if (!mapEdit) {
          $this.parent().find('.loadGif1').hide()
          $this.show();
          $this.parent().find('.markInput').show();
          $this.parent().find('.columnMark').hide();
          $('#mapError').text('地点名を入力してください！').show().delay(2000).queue(function() {
            $(this).hide().dequeue();
          });
        } else {
          $.ajax({
            type: 'POST',
            url: 'map.php',
            data: {
              'mapEdit': mapEdit,
              'mapEditId': mapEditId
            },
            dataType: 'json',
          }).done(function(data) {
            $this.parent().find('.loadGif1').hide();
            $this.parent().find('.columnMark').text(mapEdit);
            $this.parent().find('.markInput').hide();
            $this.parent().find('.columnMark').show();
            $this.parent().find('.fa-edit').show();
            $this.hide();
            $('.showMark').find('.mapEdit').slideUp(0);
          }).fail(function() {
            alert('fail');
          });
        }
      }
    }

    //カラムがクリックされた時のマーカー追加とズーム処理
    window.mapColumnClick = function() {
      $('.columnMark').on('click', function() {
        let lat = $(this).parent().find('.mapLat').val();
        let lng = $(this).parent().find('.mapLng').val();
        let map = mapInfo;
        //latとlngを浮動小数点に変換  *これがなければエラー
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        console.log(map);
        const marker = new google.maps.Marker({
          position: {
            lat,
            lng
          },
          map
        });
        map.panTo(new google.maps.LatLng(lat, lng));
        map.setZoom(12);
      });
    }

    mapColumnClick();
  });
</script>
  <script>
    $(function() {
      window.sortableLeft = function() {
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
            let mapId = $this.find('.drag').find('.mapId').val();
            if (mapId != undefined) {
              $.ajax({
                type: 'POST',
                url: 'map.php',
                data: {
                  'mapId': mapId
                },
                dataType: 'json',
              }).done(function(data) {
                $this.find('.drag').addClass('noDrag').removeClass('drag');
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
                $this.find('.noDrag').addClass('drag').removeClass('noDrag');
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
                $this.find('.noDrag').addClass('drag').removeClass('noDrag');
              }).fail(function(XMLHttpRequest, status, e) {
                alert('fail');
              });
            }
          }
        });
      }
      sortableRight();

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
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly"></script>
</body>

</html>