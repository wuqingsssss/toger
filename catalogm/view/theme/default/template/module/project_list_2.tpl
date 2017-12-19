<?php if(isset($allResults)&&!is_null($allResults)){?>
<table class="table">
	<thead>
	<tr>
		<th>产品名称</th>
		<th>价格</th>
		<th>地区</th>
		<th>日期</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($allResults as $supply){?>
	  <tr>
	    <td><?php echo $supply['name'];?></td>
	    <td><?php echo $supply['price'];?></td>
	    <td><?php echo $supply['local_addr_zone']." ".$supply['local_addr_city'];?></td>
	    <td><?php echo $supply['date_added'];?></td>
	  </tr>
	<?php  }?>
	</tbody>
</table>
<?php }else{?>
	没有记录
<?php }?>