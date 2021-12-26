<div class="wrapper">
  <h1 id="memoTitle">ToDo</h1>
  <div class="column">
    <div class="delete">
      <textarea type="text" name="memo" id="text" placeholder="本文" onkeyup="byteCount()"></textarea><button id="submit">書き留める</button><img src="/img/load.gif" alt="" id="load"><span id="byte"></span>
    </div>
    <div id="memoWrapper">
      <?php if (isset($memoResult)) : ?>
        <?php foreach ($memoResult as $memo) : ?>
          <div class="memo">
            <div class="memos">
              <p id="mainText"><?= h($memo['text']) ?></p>
              <p id="date"><?= h($memo['date']) ?></p>
              <i class="fas fa-bars"></i>
              <button type="submit" value="<?= $memo['memo_id'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="<?= $memo['memo_id'] ?>" class="memoId">
            </div>
            <ul class="dragUl">ここにドロップ
              <?php if (isset($memoMapResult)) : ?>
                <?php foreach ($memoMapResult as $map_memo) : ?>
                  <?php if ($map_memo['memo_id'] == $memo['memo_id']) : ?>
                    <div class="showMark drag">
                      <div class="columns">
                        <i class="fas fa-check"></i><i class="fas fa-edit"></i><img src="/img/load.gif" alt="" class="loadGif1">
                        <p class="columnMark"><?= h($map_memo['field_name']) ?></p><input type="hidden" value="<?= h($map_memo['lat']) ?>" class="mapLat"><input type="hidden" value="<?= h($map_memo['lng']) ?>" class="mapLng"><input type="text" class="markInput" value="<?= h($map_memo['field_name']) ?>"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="<?= h($map_memo['id']) ?>" class="mapId"><i class="fas fa-bars"></i>
                        <ul class="mapEdit">
                          <li class="mapDel">削除</li>
                        </ul>
                      </div>
                    </div>
                  <?php endif ?>
                <?php endforeach ?>
              <?php endif ?>
              <?php if (isset($memoBookShow)) : ?>
                <?php foreach ($memoBookShow as $memo_book) : ?>
                  <?php if ($memo_book['memo_id'] == $memo['memo_id']) : ?>
                    <li class="bookLi drag" id="<?= $memo['id'] ?>">
                      <div class="bookmarking">
                        <i class="fas fa-check"></i><i class="far fa-edit"></i><i class="fas fa-times"></i><a href="<?= $memo_book['link'] ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?= $memo_book['link_name'] ?></a><input type="text" value="<?= h($memo_book['link_name']) ?>" class="bookNameInput"><input type="text" value="<?= $memo_book['link'] ?>" class="bookLinkInput"><button id="deltn1" value="<?= $memo_book['id'] ?>">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?= h($memo_book['id']) ?>">
                        <i class="fas fa-bars"></i>
                      </div>
                    </li>
                  <?php endif ?>
                <?php endforeach ?>
              <?php endif ?>
          </div>
        <?php endforeach ?>
      <?php endif ?>
    </div>
  </div>
</div>
