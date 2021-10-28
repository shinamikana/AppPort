<div class="wrapper">
    <h1 id="memoTitle">メモ</h1>
    <div class="column">
        <div class="delete">
            <textarea type="text" name="memo" id="text" placeholder="本文" onkeyup="byteCount()"></textarea><button id="submit">書き留める</button><img src="/img/load.gif" alt="" id="load"><span id="byte"></span>
        </div>
        <div id="memoWrapper">
            <?php if(isset($memoResult)): ?>
                <?php foreach($memoResult as $memo): ?>
                    <div class="memo">
                        <p id="mainText"><?php echo h($memo['text']) ?></p>
                        <p id="date"><?php echo h($memo['date']) ?></p>
                        <button type="submit" value="<?php echo $memo['memo_id'] ?>" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><input type="hidden" value="<?php echo $memo['memo_id'] ?>" class="memoId">
                        <ul class="dragUl">ここにドロップ
                            <?php foreach($memoBookResult as $memo_book): ?>
                                <?php if($memo_book['memo_id'] == $memo['id']): ?>
                                    <li class="bookLi drag" id="<?php echo $memo['id'] ?>">
                                        <div class="bookmarking">      
                                            <i class="fas fa-check"></i><i class="far fa-edit"></i><i class="fas fa-times"></i><a href="<?php echo $memo_book['link'] ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?php echo $memo_book['link_name'] ?></a><input type="text" value="<?php echo h($memo_book['link_name']) ?>" class="bookNameInput"><input type="text" value="<?php echo $memo_book['link'] ?>" class="bookLinkInput"><button id="deltn1" value="<?php echo $memo_book['id'] ?>">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?php echo h($memo_book['id']) ?>">
                                        </div>
                                    </li>
                                <?php endif ?>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
</div>
    <script>
    document.getElementById('byte').innerText = '0/500';
        const byteCount = function(){
            const memoByte = document.getElementById('text').value;
            let byte = (new Blob([memoByte])).size;
            document.getElementById('byte').innerText = `${byte}/500`;
            if(byte > 500){
                document.getElementById('byte').innerText = '文字数オーバーです';
            }
        }
</script>
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
                $('#memoWrapper').prepend('<div class="memo"><p id="mainText"><span>'+val+'</span></p><p id="date">'+data.date+'</p><button type="submit" value="'+data.insert+'" name="del" id="delbtn">削除</button><img src="/img/load.gif" alt="" id="deload"><ul class="dragUl">ここにドロップ</ul></div>');
                $('#load').hide();
                $('#submit').show();
                $memoDel();
                bookSortable();
                memoSortable();
                $('#byte').text('0/500');
            }).fail(function(XMLHttpRequest,status,e){
                $('#memoWrapper').find('p').remove();
                
            });
        });

    });
    </script>




    