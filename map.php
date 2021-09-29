<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>地図</title>
    <link rel="stylesheet" href="/css/map.css">
</head>
<body>
    <?php include('miniLogo.php'); ?>
    <main>
    <?php include('mapIndex.php'); ?>
    </main>

    <script
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo getenv('APY_KEY_MAP')?>&callback=initMap&v=weekly"
      async
    ></script>
    <script>
    let map;
    function initMap(){
        map = new google.map.Map(document.getElementById('map'),{
            center:{lat:-34.397,lang:150.644},
            zoom:8,
        });
    }
</script>
</body>
</html>