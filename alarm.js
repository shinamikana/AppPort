let alarmTime = document.getElementById('alarmTime');
let alarmDate = document.getElementById('alarmDate');
setInterval(function(){
let now = new Date();
let nowYear = now.getFullYear();
let nowMonth = now.getMonth() + 1;
if (nowMonth.toString().length == 1) {
    nowMonth = '0' + nowMonth;
}
let nowDate = now.getDate();
if (nowDate.toString().length == 1) {
    nowDate = '0' + nowDate;
}
let nowHour = now.getHours();
if (nowHour.toString().length == 1) {
    nowHour = '0' + nowHour;
}
let nowMinute = now.getMinutes();
if (nowMinute.toString().length == 1) {
    nowMinute = '0' + nowMinute;
}
alarmDate.value = nowYear + '-' + nowMonth + '-' + nowDate;
alarmTime.value = nowHour + ':' + nowMinute;
},1000);

$(() => {
    $('#alarmSubmit').click(function () {
        let alarmDate = $('#alarmDate').val();
        let alarmTime = $('#alarmTime').val() + ':00';
        let alarmVal = alarmDate + ' ' + alarmTime;
        console.log(alarmVal);
        if(checkDate(alarmDate,alarmTime)){
            $.ajax({
                type:'POST',
                url:'alarmData.php',
                data:{
                    'alarmVal':alarmVal,
                },
                dataType:'json',
            }).done(function(data){
                let splitDate = alarmVal.split(' ');
                let rawDate = splitDate[0];
                let rawTime = splitDate[1];

                let dateArray = rawDate.split('-');
                let now = new Date();
                //現在の西暦と同じであれば見かけ上は西暦を表示しない
                if(dateArray[0] == now.getFullYear()){
                    dateArray.shift();
                }
                date = dateArray.join('-');

                let timeArray = rawTime.split(':');
                //バックエンド側で処理する上では秒数も使うが表示の際に邪魔なので非表示に
                timeArray.pop();
                time = timeArray.join(':');

                let adjustDate = alarmVal.replace(' ','T');
                $('#alarmColumn').prepend('<div class="alarms"><span>'+date+'</span><span>'+time+'</span><input class="alarmsInput" value='+adjustDate+' type="hidden"><input type="hidden" value='+data.id+' class="alarmId"></input></div>');
            }).fail(function(XMLHttpRequest,status,e){
                console.log('fail');
            });
        }

    });

    ///日時のチェック処理
    function checkDate(alarmDate,alarmTime) {
        let splitDate = alarmDate.split('-');
        let y = splitDate[0];
        //java scriptでは月が0から始まるため、-1している
        let m = splitDate[1] - 1;
        let d = splitDate[2];
        let splitTime = alarmTime.split(':');
        let h = splitTime[0];
        let min = splitTime[1];
        let s = splitTime[2];
        let date = new Date(y, m, d,h,min,s);
        if (date.getFullYear() != y || date.getMonth() != m || date.getDate() != d || date.getHours() != h || date.getMinutes() != min|| date.getSeconds() != s) {
            return false;
        }
        return true;
    }

    window.alarmTime = [];
    let alarms = document.getElementsByClassName('alarmsInput');
    for(let i=0; i < alarms.length; i++){
        let alarmsS = alarms[i].value.replace(' ','T');
        //配列のalarmTimeにはDateオブジェクトが入っている
        window.alarmTime.push(new Date(alarmsS));
        console.log(window.alarmTime[i].getHours());
    }

    setInterval(function(){
        let alarms = document.getElementsByClassName('alarmsInput');
        for(let i=0; i < alarms.length; i++){
            let now = new Date();
            let alarmDate = new Date(window.alarmTime[i]);
            console.log(alarms.length);
            if(alarmDate.getFullYear() == now.getFullYear()){
                console.log('true');
            }
        }
    },1000);
});