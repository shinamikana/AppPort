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
      </div>
    <?php endforeach ?>
  </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= getenv('API_KEY_MAP') ?>&callback=initMap&v=weekly" async></script>