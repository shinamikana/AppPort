<div class="bookWrapper">
    <p id="bookmarkError"></p>
    <h1 id="bookmarkTitle">ブックマーク</h1>
    <div class="bookmark">
        <!-- ブックマークフォーム　mark=URL linkName=リンク名 -->
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
    $(function() {
        $('#bookConfirmNo').click(function() {
            $('#confirmBigWrap').hide();
        });

        const errorProcess = function(error) {
            $('#bookmarkError').text(error).show().delay(2000).queue(function() {
                $(this).hide().dequeue();
            });
        }

        //ブックマークの送信処理
        $('#submit1').on('click', function(event) {
            let urlVal = $('#url').val();
            let linkNameVal = $('#linkName').val();
            $('#submit1').hide();
            $('#load1').show();
            console.log(urlVal);
            if (!urlVal && !linkNameVal) {
                errorProcess('URLとリンク名が入力されていません！');
                $('#submit1').show();
                $('#load1').hide();
            } else if (!urlVal) {
                errorProcess('URLが入力されていません！');
                $('#submit1').show();
                $('#load1').hide();
            } else if (!linkNameVal) {
                errorProcess('リンク名が入力されていません！');
                $('#submit1').show();
                $('#load1').hide();
            } else if (!urlVal.match('http')) {
                console.log(urlVal);
                errorProcess('URLが正しくありません！');
                $('#submit1').show();
                $('#load1').hide();
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'bookmark.php',
                    data: {
                        'url': urlVal,
                        'linkName': linkNameVal
                    },
                    dataType: 'json',
                }).done(function(data) {
                    $('.bookUl').prepend('<li class="bookLi noDrag" id="' + data.id + '"><div class="bookmarking"><i class="fas fa-check"></i><i class="far fa-edit"></i><a href="' + data.url + '" target="_blank" rel="noopener noreferrer" class="bookA">' + data.linkName + '</a><i class="fas fa-times"></i><input type="text" value="' + data.linkName + '" class="bookNameInput"><input type="text" value="' + data.url + '" class="bookLinkInput"><button id="deltn1" value="' + data.id + '">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="' + data.id + '"><i class="fas fa-bars"></i></div></li>');
                    $('#submit1').show();
                    $('#load1').hide();
                    $('#url').val('');
                    $('#linkName').val('');
                    $delete();
                    $('.bookmarking').find('.fa-bars').first().click(function() {
                        $(this).parent().find('#deltn1').slideToggle();
                    });
                    bookEdit();
                    bookCancel();
                    bookSubmit();
                }).fail(function(XMLHttpRequest, status, e) {
                    console.log('error number:' + XMLHttpRequest + ',status:' + status + ',thrown:' + e);
                    alert('fail');
                    $('#submit1').show();
                    $('#load1').hide();
                });
            }
        });

        //ブックマークの削除処理
        let $delete = function() {
            $('.bookmarking').find('#deltn1').on('click', function(event) {
                let $this = $(this).parent();
                $this.css({
                    opacity: '0.5'
                });
                $('.bookmarking').find('.fa-bars').hide();
                $('.deload1').show();
                let delId = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: 'bookmark.php',
                    data: {
                        'delId': delId
                    },
                    dataType: 'json',
                }).done(function(data) {
                    $this.hide();
                    $('.deload1').hide();
                    $('.bookmarking').find('.fa-bars').show();
                }).fail(function(XMLHttpRequest, status, e) {
                    console.log('error number:' + XMLHttpRequest + ',status:' + status + ',thrown:' + e);
                    $this.css({
                        opacity: '1'
                    });
                    $('.deload1').hide();
                });
            });
        }

        $delete();

        const bookEdit = function() {
            $('.fa-edit').click(function() {
                $(this).hide();
                $(this).parent().find('.bookA').hide();
                $(this).parent().find('.bookNameInput').show();
                $(this).parent().find('.bookLinkInput').show();
                $(this).parent().find('.fa-check').show();
                $(this).parent().find('.fa-bars').hide();
                $(this).parent().find('.fa-times').show();
            });
        }
        bookEdit();

        //編集完了プロセス
        const bookEditShow = function($this) {
            $this.hide();
            $this.parent().find('.fa-edit').show();
            $this.parent().find('.bookNameInput').hide();
            $this.parent().find('.bookA').show();
            $this.parent().find('.bookLinkInput').hide();
            $this.parent().css('opacity', '1');
            $this.parent().find('.fa-bars').show();
            $this.parent().find('.fa-times').hide();
            $this.parent().find('.fa-check').hide();
        }

        //編集を中止した場合の処理
        const bookCancel = function() {
            $('.fa-times').click(function() {
                let $this = $(this);
                let bookName = $this.parent().find('.bookA').text();
                let bookLink = $this.parent().find('.bookA').attr('href');
                $this.parent().find('.bookNameInput').val(bookName);
                $this.parent().find('.bookLinkInput').val(bookLink);
                bookEditShow($this);
            });
        }
        bookCancel();

        //編集を送信する処理
        const bookSubmit = function() {
            $('.fa-check').click(function() {
                let $this = $(this);
                let bookNameVal = $this.parent().find('.bookNameInput').val();
                let bookLinkVal = $this.parent().find('.bookLinkInput').val();
                let bookName = $this.parent().find('.bookA').text();
                let bookLink = $this.parent().find('.bookA').attr('href');
                let bookId = $this.parent().find('.bookId').val();
                //入力欄が空白の場合の処理
                if (bookNameVal == '') {
                    errorProcess('ブックマーク名を入力してください！');
                }
                if (bookLinkVal == '') {
                    errorProcess('URLを入力してください！');
                }

                if (bookNameVal != bookName && bookLinkVal != bookLink) {
                    $('#confirmBigWrap').show();
                    $('#bookConfirmYes').click(function() {
                        $this.parent().css('opacity', '0.5');
                        $('#confirmBigWrap').hide();
                        $.ajax({
                            type: 'POST',
                            url: 'bookmark.php',
                            data: {
                                'bookName': bookNameVal,
                                'bookLink': bookLinkVal,
                                'bookId': bookId
                            },
                            dataType: 'json',
                        }).done(function(data) {
                            $this.parent().find('.bookA').text(bookNameVal);
                            $this.parent().find('.bookA').attr('href', bookLinkVal);
                            bookEditShow($this);
                        }).fail(function(XMLHttpRequest, status, e) {});
                    });

                } else if (bookNameVal != bookName) {
                    $this.parent().css('opacity', '0.5');
                    $.ajax({
                        type: 'POST',
                        url: 'bookmark.php',
                        data: {
                            'bookName': bookNameVal,
                            'bookId': bookId
                        },
                        dataType: 'json',
                    }).done(function(data) {
                        $this.parent().find('.bookA').text(bookNameVal);
                        bookEditShow($this);
                        $this.parent().css('opacity', '1');
                    }).fail(function(XMLHttpRequest, status, e) {});

                } else if (bookLinkVal != bookLink) {
                    $('#confirmBigWrap').show();
                    $('#bookConfirmYes').click(function() {
                        $this.parent().css('opacity', '0.5');
                        $('#confirmBigWrap').hide();
                        $.ajax({
                            type: 'POST',
                            url: 'bookmark.php',
                            data: {
                                'bookLink': bookLinkVal,
                                'bookId': bookId
                            },
                            dataType: 'json',
                        }).done(function(data) {
                            $this.parent().find('.bookA').attr('href', bookLinkVal);
                            bookEditShow($this);
                        }).fail(function(XMLHttpRequest, status, e) {});
                    });
                } else {
                    bookEditShow($this);
                }
            });
        }
        bookSubmit();

        $('.bookmarking').find('.fa-bars').click(function() {
            $(this).parent().find('#deltn1').slideToggle();
        });

    });
</script>