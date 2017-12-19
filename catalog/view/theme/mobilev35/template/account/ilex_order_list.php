<div class="list">
<?php foreach ($orders as $order) { ?>
<a href="<?php echo $order['view']; ?>" class="item">
<table class="form">
	<tbody>
		<tr>
			<td><?php echo $column_order_id; ?></td>
			<td>#<?php echo $order['order_id']; ?>
			<?php if($order['p_order_id']!=''&&$order['count']==0){ ?>(子订单)<?php } ?>
			</td>
			<td><?php echo $column_status; ?></td>
			<td><?php echo $order['status']; ?></td>
		</tr>
		<tr>
			<td><?php if($order[shipping_point_id]) echo '取菜时间'; else echo '配送时间';?></td>
			<td><?php if($order[shipping_point_id]) echo $order['pdate']; else echo '约'.date('Y-m-d H:s',strtotime($order['shipping_time'])).'左右';?></td>
			<td><?php echo $column_total; ?></td>
			<td><?php echo $order['total']; ?></td>
		</tr>
	</tbody>
</table>
</a>
<?php } ?>
</div>