<div class="bookWrapper">
    <p id="bookmarkError"></p>
    <h1 id="bookmarkTitle">ブックマーク</h1>
    <div class="bookmark">
        <!-- ブックマークフォーム mark=URL linkName=リンク名 -->
        <input type="text" id="linkName" name="linkName" placeholder="お好きなリンク名">
        <input name="url" id="url" placeholder="URL(http~)">
        <input type="submit" id="submit1" value="登録"><img src="/img/load.gif" alt="" id="load1">
        <div class="bookmarkColumn"></div>
        <ul class="sortUl">
            <li class="bookLi"></li>
            <?php if (isset($showResult)) : ?>
                <?php foreach ($showResult as $show) : ?>
                    <li class="bookLi noDrag" id="<?= $show['bookmark_id'] ?>">
                        <div class="bookmarking">
                            <i class="fas fa-check"></i><i class="far fa-edit"></i><a href="<?= h($show['link']) ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?= h($show['link_name']) ?></a><i class="fas fa-times"></i><input type="text" value="<?= h($show['link_name']) ?>" class="bookNameInput"><input type="text" value="<?= h($show['link']) ?>" class="bookLinkInput"><button id="deltn1" value="<?= h($show['bookmark_id']) ?>">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?= h($show['bookmark_id']) ?>">
                            <i class="fas fa-bars"></i>
                        </div>
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
