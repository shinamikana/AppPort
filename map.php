<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>地図</title>
    <link rel="stylesheet" href="/css/map.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <main>
    <?php include('mapIndex.php'); ?>
    </main>

    <script
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo getenv('API_KEY_MAP')?>&callback=initMap&v=weekly"
      async
    ></script>
    <script>

    let map;

//地図の読み込み関数
function initMap() {
  let lat = 34.73373029238828;
  let lng =  135.50025469752734;
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: lat, lng: lng },
    zoom: 8,
  });

  google.maps.event.addListener(map,'click',event => clickListener(event,map));
  
    
}

//マーカーをクリックで削除処理
function markerDelete(event,marker){
      marker.setMap(null);
} 

//地図をクリックでマーカー追加
function clickListener(event,map){
  const lat = event.latLng.lat();
  const lng = event.latLng.lng();
  const marker = new google.maps.Marker({
    position:{lat,lng},
    map
  });
  insert(lat,lng);
  google.maps.event.addListener(marker,'click',event => markerDelete(event,marker));
  
}


$(function(){

  window.insert = function(lat,lng){
    $.ajax({
      type:'POST',
      url:'map.php',
      data:{'lat':lat,'lng':lng},
      dataType:'json'
    }).done(function(data){
      alert('done');
    }).fail(function(HMLHttpRequest,status,e){
      alert('fail');
    });
  }

  console.log((new Blob(['aiueo'])).size);

});

</script>
</body>
</html>