<form method="post" id="location-form">
    <div class="list row">
        <div class="col">
            所在区域<br/>
            <select name="city_id" style="width:100%;"
                    onchange="$('select[name=\'cbd_id\']').load('index.php?route=common/localisation/cbd&city_id=' + this.value);">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($cities as $city) { ?>
                    <?php if ($city['city_id'] == $city_id) { ?>
                        <option value="<?php echo $city['city_id']; ?>"
                                selected="selected"><?php echo $city['name']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col">

            所在商圈<br/>
            <select name="cbd_id" style="width:100%;" onchange="set_default_location()">
                <option value=""><?php echo $text_select; ?></option>
            </select>

        </div>
    </div>
</form>
<script type="text/javascript">
    $('select[name=\'cbd_id\']').load('index.php?route=common/localisation/cbd&city_id=<?php echo $city_id; ?>&cbd_id=<?php echo $cbd_id; ?>');
</script>

