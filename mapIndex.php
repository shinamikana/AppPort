<div class="mapWrapper">
  <p id="mapError"></p>
  <h1>地図</h1>
  <div id="map"></div>
  <div class="load"><img src="/img/load2.gif" alt=""></div>
  <p id="mapMark">登録地点一覧</p>
  <div class="mapColumn">
    <?php foreach ($resultMark as $mark) : ?>
      <div class="showMark noDrag">
        <div class="columns">
        <i class="fas fa-check"></i><i class="fas fa-edit"></i><img src="/img/load.gif" alt="" class="loadGif1">
        <p class="columnMark"><?= h($mark['field_name']) ?></p><input type="hidden" value="<?= h($mark['lat']) ?>" class="mapLat"><input type="hidden" value="<?= h($mark['lng']) ?>" class="mapLng"><input type="text" class="markInput" value="<?= h($mark['field_name']) ?>"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="<?= h($mark['mapId']) ?>" class="mapId"><i class="fas fa-bars"></i>
        <ul class="mapEdit">
          <li class="mapDel">削除</li>
        </ul>
        </div>
        <div class="dragUl">
          ここにドラッグ
          <?php if (isset($bookMapResult)) : ?>
            <?php foreach ($bookMapResult as $bookMap) : ?>
              <?php if ($bookMap['map_id'] == $mark['mapId']) : ?>
                <div class="bookmarking dragBM">
                  <i class="fas fa-check"></i><i class="far fa-edit"></i><a href="<?= h($bookMap['link']) ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?= h($bookMap['link_name']) ?></a><i class="fas fa-times"></i><input type="text" value="<?= h($bookMap['link_name']) ?>" class="bookNameInput"><input type="text" value="<?= h($bookMap['link']) ?>" class="bookLinkInput"><button id="deltn1" value="<?= h($bookMap['bookId']) ?>">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?= h($bookMap['bookId']) ?>">
                </div>
              <?php endif ?>
            <?php endforeach ?>
          <?php endif ?>
          <?php if (isset($memoMapResult)) : ?>
            <?php foreach ($memoMapResult as $memoMap) : ?>
              <?php if ($mark['mapId'] == $memoMap['map_id']) : ?>
                <div class="memo dragMM">
                  <p id="mainText"><?= h($memoMap['text']) ?></p>
                  <p id="date"><?= h($memoMap['date']) ?></p>
                  <i class="fas fa-bars"></i><button type="submit" value="<?= $memoMap['memoId'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="<?= $memoMap['memoId'] ?>" class="memoId">
                </div>
              <?php endif ?>
            <?php endforeach ?>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly" async></script>
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
        $('.mapColumn').prepend('<div class="showMark noDrag"><div class="columns"><i class="fas fa-check"></i><i class="fas fa-edit"></i><p class="columnMark">登録した地点</p><input type="hidden" value="' + data.lat + '" class="mapLat"><input type="hidden" value="' + data.lng + '" class="mapLng"><input type="text" class="markInput" value="登録した地点"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="' + data.insert_id + '" class="mapId"><i class="fas fa-bars"></i><ul class="mapEdit"><li class="mapDel">削除</li></ul></div><div class="dragUl">ここにドラッグ</ul></div>');
        $('.showMark').first().find('.mapEdit').slideUp(0);
        $('.columns').first().find('.fa-bars').click(function() {
          $(this).parent().find('.mapEdit').slideToggle(200);
        });
        mapDelete();
        mapEdit();
        mapCheck();
        sortableLeft();
        sortableRight();
      }).fail(function(HMLHttpRequest, status, e) {
        console.log('error number:' + XMLHttpRequest + ',status:' + status + ',thrown:' + e);
        alert('fail');
      });
    }

    mapDelete();

    function slideToggle() {
      $('.columns').find('.fa-bars').click(function() {
        $(this).parent().find('.mapEdit').slideToggle(200);
      });
    }

    slideToggle();

    $('.columns').find('.mapEdit').slideUp(0);

    function mapEdit() {
      $('.columns').find('.fa-edit').click(function() {
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
      $('.columns').find('.fa-check').on('click', function() {
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