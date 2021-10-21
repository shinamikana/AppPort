
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bookMarkIndex</title>
    <link rel="stylesheet" href="/css/bookmarkIndex.css">
</head>
<body>
    <div class="bookWrapper">
        <h1 id="bookmarkTitle">ブックマーク</h1>
        <div class="bookmark">
            <!-- ブックマークフォーム　mark=URL linkName=リンク名 -->
                <input name="url" id="url" placeholder="URL">
                <input type="text" id="linkName" name="linkName" placeholder="リンク名">
                <input type="submit" id="submit1"><img src="/img/load.gif" alt="" id="load1">
                <div class="bookmarkColumn"></div>
                <ul class="bookUl">
                    <li class="bookLi"></li>
                    <?php if(isset($showResult)): ?>
                        <?php foreach($showResult as $show): ?>
                            <li class="bookLi" id="<?php echo $show['id'] ?>">
                            <div class="bookmarking">
                            <i class="fas fa-check"></i><i class="far fa-edit"></i><a href="<?php echo h($show['link']) ?>" target="_blank" rel="noopener noreferrer" class="bookA"><?php echo h($show['link_name']) ?></a><i class="fas fa-times"></i><input type="text" value="<?php echo h($show['link_name']) ?>" class="bookNameInput"><input type="text" value="<?php echo h($show['link']) ?>" class="bookLinkInput"><button id="deltn1" value="<?php echo h($show['id']) ?>">削除</button><img src="/img/load.gif" alt="" class="deload1"><input type="hidden" class="bookId" value="<?php echo h($show['id']) ?>">
                            </div>
                        </li>
                        <?php endforeach ?>
                    <?php endif ?>
                    </ul>
                </div>
        </div>
    </div>

    <div class="bookConfirm">
        <p>URLが変更されています。</p>
        <p>このまま送信しますか？</p>
        <button id="bookConfirmNo">いいえ</button><button id="bookConfirmNo">はい</button>
    </div>

    <script>$(function(){
        $('#submit1').on('click',function(event){
            let val1 = $('#url').val();
            let val2 = $('#linkName').val();
            $('#submit1').hide();
            $('#load1').show();
            $.ajax({
                type:'POST',
                url:'bookmark.php',
                data:{'url':val1,'linkName':val2},
                dataType:'json',
            }).done(function(data){
                $('.bookmarkColumn').prepend('<div class="bookmarking"><a href="'+data.url+'" target="_blank" rel="noopener noreferrer">　'+data.linkName+'　　　　　<span class="sliceUrl">'+data.url+'</span></a><button id="deltn1" value="'+data.id+'">削除</button><img src="/img/load.gif" alt="" class="deload1"></div>');
                $('#submit1').show();
                $('#load1').hide();
                $('#url').val('');
                $('#linkName').val('');
                $delete();
            }).fail(function(XMLHttpRequest,status,e){
                console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                alert('fail');
                $('#load1').hide()
            });
        });

        let $delete = function(){
            $('.bookmarking').find('#deltn1').on('click',function(event){
            let $this = $(this).parent();
            $this.css({opacity:'0.5'});
            $('.bookmarking').find('#deltn1').hide();
            $('.deload1').show();
            let delId = $(this).val();
            $.ajax({
                type:'POST',
                url:'bookmark.php',
                data:{'delId':delId},
                dataType:'json',
            }).done(function(data){
                $this.hide();
                $('.deload1').hide();
                $('.bookmarking').find('#deltn1').show();
            }).fail(function(XMLHttpRequest,status,e){
                console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                $this.css({opacity :'1'});
                $('.deload1').hide();
            });
        });
        }

        $delete();

        $('.fa-edit').click(function(){
            $(this).hide();
            $(this).parent().find('.bookA').hide();
            $(this).parent().find('.bookNameInput').show();
            $(this).parent().find('.bookLinkInput').show();
            $(this).parent().find('.fa-check').show();
            $(this).parent().find('#deltn1').hide();
            $(this).parent().find('.fa-times').show();
        });

        const bookEditShow = function($this){
            $this.hide();
            $this.parent().find('.fa-edit').show();
            $this.parent().find('.bookNameInput').hide();
            $this.parent().find('.bookA').show();
            $this.parent().find('.bookLinkInput').hide();
            $this.parent().css('opacity','1');
            $this.parent().find('.deltn1').show();
            $this.parent().find('.fa-times').hide();
            $this.parent().find('#deltn1').show();
            $this.parent().find('.fa-check').hide();
        }

        $('.fa-times').click(function(){
            let $this = $(this);
            let bookName = $this.parent().find('.bookA').text();
            let bookLink = $this.parent().find('.bookA').attr('href');
            $this.parent().find('.bookNameInput').val(bookName);
            $this.parent().find('.bookLinkInput').val(bookLink);
            bookEditShow($this);
        });

        $('.fa-check').click(function(){
            let $this = $(this);
            let bookNameVal = $this.parent().find('.bookNameInput').val();
            let bookLinkVal = $this.parent().find('.bookLinkInput').val();
            let bookName = $this.parent().find('.bookA').text();
            let bookLink = $this.parent().find('.bookA').attr('href');
            let bookId = $this.parent().find('.bookId').val();
            $this.parent().css('opacity','0.5');
            if(bookNameVal != bookName && bookLinkVal != bookLink){
                $.ajax({
                    type:'POST',
                    url:'bookmark.php',
                    data:{'bookName':bookNameVal,'bookLink':bookLinkVal,'bookId':bookId},
                    dataType:'json',
                }).done(function(data){
                    $this.parent().find('.bookA').text(bookNameVal);
                    $this.parent().find('.bookA').attr('href',bookLinkVal);
                    bookEditShow($this);
                }).fail(function(XMLHttpRequest,status,e){
                    console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                });

            }else if(bookNameVal != bookName){
                $.ajax({
                    type:'POST',
                    url:'bookmark.php',
                    data:{'bookName':bookNameVal,'bookId':bookId},
                    dataType:'json',
                }).done(function(data){
                    $this.parent().find('.bookA').text(bookNameVal);
                    bookEditShow($this);
                }).fail(function(XMLHttpRequest,status,e){
                    console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                });

            }else if(bookLinkVal != bookLink){
                $.ajax({
                    type:'POST',
                    url:'bookmark.php',
                    data:{'bookLink':bookLinkVal,'bookId':bookId},
                    dataType:'json',
                }).done(function(data){
                    $this.parent().find('.bookA').attr('href',bookLinkVal);
                    bookEditShow($this);
                }).fail(function(XMLHttpRequest,status,e){
                    console.log('error number:'+ XMLHttpRequest +',status:'+ status +',thrown:'+ e);
                });
            }else{
                bookEditShow($this);
            }
        });

    });
    </script>
</body>
