<html lang="en">
<head>
<meta charset="UTF-8" />
<title>新闻文章接口测试</title>
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
		$('#alllistContainer').hide();
		$('#queryType').bind("change",function(){
			if($(this).val()=='detail'){
				$('#queryForm').attr('action',"<?php echo $detail;?>");
				$('#detailContainer').show();
				$('#listContainer').hide();
				$('#alllistContainer').hide();
			}else if($(this).val()=='list'||$(this).val()=='muitllist'){
				$('#queryForm').attr('action',"<?php echo $list;?>");
				$('#detailContainer').hide();
				$('#listContainer').show();
				$('#alllistContainer').hide();
			}else if($(this).val()=='alllist')
			{
				$('#queryForm').attr('action',"<?php echo $list;?>");
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#alllistContainer').show();
			}
			else{
				$('#detailContainer').hide();
				$('#listContainer').hide();
				$('#alllistContainer').hide();
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
				var code = $('#exampleInputCode').val();
				paramers = paramers+"article_id="+id+"&code="+code;
			}else if(queryType == 'list'||queryType == 'muitllist')
			{
				paramers = "";
				var pageNum = $("#exampleInputPageNum").val();
				var pageSize = $("#exampleInputPageSize").val();
				var exampleInputParentId = $('#exampleInputParentId').val();
				var exampleInputParentCode = $('#exampleInputParentCode').val();
				paramers = paramers+"pagenum="+pageNum+"&pagesize="+pageSize+"&article_category_id="+exampleInputParentId+"&code="+exampleInputParentCode;
			}
			else if(queryType == 'alllist')
			{
				paramers = "";
				var pageNum = $("#exampleInputPageNumall").val();
				var pageSize = $("#exampleInputPageSizeall").val();
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
				<h4>(根据code或者id)获取某个分类下文章列表接口</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">接口：http://localhost/siteilexv3/api/index.php?route=catalog/article/list&article_category_id=3&pagenum=1&pagesize=2</div>
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
								<td>article_category_id</td>
								<td>int</td>
								<td>GET</td>
								<td>article_category_id/code最少使用一个</td>
								<td>产品分类ID</td>
							</tr>
							<tr>
								<td>code</td>
								<td>String</td>
								<td>GET</td>
								<td>article_category_id/code最少使用一个</td>
								<td>产品分类Code</td>
							</tr>
							<tr>
								<td>pagesize</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页面大小</td>
							</tr>
							<tr>
								<td>pagenum</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页码</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{'errMsg':'','code':'0x0000','flag':'0','totalItems':'3','result':{'0':{'article_id':'14','status':'1','download_only':'0','sort_order':'0','date_added':'2014-01-12 00:00:00','date_modified':'2014-01-13 13:42:53','editor':'admin','featured':'0','image':'','feature_image':'0','language_id':'1','name':'招聘软件开发工程师（2-3人）','meta_keyword':'','meta_description':'','description':'<div style="line-height: 25px"> <span style="font-size: 14px">岗位职责：<br /> 1.负责产品上位机软件设计、开发、调试与测试，并完成相关技术文档；<br /> 2.独立完成项目中与软件开发相关的具体任务；<br /> 3.定期向项目负责人进行工作汇报，与项目其它相关技术人员进行工作交流与协调。</span></div> <div style="line-height: 25px"> &nbsp;</div> <div style="line-height: 25px"> <span style="font-size: 14px">任职资格：<br /> 1.3年及以上软件设计相关工作经验；<br /> 2.精通常用软件开发工具（VC、VB、LabVIEW等）；<br /> 3.熟悉各类通讯接口软件开发（串口、CAN总线、TCP/IP网络通讯等）；<br /> 4.具有单片机/嵌入式等下位机软件开发工作经验优先；<br /> 5.具有核仪器仪表研发工作经历或核电工作经历者优先。</span></div> <p> &nbsp;</p> ','summary':''}}}</div>
				</div>
			</div>
		
				</div>
			</div>
			<br />
			
		</div>
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>文章明细接口说明</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
				<div class="panel-heading">接口： http://localhost/siteilexv3/api/index.php?route=catalog/article/detail&article_id=3</div>
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
								<td>article_id</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>文章ID</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{'errMsg':'','code':'0x0000','flag':'0','totalItems':'1','result':{'article_id':'3','status':'1','download_only':'0','sort_order':'1','date_added':'2013-12-11 00:00:00','date_modified':'2013-12-26 22:09:08','editor':'ilexnet','featured':'0','image':'data/trade/feigangtie.jpg','feature_image':'0','language_id':'1','name':'废钢铁及再生资源','meta_keyword':'','meta_description':'','description':'<p> &nbsp; &nbsp; 随着铁矿石价格的增长, 大大提高了钢铁制造的成本。直接降低成本的方法就是用废旧金属替代铁矿石。但很遗憾的是废旧金属中有时会含有放射性元素。放射性元素在医疗、工业、农业中使用非常广泛。这些放射源通常放在特殊的密封容器里。当辐射量很低时，不会对人类造成伤害。但一旦辐射量超标或直接暴露在自然环境中，将对人体产生不可挽回的巨大影响。不幸的是它们常被人们遗弃或放入炼钢炉里。如果，人们把此类物质放在炼钢炉里进行冶炼，这些被融化了的放射性元素将释放出辐射，不仅产品将受到污染，炼钢炉和周围的环境也都将受到污染。如果它们被制成建筑材料，整个建筑也将污染人类的居住环境。</p> <p> &nbsp; &nbsp; 就在去年，中国某不锈钢公司出在160吨出口到意大利的不锈钢中就被查出30吨的产品钴60超标结果造成了很大的经济损失。如果该公司装备了辐射探测系统就会很容易避免此类事故的发生。所以为了员工的安全、客户的安全请使用辐射探测系统，以确保您可以制造、销售安全无污染的产品！</p> <p> &nbsp; &nbsp; 当放射源在废钢中间时，废钢会成为射线行进的阻碍，即使有一部分射线穿过障碍，辐射水平也变得很低，这时检测就变得有难度了。没有任何人可以提出一种解决方案可以100%发现这些隐匿的放射源。在现实情况下如何去发现这些隐匿的威胁是必须被充分重视的。通道式车辆放射性检测系统是可以比较好的对废钢中的放射性进行检测，但是这和企业的经济承受能力相关，因此各个行业应该根据自己的生产能力和经营的情况来决定这方面的支出。</p> <p> &nbsp; &nbsp; 目前上海仁机的通道式车辆放射性检测系统在中国各地区、日本各地以及缅甸都有安装案例可供参考。如有需要请来电索取解决方案，我们将就近为您安排参观实际案例。上海仁机将为您提供最符合您需求的最经济、最实惠的解决方案。</p> ','summary':'随着铁矿石价格的增长, 大大提高了钢铁制造的成本。直接降低成本的方法就是用废旧金属替代铁矿石。但很遗憾的是废旧金属中有时会含有放射性元素。放射性元素在医疗、工业、农业中使用非常广泛。这些放射源通常放在特殊的密封容器里。当辐射量很低时，不会对人类造成伤害。但一旦辐射量超标或直接暴露在自然环境中，将对人体产生不可挽回的巨大影响……','keyword':'article-3'}}</div>
				</div>
			</div>
				</div>
			</div>
			<br />
		</div>
		
		
		<div class="row">
		<!-- 收缩展开效果 -->
			<div class="box">
				<h4>获取全部文章列表接口</h4>
				<div class="text clearfix">
				<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">接口：http://localhost/siteilexv3/api/index.php?route=catalog/article/list&pagesize=1&pagenum=1</div>
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
								<td>pagesize</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页面大小</td>
							</tr>
							<tr>
								<td>pagenum</td>
								<td>int</td>
								<td>GET</td>
								<td>是</td>
								<td>页码</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">返回结果说明</div>
					<div class="panel-body">返回接口实例JSON.{'errMsg':'','code':'0x0000','flag':'0','totalItems':'13','result':{'0':{'article_id':'9','status':'1','download_only':'0','sort_order':'7','date_added':'2013-12-11 00:00:00','date_modified':'2013-12-26 23:01:03','editor':'ilexnet','featured':'0','image':'data/trade/hejishu.jpg','feature_image':'0','language_id':'1','name':'核技术应用','meta_keyword':'','meta_description':'','description':'<p> 核技术是建立在核科学基础之上的一门现代技术，也称为核科学技术，是现代化科学技术的组成部分。核技术应用主要包括核能的利用及同位素和辐照技术的利用。核能是一种安全、经济、清洁的能源，人类生存、发展所面临的能源问题，最终也需要依靠核能来解决。核探测技术在地学中主要应用于放射性勘查。在农业中的应用主要有同位素示踪技术与核辐射技术两个方面。核辐射技术在农业科学中主要应用于作物品种改良、害虫防治、食品贮藏保鲜和辐照刺激生物生长等各个方面。当前核科学与核技术发展的特点体现为：一方面对物质层次结构、宇宙起源等的探索不断深入，另一方面在能源、人口与健康、环境、信息、材料、农业、国家安全等领域以及多种学科的基础研究中的应用日益广泛。 　　</p> <p> 辐射在核技术的应用虽然很广泛，也相对比较成熟，但是也需要使用的得当。对于辐射剂量这一方面是有严格的规定的，使用过量不仅起不到该有的效果，而且会对工作人员造成一定的伤害。所以，有必要选用合适的放射性检测仪器对其周围的放射性进行检测。上海仁机为客户提供比较多的仪器就是环境级辐射检测仪。</p> ','summary':'核技术是建立在核科学基础之上的一门现代技术，也称为核科学技术，是现代化科学技术的组成部分。核技术应用主要包括核能的利用及同位素和辐照技术的利用。核能是一种安全、经济、清洁的能源，人类生存、发展所面临的能源问题……'}}}</div>
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
							<option value="list">(根据code或者id)获取某个分类下文章列表接口</option>
							<option value="alllist">获取全部分类下文章列表接口</option>
							<option value="detail">文章明细接口</option>
							<option value="muitllist">获取多个分类文章接口</option>
							<option value="manulist">获取品牌下文章接口</option>
						</select>
					</div>

					<div id="detailContainer" class="form-group">
						<label for="exampleInputEmail1">文章分类ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputId" placeholder="文章分类ID">
						<label for="exampleInputEmail1">文章分类Code<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
						class="form-control" id="exampleInputCode" placeholder="文章分类CODE">
					</div>

					<div id="listContainer" class="form-group">
						<label for="exampleInputEmail1">文章分类ID<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentId" placeholder="文章分类ID">
						<label for="exampleInputEmail1">文章分类CODE<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputParentCode" placeholder="文章分类CODE">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNum" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSize" placeholder="页面容量">
					</div>
					
					<div id="alllistContainer" class="form-group">
						<label for="exampleInputEmail1">页数<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageNumall" placeholder="查询的页数">
						<label for="exampleInputEmail1">数量<font color="red">&nbsp;&nbsp;*</font></label> <input type="text"
							class="form-control" id="exampleInputPageSizeall" placeholder="页面容量">
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
