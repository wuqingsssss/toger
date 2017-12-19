<?php if(isset($allResults)&&!is_null($allResults)){?>

<table class="table">
<?php foreach ($allResults as $supply){?>
  <tr>
    <td><?php echo $supply['name']?></td>
  </tr>
<?php  }?>
</table>

<?php }else{?>
	没有记录
<?php }?>