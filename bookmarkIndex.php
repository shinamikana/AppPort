<div class="bookWrapper">
    <p id="bookmarkError"></p>
    <h1 id="bookmarkTitle">ブックマーク</h1>
    <div class="bookmark">
        <!-- ブックマークフォーム mark=URL linkName=リンク名 -->
        <input type="text" id="linkName" name="linkName" placeholder="お好きなリンク名">
        <input name="url" id="url" placeholder="URL(http~)">
        <input type="submit" id="submit1" value="登録"><img src="/img/load.gif" alt="" id="load1">
        <div class="bookmarkColumn"></div>
        <ul class="bookUl">
            <li class="bookLi"></li>
            <?php if (isset($showResult)) : ?>
                <?php foreach ($showResult as $show) : ?>
                    <li class="bookLi noDrag" id="<?= $show['bookmark_id'] ?>">
                        <div class="bookmarking">
                            <i class="fas fa-check"></i><i class="far fa-edit"></i><a href="<?= h($show['link']) ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?= h($show['link_name']) ?></a><i class="fas fa-times"></i><input type="text" value="<?= h($show['link_name']) ?>" class="bookNameInput"><input type="text" value="<?= h($show['link']) ?>" class="bookLinkInput"><button id="deltn1" value="<?= h($show['bookmark_id']) ?>">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?= h($show['bookmark_id']) ?>">
                            <i class="fas fa-bars"></i>
                        </div>
                        <ul class="dragUl">
                            ここにドロップ
                            <?php if (isset($resultMapBook)) : ?>
                                <?php foreach ($resultMapBook as $map_book) : ?>
                                    <?php if ($map_book['book_id'] == $show['bookmark_id']) : ?>
                                        <li>
                                            <div class="showMark dragBM">
                                                <i class="fas fa-check"></i><i class="fas fa-edit"></i><img src="/img/load.gif" alt="" class="loadGif1">
                                                <p class="columnMark"><?= h($map_book['field_name']) ?></p><input type="hidden" value="<?= h($map_book['lat']) ?>" class="mapLat"><input type="hidden" value="<?= h($map_book['lng']) ?>" class="mapLng"><input type="text" class="markInput" value="<?= h($mark['field_name']) ?>"><img src="/img/load.gif" alt="" class="loadGif"><input type="hidden" value="<?= h($map_book['map_id']) ?>" class="mapId"><i class="fas fa-bars"></i>
                                                <ul class="mapEdit">
                                                    <li class="mapDel">削除</li>
                                                </ul>
                                            </div>
                                        </li>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                            <?php if (isset($memoBookShow)) : ?>
                                <?php foreach ($memoBookShow as $memoBook) : ?>
                                    <?php if ($show['bookmark_id'] == $memoBook['book_id']) : ?>
                                        <div class="memo dragMB">
                                            <p id="mainText"><?= h($memoBook['text']) ?></p>
                                            <p id="date"><?= h($memoBook['date']) ?></p>
                                            <i class="fas fa-bars"></i><button type="submit" value="<?= $memoBook['memoId'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="<?= $memoBook['memoId'] ?>" class="memoId">
                                        </div>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
    </div>
</div>

<div id="confirmBigWrap">
    <div id="confirmWrapper">
        <div id="bookConfirm">
            <p id="alert">お知らせ</p>
            <p>URLが変更されています。</p>
            <p>このまま送信しますか？</p>
            <button id="bookConfirmNo">いいえ</button><button id="bookConfirmYes">はい</button>
        </div>
    </div>
</div>

<script>
    
</script>