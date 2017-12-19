<?php if($categories&&count($categories)>0) { ?>
<div id="category" class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($categories as $category) { ?>
        <li>
          <?php if ($category['category_id'] == $category_id) { ?>
          <a href="<?php echo $category['href']; ?>" class="active" title="<?php echo $category['name']; ?>"> <?php echo $category['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $category['href']; ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
          <?php } ?>
          <?php if (FALSE && $category['children']) { ?>
          <ul>
            <?php foreach ($category['children'] as $child) { ?>
            <li>
              <?php if ($child['category_id'] == $child_id) { ?>
              <a href="<?php echo $child['href']; ?>" title="<?php echo $child['name']; ?>" class="active"> - <?php echo $child['name']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $child['href']; ?>" title="<?php echo $child['name']; ?>"> - <?php echo $child['name']; ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  
</div>

<?php }  ?>
<script type="text/javascript">
$(document).ready(function() {
   $(window).scroll(function() {
       var scrollVal = $(this).scrollTop();
       
        if ( scrollVal > 150 ) {
            $('#category').css({'position':'fixed','top' :'10px'});
        } else {
            $('#category').css({'position':'static','top':'auto'});
        }
    });
 });
</script>