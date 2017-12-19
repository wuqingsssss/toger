<html lang="en">
<head>
<meta charset="UTF-8" />
<title>案例接口测试</title>
<link rel="stylesheet" type="text/css"
	href="view/assets/css/bootstrap.min.css" />
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
		$('#sublistContainer').hide();
		$('#mutilSublistContainer').hide();
		
		$('#queryType').bind("change",function(){
			if($(this).val()=='detail'){
				$('#queryForm').attr('action',"<?php echo $detail;?>");
				$('#detailContainer').show();
				$('#listContainer').hide();
				$('#sublistContainer').hide();
				$('#mutilSublistContainer').hide();
			}else if($(this).val()=='list'){
				$('#queryForm').attr('action',"<?php echo $list;?>");
				$('#detailContainer').hide();
				$('#listContainer').show();
				$('#sublistContainer').hide();
				$('#mutilSublistContainer').hide();
			}else if($(this).val()=='subList'){
				$('#queryForm').attr('action',"<?php echo $sublist;?>");
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#sublistContainer').show();
				$('#mutilSublistContainer').hide();
			}else if($(this).val()=='muitlSubList'){
				$('#queryForm').attr('action',"<?php echo $muitlSublist;?>");
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#sublistContainer').hide();
				$('#mutilSublistContainer').show();
			}else{
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#sublistContainer').hide();
				$('#mutilSublistContainer').hide();
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
				paramers = "";
				var id  = $('#exampleInputId').val();
				paramers = paramers+"case_id="+id
			}else if(queryType == 'list')
			{
				paramers = "";
				var pageNum = $("#exampleInputPageNum").val();
				var pageSize = $("#exampleInputPageSize").val();
				paramers = paramers+"pagenum="+pageNum+"&pagesize="+pageSize;
			}else if(queryType == 'subList'){
				paramers = "";
				var pageNum = $("#exampleInputPageNum1").val();
				var pageSize = $("#exampleInputPageSize1").val();
				var exampleInputParentId = $('#exampleInputParentId').val();
				var exampleInputParentCode = $('#exampleInputParentCode').val();
				paramers = paramers+"pagenum="+pageNum+"&pagesize="+pageSize+"&case_category_id="+exampleInputParentId+"&code="+exampleInputParentCode;
			}else if(queryType == 'muitlSubList'){
				paramers = "";
				
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
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>获取案例分类下案例列表接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">接口示例： http://localhost/siteilexv3/api/index.php?route=catalog/case/list&case_category_id=4&pagenum=1&pagesize=1</div>
					<div class="panel-heading">请求参数说明</div>
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
								<td>case_category_id</td>
								<td>int</td>
								<td>GET</td>
								<td>case_category_id/code最少使用一个</td>
								<td>案例分类ID</td>
							</tr>
							<tr>
								<td>code</td>
								<td>String</td>
								<td>GET</td>
								<td>case_category_id/code最少使用一个</td>
								<td>案例分类Code</td>
							</tr>
							<tr>
								<td>pagenum</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页码</td>
							</tr>
							<tr>
								<td>pagesize</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页面容量</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{"errMsg":"","code":"0x0000","flag":"0","totalItems":"1","result":[{"case_id":"3","status":"1","sort_order":"0","date_added":"2014-03-26 00:00:00","date_modified":"2014-03-28 12:58:40","editor":"ilexnet","featured":"0","image":"","uri":"","language_id":"1","name":"\u6848\u4f8b\u4e00","meta_keyword":"","meta_description":"","description":"<p>213233<\/p>\r\n","summary":"1223333333"}]}</div>
				</div>
			</div>
		
				</div>
			</div>
			<br />
			
		</div>
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>案例明细信息接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
				<div class="panel-heading">接口： http://localhost/siteilexv3/api/index.php?route=catalog/case/detail&case_id=3</div>
					<div class="panel-heading">请求参数说明</div>
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
							<td>case_id</td>
							<td>int</td>
							<td>GET</td>
							<td>是</td>
							<td>案例ID</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{"errMsg":"","code":"0x0000","flag":"0","totalItems":1,"result":{"case_id":"3","status":"1","sort_order":"0","date_added":"2014-03-26 00:00:00","date_modified":"2014-03-28 12:58:40","editor":"ilexnet","featured":"0","image":"","uri":"","language_id":"1","name":"\u6848\u4f8b\u4e00","meta_keyword":"","meta_description":"","description":"<p>213233<\/p>\r\n","summary":"1223333333","keyword":"case-3"}}</div>
				</div>
			</div>
				</div>
			</div>
			<br />
			
		</div>
		
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>获取全部案例列表接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">接口示例： http://localhost/siteilexv3/api/index.php?route=catalog/case/list&pagesize=1&pagenum=1</div>
					<div class="panel-heading">请求参数说明</div>
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
								<td>pagenum</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页码</td>
							</tr>
							<tr>
								<td>pagesize</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页面容量</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{"errMsg":"","code":"0x0000","flag":"0","totalItems":"1","result":[{"case_id":"3","status":"1","sort_order":"0","date_added":"2014-03-26 00:00:00","date_modified":"2014-03-28 12:58:40","editor":"ilexnet","featured":"0","image":"","uri":"","language_id":"1","name":"\u6848\u4f8b\u4e00","meta_keyword":"","meta_description":"","description":"<p>213233<\/p>\r\n","summary":"1223333333"}]}</div>
				</div>
			</div>
		
				</div>
			</div>
			<br />
			
		</div>
		
		
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>获取案例分类下案例列表接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">接口示例：http://localhost/siteilexv3/api/index.php?route=catalog/case/list&case_category_id=4,5&code=5&pagesize=3&pagenum=1</div>
					<div class="panel-heading">请求参数说明</div>
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
								<td>case_category_id</td>
								<td>int</td>
								<td>GET</td>
								<td>case_category_id/code最少使用一个</td>
								<td>案例分类ID</td>
							</tr>
							<tr>
								<td>code</td>
								<td>String</td>
								<td>GET</td>
								<td>case_category_id/code最少使用一个</td>
								<td>案例分类Code</td>
							</tr>
							<tr>
								<td>pagenum</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页码</td>
							</tr>
							<tr>
								<td>pagesize</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页面容量</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{"errMsg":"","code":"0x0000","flag":"0","totalItems":"1","result":[{"case_id":"3","status":"1","sort_order":"0","date_added":"2014-03-26 00:00:00","date_modified":"2014-03-28 12:58:40","editor":"ilexnet","featured":"0","image":"","uri":"","language_id":"1","name":"\u6848\u4f8b\u4e00","meta_keyword":"","meta_description":"","description":"<p>213233<\/p>\r\n","summary":"1223333333"}]}</div>
				</div>
			</div>
		
				</div>
			</div>
			<br />
			
		</div>
		
		<h3>接口测试</h3>
		<div class="row">
			<div class="col-md-8">
				<form id="queryForm" role="form" method="post"
					enctype="multipart/form-data" action="<?php echo $list;?>">
					<div class="form-group">
						<label for="exampleInputEmail1">接口类型<font color="red">&nbsp;&nbsp;*</font></label> <select
							id="queryType" name="queryType" class="form-control">
							<option value="">请选择</option>
							<option value="subList">获取案例分类下案例列表接口</option>
							<option value="detail">案例明细信息接口</option>
							<option value="list">获取全部案例列表接口</option>
							<option value="muitlSubList">获取多案例下产品接口</option>
						</select>
					</div>

					<div id="detailContainer" class="form-group">
						<label for="exampleInputEmail1">案例ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputId" placeholder="案例分类ID">
					</div>

					<div id="listContainer" class="form-group">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize" placeholder="页面容量">
					</div>
					
					<div id="sublistContainer" class="form-group">
						<label for="exampleInputEmail1">案例分类ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentId" placeholder="父案例分类ID">
						<label for="exampleInputEmail1">案例分类CODE<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentCode" placeholder="父案例分类CODE">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum1" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize1" placeholder="页面容量">
					</div>
					
					
					<div id="mutilSublistContainer" class="form-group">
						<label for="exampleInputEmail1">父案例分类IDS<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentId1" placeholder="父案例分类IDS">
						<label for="exampleInputEmail1">父案例分类CODES<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentCode1" placeholder="父案例分类CODES">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum11" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize11" placeholder="页面容量">
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
