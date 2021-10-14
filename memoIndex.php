<div class="wrapper">
<h1 id="memoTitle">メモ</h1>
        <div class="column">
            <div class="delete">
                <textarea type="text" name="memo" id="text" placeholder="本文" onkeyup="byteCount()"></textarea><button id="submit">送信</button><img src="/img/load.gif" alt="" id="load"><span id="byte"></span>
            </div>
            <div id="memoWrapper">
                
                <?php if(isset($memoResult)): ?>
                    <?php foreach($memoResult as $memo): ?>
                        <div class="memo">
                            <p id="mainText"><span><?php echo h($memo['text']) ?></span></p>
                            <p id="date"><?php echo h($memo['date']) ?></p>
                            <button type="submit" value="<?php echo $memo['id'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload">
                            <ul class="dragUl">ここにドロップ</ul>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
        </div>
    </div>
    <script>$(function(){
        let $memoDel = function(){
            $('.memo').find('#delbtn').on('click',function(){
            let delId = $(this).val();
            $('.memo').find('#delbtn').hide();
            $('#deload').show();
            let $this = $(this).parent()
            $this.css({opacity:'0.5'});
            $.ajax({
                type:'POST',
                url:'memo.php',
                data:{'del':delId},
                dataType:'json',
            }).done(function(data){
                $this.hide();
                $('.memo').find('#delbtn').show();
                $('#deload').hide();
            }).fail(function(XMLHttpRequest,status,e){
                $this.css({opacity:'1'});
            });
        });
        }
        $memoDel();

        $('#submit').on('click',function(event){
            let val = $('#text').val();
            $('#submit').hide();
            $('#load').show();
            $.ajax({
                type:'POST',
                url:'memo.php',
                data:{'text':val},
                dataType:'json',
            }).done(function(data){
                $('#text').val('');
                $('#memoWrapper').prepend('<div class="memo"><p id="mainText"><span>'+val+'</span></p><button type="submit" value="'+data.insert+'" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"></div>');
                $('#load').hide();
                $('#submit').show();
                $memoDel();
            }).fail(function(XMLHttpRequest,status,e){
                $('#memoWrapper').find('p').remove();
                
            });
        });

    });
    </script>




    