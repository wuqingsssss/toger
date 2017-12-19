<html lang="en">
<head>
<meta charset="UTF-8" />
<title>产品接口测试</title>
<?php include 'view/common.tpl';?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#detailFormButton').bind('click',function(){
			$('#detailForm').attr('action',$('#detailForm').attr('action')+"&id="+$('#id').val());
			$('#detailForm').submit();
		});
	});
</script>

</head>
<body>
	<div class="container" style="padding: 20px;">
		
		<div class="row">
			<div class="col-md-6">
			  <div class="panel panel-default">
			  <div class="panel-heading">参数说明</div>
			  <table class="table">
		        <thead>
		          <tr>
		            <th>参数</th>
		            <th>类型</th>
		            <th>METHOD</th>
		            <th>是否必须</th>
		            <th>备注</th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr>
		            <td>ID</td>
		            <td>整数</td>
		            <td>GET</td>
		            <td>是</td>
		            <td>产品分类ID</td>
		          </tr>
		         </tbody>
		      </table>
			</div>
			</div>
			<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">返回结果说明</div>
			  <div class="panel-body">
			   返回接口实例JSON.
			  </div>
			</div>
			</div>
		</div>
		<h3>接口测试实例-1</h3>
		<div class="row">
			<div class="col-md-8">
				<form id="detailForm" role="form" method="post" enctype="multipart/form-data"  action="<?php echo $detail;?>">
					<div class="form-group">
						<label for="id">产品ID</label> <input
							type="input" class="form-control" id="id" name="id"
							placeholder="产品ID">
					</div>
					<button type="button" id="detailFormButton" class="btn btn-default">查询产品明细</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>