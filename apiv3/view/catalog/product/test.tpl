<html lang="en">
<head>
<meta charset="UTF-8" />
<title>产品接口测试</title>
<link rel="stylesheet" type="text/css" href="view/assets/css/bootstrap.min.css" />
<script src="view/assets/js/jquery-2.0.3.min.js"></script>
<style type="text/css">
/* 收缩展开效果 */
.text {
	line-height: 22px;
	padding: 0 6px;
	color: #666;
}

.box h4 {
	padding-left: 10px;
	height: 22px;
	line-height: 22px;
	background: #f1f1f1;
	font-weight: bold;
}

.box {
	position: relative;
	border: 1px solid #e7e7e7;
}
</style>
<script type="text/javascript">
		function json2str(o) {
             var arr = [];
             var fmt = function(s) {
	                if (typeof s == 'object' && s != null) return json2str(s);
	                return /^(string|number)$/.test(typeof s) ? "'" + s + "'" : s;
	             }
	             for (var i in o) arr.push("'" + i + "':" + fmt(o[i]));
	             return '{' + arr.join(',') + '}';
	     }

	$(document).ready(function(){
			$("div.text").hide();//默认隐藏div，或者在样式表中添加.text{display:none}，推荐使用后者
			$(".box h4").click(function(){
				$(this).next(".text").slideToggle("slow");
			})
		
		$('#detailContainer').hide();
		$('#listContainer').hide();
		$('#resultContainer').hide();
		$('#queryType').bind("change",function(){
			if($(this).val()=='detail'){
				$('#queryForm').attr('action',"<?php echo $detail;?>");
				$('#detailContainer').show();
				$('#listContainer').hide();
			}else if($(this).val()=='list'){
				$('#queryForm').attr('action',"<?php echo $list;?>");
				$('#detailContainer').hide();
				$('#listContainer').show();
			}else{
				$('#detailContainer').hide();
				$('#listContainer').hide();
			}
			$('#resultContainer').hide();
		});

		$('#submitButton').bind('click',function(){
			var queryType = $('#queryType').val();
			if(queryType==''){
				alert("请选择查询类型");
				return ;
			}
			var paramers = "";
			if(queryType == 'detail')
			{
				var id  = $('#exampleInputId').val();
				paramers = paramers+"id="+id;
			}else if(queryType == 'list')
			{
				var pageNum = $("#exampleInputPageNum").val();
				var pageSize = $("#exampleInputPageSize").val();
				var exampleInputParentId = $('#exampleInputParentId').val();
				var exampleInputParentCode = $('#exampleInputParentCode').val();
				paramers = paramers+"pagenum="+pageNum+"&pagesize="+pageSize+"&category_id="+exampleInputParentId+"&code="+exampleInputParentCode;
			}
			$.ajax({
				url: $('#queryForm').attr("action"),
				type: 'GET',
				dataType: 'json',
				data: paramers,
				success: function(data) {
					$('#resultContainer').show();
					$('#result').html(json2str(data));	
					$('#result').slideToggle("slow");
				}
			});
		});
		
	});
</script>
<body>
	<div class="container" style="padding: 20px;">
		
		<div class="row">
			<div class="col-md-6">
			  <div class="panel panel-default">
			  <div class="panel-heading">返回结果说明</div>
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
		<h3>接口测试</h3>
		<div class="row">
			<div class="col-md-8">
				<form id="queryForm" role="form" method="post" enctype="multipart/form-data"  action="<?php echo $list;?>">
					<div class="form-group">
						<label for="exampleInputEmail1">接口类型<font color="red">&nbsp;&nbsp;*</font></label> <select
							id="queryType" name="queryType" class="form-control">
							<option value="">请选择</option>
							<option value="detail">产品明细接口</option>
							<option value="list">产品列表接口</option>
							<option value="mutilProductList">多分类下产品列表接口</option>
							<option value="brandProductList">品牌下产品列表接口</option>
						</select>
					</div>
					
					<div id="detailContainer" class="form-group">
						<label for="exampleInputEmail1">产品ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputId" placeholder="产品ID">
					</div>
					
					<div id="listContainer" class="form-group">
						<label for="exampleInputEmail1">分类ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentId" placeholder="父分类ID">
						<label for="exampleInputEmail1">分类CODE<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentCode" placeholder="父分类CODE">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize" placeholder="页面容量">
					</div>
					
					
					<button type="button" id="submitButton" class="btn btn-default">查询</button>
				</form>
			</div>
		</div>
		
		<div class="row" id="resultContainer">
			<!-- 收缩展开效果 -->
			<div class="box">
				<h4>点击查看</h4>
				<div id="result" class="text">
				
				</div>
			</div>
			<br />
		</div>
	</div>
</body>
</html>