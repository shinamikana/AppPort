<div class="wrapper">
    <h1 id="memoTitle">メモ</h1>
    <div class="column">
        <div class="delete">
            <textarea type="text" name="memo" id="text" placeholder="本文" onkeyup="byteCount()"></textarea><button id="submit">書き留める</button><img src="/img/load.gif" alt="" id="load"><span id="byte"></span>
        </div>
        <div id="memoWrapper">
            <?php if (isset($memoResult)) : ?>
                <?php foreach ($memoResult as $memo) : ?>
                    <div class="memo noDrag">
                        <p id="mainText"><?= h($memo['text']) ?></p>
                        <p id="date"><?= h($memo['date']) ?></p>
                        <i class="fas fa-bars"></i><button type="submit" value="<?= $memo['memo_id'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="<?= $memo['memo_id'] ?>" class="memoId">
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
</div>