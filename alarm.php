<div id="alarmWrapper">
    <h1 id="alarmH1">アラーム</h1>
    <div id="alarmForm">
        <input type="date" id="alarmDate">
        <input type="time" id="alarmTime">
        <br>
        <input type="submit" value="設定する" id="alarmSubmit">
    </div>
    <div id="alarmColumn" class="sortUl">
        <?php if(isset($resultAlarm)): ?>
            <?php foreach($resultAlarm as $alarm) :?>
                <?php [$dates,$times] = explode(' ',$alarm['date']);
                $setTime = explode(':',$times);
                $dates = explode('-',$dates); 
                $now = date('Y');
                if($dates[0] == $now){
                    unset($dates[0]);
                    $dates = implode('-',$dates);
                }else{
                    $dates = implode('-',$dates);
                }
                ?>
                <?="<div class='alarms'>" ?>
                    <?= "<span>".$dates."</span>" ?>
                    <?= "<span>".$setTime[0].':'.$setTime[1]."</span>" ?>
                    <?= "<input class='alarmsInput' value='".$alarm['date']."' type='hidden'></input>" ?>
                    <?= "<input type='hidden' value='".$alarm['id']."' class='alarmId'></input>" ?>
                <?= "</div>" ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>