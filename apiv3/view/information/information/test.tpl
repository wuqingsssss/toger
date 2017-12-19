<html lang="en">
<head>
<meta charset="UTF-8" />
<title>单页信息接口测试</title>
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
				var code = $('#exampleInputCode').val();
				paramers = paramers+"id="+id+"&code="+code;
			}else if(queryType == 'list')
			{
				var pageNum = $("#exampleInputPageNum").val();
				var pageSize = $("#exampleInputPageSize").val();
				paramers = paramers+"pagenum="+pageNum+"&pagesize="+pageSize;
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
					<div class="panel-heading">接口： http://localhost/siteilexv3/api/index.php?route=information/information/detail&id=3</div>
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
								<td>id/code最少使用一个</td>
								<td>单页ID</td>
							</tr>
							<tr>
								<td>code</td>
								<td>String</td>
								<td>GET</td>
								<td>id/code最少使用一个</td>
								<td>单页Code</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{"errMsg":"","code":"0x0000","flag":"0","totalItems":"","result":{"information_id":"3","code":null,"sort_order":"12","status":"1","section":"0","image":null,"language_id":"1","title":"\u4f01\u4e1a\u6587\u5316","description":"<p>\r\n\t\u4f01\u4e1a\u6587\u5316<\/p>\r\n","meta_keyword":"","meta_description":"","keyword":null}}</div>
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
				<div class="panel-heading">接口： http://localhost/siteilexv3/api/index.php?route=information/information/list&pagesize=1&pagenum=1</div>
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
					<div class="panel-body">返回接口实例JSON.{"errMsg":"","code":"0x0000","flag":"0","totalItems":"4","result":[{"information_id":"1","code":null,"sort_order":"11","status":"1","section":"0","image":null,"language_id":"1","title":"\u516c\u53f8\u7b80\u4ecb","description":"<p>\r\n\t<strong>\u4e0a\u6d77\u4ec1\u673a\u4eea\u5668\u4eea\u8868\u6709\u9650\u516c\u53f8<\/strong>\u662f\u4e00\u5bb6\u4e13\u4e1a\u4ece\u4e8b\u6838\u5de5\u4e1a\u667a\u80fd\u4eea\u5668\u4eea\u8868\u7684\u7814\u53d1\u3001\u751f\u4ea7\u4e0e\u9500\u552e\u7684\u9ad8\u65b0\u6280\u672f\u4f01\u4e1a\uff0c\u4ea7\u54c1\u6db5\u76d6\u8f90\u5c04\u9632\u62a4\u4eea\u5668\u3001\u8f90\u5c04\u73af\u5883\u68c0\u6d4b\u4eea\u5668\u3001\u653e\u5c04\u6027\u76d1\u6d4b\u4eea\u5668\u3001\u540c\u4f4d\u7d20\u5e94\u7528\u4eea\u5668\u3001\u6838\u52d8\u6d4b\u4eea\u5668\u4ee5\u53ca\u653e\u5c04\u6e90\u76d1\u7ba1\u7cfb\u7edf\uff0c\u5e76\u5e7f\u6cdb\u5e94\u7528\u4e8e\u73af\u4fdd\u3001\u75be\u63a7\u3001\u6838\u7535\u3001\u653e\u5c04\u533b\u7597\u3001\u80fd\u6e90\u3001\u77f3\u6cb9\u3001\u7164\u7530\u3001\u5efa\u6750\u3001\u51b6\u91d1\u3001\u98df\u54c1\u3001\u5546\u68c0\u3001\u5b89\u9632\u3001\u518d\u751f\u8d44\u6e90\u7b49\u591a\u4e2a\u9886\u57df\u3002<\/p>\r\n<div>\r\n\t<img alt="" src="\/upgrade\/ergodi\/image\/data\/content\/about.jpg" \/><\/div>\r\n<p>\r\n\t&nbsp;<\/p>\r\n<p>\r\n\t<strong>\u4ec1\u673a\u516c\u53f8<\/strong>\u62e5\u6709\u4e00\u652f\u9ad8\u7d20\u8d28\u3001\u7ecf\u9a8c\u4e30\u5bcc\u7684\u6838\u4eea\u5668\u79d1\u7814\u56e2\u961f\uff0c\u4ee5\u4eba\u673a\u5de5\u7a0b\u5b66\u7684\u6807\u51c6\u6765\u8bbe\u8ba1\u4ea7\u54c1\uff0c\u6bcf\u4e00\u6b3e\u4ea7\u54c1\u90fd\u5177\u6709\u4eba\u6027\u5316\u8bbe\u8ba1\uff0c\u4ec1\u673a\u516c\u53f8\u5c06\u7ed3\u5408\u5b8c\u5584\u7684\u552e\u540e\u670d\u52a1\u4f53\u7cfb\uff0c\u52aa\u529b\u6253\u9020\u56fd\u4ea7\u6838\u4eea\u5668\u7b2c\u4e00\u54c1\u724c\u3002<\/p>\r\n<p>\r\n\t\u6211\u516c\u53f8\u79c9\u627f\u201c\u8d28\u91cf\u7b2c\u4e00\u3001\u8bda\u4fe1\u4e3a\u672c\u3001\u5ba2\u6237\u81f3\u4e0a\u201d\u7684\u4f01\u4e1a\u5b97\u65e8\uff0c\u4e3a\u4f60\u63d0\u4f9b\u6700\u4f18\u8d28\u7684\u6838\u4eea\u5668\u3001\u8f90\u5c04\u9632\u62a4\u4e0e\u653e\u5c04\u6027\u76d1\u6d4b\u7684\u89e3\u51b3\u65b9\u6848\u4ee5\u53ca\u6ee1\u610f\u7684\u552e\u540e\u670d\u52a1\u3002 \u4ec1\u673a\u516c\u53f8\u4e3a\u7528\u6237\u63d0\u4f9b\u81ea\u4e3b\u7814\u53d1\u7684\u201cergodi\u201d\u54c1\u724c\u4ea7\u54c1\u6709\uff1a\u7cfb\u5217\u5242\u91cf\u4eea\u3001\u7cfb\u5217\u4fbf\u643a\u5f0f\u5c04\u7ebf\u68c0\u6d4b\u4eea\u3001\u7cfb\u5217\u573a\u6240\u76d1\u63a7\u4eea\u5668\u53ca\u653e\u5c04\u6e90\u76d1\u63a7\u7ba1\u7406\u7cfb\u7edf\uff0c\u4e3a\u6ee1\u8db3\u7528\u6237\u7684\u9700\u6c42\uff0c\u6211\u4eec\u4f1a\u4e0d\u65ad\u63d0\u5347\u4ea7\u54c1\u6027\u80fd\u3001\u63d0\u9ad8\u4ea7\u54c1\u8d28\u91cf\uff0c\u4ee5\u6700\u5177\u6709\u79d1\u6280\u542b\u91cf\u7684\u4ea7\u54c1\u56de\u9988\u7ed9\u5e7f\u5927\u7528\u6237\u3002<\/p>\r\n<p>\r\n\t&nbsp;<\/p>\r\n","meta_keyword":"","meta_description":""}]}</div>
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
							<option value="detail">明细接口</option>
							<option value="list">列表接口</option>
						</select>
					</div>

					<div id="detailContainer" class="form-group">
						<label for="exampleInputEmail1">单页ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputId" placeholder="单页ID">
						<label for="exampleInputEmail1">单页Code<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
						class="form-control" id="exampleInputCode" placeholder="单页CODE">
					</div>

					<div id="listContainer" class="form-group">
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
