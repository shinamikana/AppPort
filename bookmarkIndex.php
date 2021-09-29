
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
                            <li class="bookLi">
                            <div class="bookmarking">
                                <a href="<?php echo h($show['link']) ?>" target="_blank" rel="noopener noreferrer">　<?php echo h($show['link_name']) ?>　　　　　<span class="sliceUrl"><?php echo mb_strimwidth(h($show['link']),0,25,'...') ?></span></a><button id="deltn1" value="<?php echo h($show['id']) ?>">削除</button><img src="/img/load.gif" alt="" class="deload1">
                            </div>
                        </li>
                        <?php endforeach ?>
                    <?php endif ?>
                    </ul>
                </div>
        </div>
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

    });
    </script>
</body>
