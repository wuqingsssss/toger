<html lang="en">
<head>
<meta charset="UTF-8" />
<title>品牌接口测试</title>
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
		$('#productlistContainer').hide();
		
		$('#queryType').bind("change",function(){
			if($(this).val()=='detail'){
				$('#queryForm').attr('action',"<?php echo $detail;?>");
				$('#detailContainer').show();
				$('#listContainer').hide();
				$('#productlistContainer').hide();
			}else if($(this).val()=='list'){
				$('#queryForm').attr('action',"<?php echo $list;?>");
				$('#detailContainer').hide();
				$('#listContainer').show();
				$('#productlistContainer').hide();
			}else if($(this).val()=='productList'){
				$('#queryForm').attr('action',"<?php echo $productlist;?>");
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#productlistContainer').show();
			}
			else{
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#productlistContainer').hide();
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
				paramers = paramers+"pagenum="+pageNum+"&pagesize="+pageSize;
			}else if(queryType='productList')
			{
				var id  = $('#exampleInputId1').val();
				var pageNum = $("#exampleInputPageNum1").val();
				var pageSize = $("#exampleInputPageSize1").val();
				paramers = paramers+"id="+id;
				paramers = paramers+"&pagenum="+pageNum+"&pagesize="+pageSize;
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
				<h4>明细接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">接口：http://localhost/siteilexv3/api/index.php?route=catalog/manufacturer/detail&id=2</div>
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
								<td>id</td>
								<td>int</td>
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
					<div class="panel-body">返回接口实例JSON.{'errMsg':'','code':'0x0000','flag':'0','totalItems':'','result':{'category_id':'1','image':'','parent_id':'0','top':'0','column':'1','sort_order':'1','status':'1','date_added':'2013-12-12 16:09:09','date_modified':'2013-12-12 16:09:09','code':null,'language_id':'1','name':'通道门式放射性检测仪表','description':'<p> 通道门式放射性检测仪表</p> ','meta_description':'','meta_keyword':''}}</div>
				</div>
			</div>
		
				</div>
			</div>
			<br />
			
		</div>
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>列表接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
				<div class="panel-heading">接口：http://localhost/siteilexv3/api/index.php?route=catalog/manufacturer/list&pagenum=1&pagesize=1</div>
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
								<td>页数</td>
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
					<div class="panel-body">返回接口实例JSON.{'errMsg':'','code':'0x0000','flag':'0','totalItems':'6','result':{'0':{'id':'1','parent_id':'0','top':'0','column':'1','sort_order':'1','status':'1','code':null,'language_id':'1','name':'通道门式放射性检测仪表','description':'<p> 通道门式放射性检测仪表</p> ','meta_description':'','meta_keyword':'','images':''},'1':{'id':'2','parent_id':'0','top':'0','column':'1','sort_order':'2','status':'1','code':null,'language_id':'1','name':'环境及区域放射性监测仪表','description':'<p> 环境及区域放射性监测仪表</p> ','meta_description':'','meta_keyword':'','images':''}}}</div>
				</div>
			</div>
				</div>
			</div>
			<br />
			
		</div>
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>品牌下产品列表接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
				<div class="panel-heading">接口：http://localhost/siteilexv3/api/index.php?route=catalog/manufacturer/productlist&id=1&pagenum=1&pagesize=1</div>
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
								<td>id</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>产品分类ID</td>
							</tr>
							<tr>
								<td>pagenum</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页数</td>
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
					<div class="panel-body">返回接口实例JSON.{'errMsg':'','code':'0x0000','flag':'0','totalItems':'6','result':{'0':{'id':'1','parent_id':'0','top':'0','column':'1','sort_order':'1','status':'1','code':null,'language_id':'1','name':'通道门式放射性检测仪表','description':'<p> 通道门式放射性检测仪表</p> ','meta_description':'','meta_keyword':'','images':''},'1':{'id':'2','parent_id':'0','top':'0','column':'1','sort_order':'2','status':'1','code':null,'language_id':'1','name':'环境及区域放射性监测仪表','description':'<p> 环境及区域放射性监测仪表</p> ','meta_description':'','meta_keyword':'','images':''}}}</div>
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
							<option value="detail">品牌明细接口</option>
							<option value="list">品牌列表接口</option>
							<option value="productList">品牌产品接口</option>
						</select>
					</div>

					<div id="detailContainer" class="form-group">
						<label for="exampleInputEmail1">品牌ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputId" placeholder="品牌ID">
					</div>

					<div id="listContainer" class="form-group">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize" placeholder="页面容量">
					</div>
					
					<div id="productlistContainer" class="form-group">
						<label for="exampleInputEmail1">品牌ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputId1" placeholder="品牌ID">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum1" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize1" placeholder="页面容量">
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
