<div class="mapWrapper">
    <p id="mapError"></p>
    <h1>地図</h1>
    <div id="map"></div>
    <div class="load"><img src="/img/load2.gif" alt=""></div>
    <p id="mapMark">登録地点一覧</p>
    <div class="mapColumn">
        <?php foreach ($resultMark as $mark) : ?>
            <div class="showMark noDrag">
                <i class="fas fa-check"></i><i class="fas fa-edit"></i><img src="/img/load.gif" alt="" class="loadGif1">
                <p class="columnMark"><?php echo h($mark['field_name']) ?></p><input type="hidden" value="<?php echo h($mark['lat']) ?>" class="mapLat"><input type="hidden" value="<?php echo h($mark['lng']) ?>" class="mapLng"><input type="text" class="markInput" value="<?php echo h($mark['field_name']) ?>"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="<?php echo h($mark['id']) ?>" class="mapId"><i class="fas fa-bars"></i>
                <ul class="mapEdit">
                    <li class="mapDel">削除</li>
                </ul>
            </div>
        <?php endforeach ?>
    </div>
</div>