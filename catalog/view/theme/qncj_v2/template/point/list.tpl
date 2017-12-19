<div class="point-lists">
       <?php foreach ($points as $result) { ?>
                <a href="#point-content-<?php echo $result['point_id']; ?>" class="point-item" data-id="<?php echo $result['point_id']; ?>" title=""><?php echo $result['name']; ?></a>
     <?php } ?>
</div>