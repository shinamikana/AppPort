<div class="mapWrapper">
    <h1>地図</h1>
    <div id="map"></div>
    <?php foreach($resultMark as $mark): ?>
    <div class="showMark">
        <p class="columnMark"><?php echo $mark['field_name'] ?> </p><input type="text" class="markInput" value="<?php echo $mark['field_name'] ?>"><input type="hidden" value="<?php echo $mark['id']?>">
    </div>
    <?php endforeach ?>
</div>


