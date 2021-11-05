<?php
include('dateBase.php');
include('mapData.php');
include('memoData.php');
include('bookmarkData.php');

$showMapMemo = $mysqli -> prepare('SELECT* ,map_memo.id AS mapMemoId FROM map_memo LEFT JOIN map ON map_memo.map_id = map.id WHERE map.user_id = ?');
$showMapMemo -> bind_param('i',$_SESSION['id']);
$showMapMemo -> execute();
$mapMemoResult = $showMapMemo -> get_result();

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
  <link rel="stylesheet" href="/css/map.css">
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Tokumin:wght@500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/css/memoIndex.css">
  <link rel="stylesheet" href="/css/bookmarkIndex.css">
</head>

<body>

  <?php include('miniLogo.php'); ?>
  <main>
    <?php include('mapIndex.php'); ?>
    <div id="select">
      <div id="emptySelect">

      </div>
      <select name="bookR" id="bookR">
        <option value="memo">メモ</option>
        <option value="bookmark">ブックマーク</option>
      </select>
    </div>
    <?php include('bookmarkIndex.php'); ?>
    <?php include('memoIndex.php'); ?>
  </main>

  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly" async></script>
  <script>
    let map;

    //地図の読み込み関数
    function initMap() {
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


    $(function() {

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
            $this.parent().parent().remove();
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
          $('.mapColumn').prepend('<div class="showMark"><i class="fas fa-check"></i><i class="fas fa-edit"></i><p class="columnMark">登録した地点</p><input type="hidden" value="' + data.lat + '" class="mapLat"><input type="hidden" value="' + data.lng + '" class="mapLng"><input type="text" class="markInput" value="登録した地点"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="' + data.insert_id + '" class="mapId"><i class="fas fa-bars"></i><ul class="mapEdit"><li class="mapDel">削除</li></ul></div>');
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
          if(mapBookId != undefined){
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

      $('#bookR').on('change',function(){
        let selectVal = $(this).val();
        console.log(selectVal);
        if(selectVal === 'bookmark'){
          $('.bookWrapper').show();
          $('.wrapper').hide();
        }

        if(selectVal === 'memo'){
          $('.bookWrapper').hide();
          $('.wrapper').show();
        }
      });

    });

    $('.bookWrapper').hide();
  </script>
</body>

</html>