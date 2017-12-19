
  <div class="cbd-lists">
            	<?php foreach ($cbds as $result) { ?>
                <a href="#cbd-content-<?php echo $result['id']; ?>" class="cbd-item" data-id="<?php echo $result['id']; ?>" title=""><?php echo $result['name']; ?></a>
                <?php } ?>
            </div>
                      
           <?php foreach ($cbds as $result) { ?> 
            <div id="cbd-content-<?php echo $result['id']; ?>" class="tab-content">
            	<?php echo $this->getChild('point/home/point',$result['id']); ?>
            </div>
<?php } ?>