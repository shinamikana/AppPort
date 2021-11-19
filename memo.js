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
                url: 'memo.php',
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
            url: 'memo.php',
            data: {
                'text': val
            },
            dataType: 'json',
        }).done(function(data) {
            $('#text').val('');
            $('#memoWrapper').prepend('<div class="memo"><p id="mainText"><span>' + val + '</span></p><p id="date">' + data.date + '</p><button type="submit" value="' + data.insert + '" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="' + data.insert + '" class="memoId"><ul class="dragUl">ここにドロップ</ul></div>');
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
        $(this).parent().find('#delbtn').slideToggle();
    });

});