$(function() {
    let $memoDel = function() {
        $('.memos').find('#delbtn').on('click', function() {
            let delId = $(this).val();
            $('.memos').find('#delbtn').hide();
            $('#deload').show();
            let $this = $(this).parent()
            $this.css({
                opacity: '0.5'
            });
            $.ajax({
                type: 'POST',
                url: 'memoData.php',
                data: {
                    'del': delId
                },
                dataType: 'json',
            }).done(function(data) {
                $this.hide();
                $('#deload').hide();
            }).fail(function(XMLHttpRequest, status, e) {
                $this.css({
                    opacity: '1'
                });
            });
        });
    }
    $memoDel();

    $('#submit').on('click', function(event) {
        let val = $('#text').val();
        $('#submit').hide();
        $('#load').show();
        $.ajax({
            type: 'POST',
            url: 'memoData.php',
            data: {
                'text': val
            },
            dataType: 'json',
        }).done(function(data) {
            $('#text').val('');
            $('#memoWrapper').prepend('<div class="memo"><p id="mainText"><span>' + val + '</span></p><p id="date">' + data.date + '</p><button type="submit" value="' + data.insert + '" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="' + data.insert + '" class="memoId"><ul class="dragUl">ここにドロップ</ul></div>');
            $('#memoWrapper').prepend('<div class="memo memoShadow" data-toggle="buttons"><div class="memos"><i class="fas fa-map-pin"></i><p id="mainText"><?= h($memo[' + val +']) ?></p><p id="date"><?= h($memo[' + data.date + ']) ?></p><i class="fas fa-bars"></i><input type="checkbox" id="check<?= $memo['+ data.insert +'] ?>"><label for="check<?= $memo['+ data.insert +'] ?>" class="label"></label><button type="submit" value="<?= $memo['+ data.insert +'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="<?= $memo['+ data.insert +'] ?>" class="memoId"></div><ul class="dragUl">ここにドロップ</ul></div>');
            $('#load').hide();
            $('#submit').show();
            $memoDel();
            sortableLeft();
            sortableRight();
            $('#byte').text('0/500');
        }).fail(function(XMLHttpRequest, status, e) {
            $('#memoWrapper').find('p').remove();

        });
    });

    $('.memos').find('.fa-bars').click(function() {
        $(this).parent().find('#delbtn').slideToggle(0);
    });

    $('.wrapper').find('.noDragUl').find('.showMark').on('click', function () {
        let ostTop = $('#mapH1').offset().top + 50;
        $('html,body').animate({scrollTop:ostTop},0);
        $('.mapWrapper').show();
        $('.wrapper').hide();
        $('#selectR').val('map');
    });
});
