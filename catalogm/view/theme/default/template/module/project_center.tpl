<?php if(isset($allResults)&&!is_null($allResults)){?>
<table class="table">
	<thead>
	<tr>
		<th>项目标示</th>
		<th>项目编号</th>
		<th>项目名称</th>
		<th>项目标的</th>
		<th>项目发布日</th>
		<th>项目截止日</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($allResults as $supply){?>
	  <tr>
	  	<td><?php echo $supply['product_id']?></td>
	    <td><?php echo $supply['number'];?></td>
	    <td><?php echo $supply['name'];?></td>
	    <td><?php echo $supply['price'];?></td>
	    <td><?php echo $supply['date_added'];?></td>
	    <td><?php echo $supply['period'];?></td>
	  </tr>
	<?php  }?>
	</tbody>
</table>
<?php }else{?>
	没有记录
<?php }?>