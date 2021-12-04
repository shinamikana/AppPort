<div class="mapWrapper">
  <p id="mapError"></p>
  <h1 id="mapH1">地図</h1>
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
                  <i class="fas fa-bars"></i>
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
        <ul id="noDragUl">
          <?php if (isset($resultShowMemoBook)) : ?>
            <?php foreach ($resultShowMemoBook as $show) : ?>
              <?php if ($mark['book_id'] == $show['bookId']) : ?>
                <div class="bookmarking">
                  <a href="<?= h($show['link']) ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?= h($show['link_name']) ?></a><i class="fas fa-times"></i><input type="text" value="<?= h($show['link_name']) ?>" class="bookNameInput"><input type="text" value="<?= h($show['link']) ?>" class="bookLinkInput"><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?= h($show['bookId']) ?>">
                </div>
              <?php endif ?>
            <?php endforeach ?>
          <?php endif ?>
          <?php if (isset($showCMeM)) : ?>
            <?php foreach ($showCMeM as $memoMap) : ?>
              <?php if ($mark['memo_id'] == $memoMap['memoId']) : ?>
                <div class="memo">
                  <p id="mainText"><?= h($memoMap['text']) ?></p>
                  <p id="date"><?= h($memoMap['date']) ?></p>
                  <input type="hidden" value="<?= $memoMap['memoId'] ?>" class="memoId">
                </div>
              <?php endif ?>
            <?php endforeach ?>
          <?php endif ?>
        </ul>
      </div>
    <?php endforeach ?>
  </div>
</div>