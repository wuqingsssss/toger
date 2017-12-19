<form id="form_consulation">
<div class="Review_Form mgt20">
	<h5><?php echo $heading_title_add; ?></h5>
	<div class="Re_Explain">
	<?php echo $text_note; ?>
	</div>
	<ul>
		<li id="pointType">
			<span style="display: inline;"><?php echo $entry_type; ?></span>
			<?php foreach($types as $result) {?>
			<label class="radio inline">
			<input type="radio" value="<?php echo $result['value']; ?>" name="type" /></label>
			<?php echo $result['name']; ?>
			<?php } ?>
			<span id="error-type" class="error"></span>
		</li>
		<li id="tipAnswer" style="display: none;">
			<p id="answer2" style="display: none;">
				<strong>京东承诺</strong>：商品均为原装正品行货，自带机打发票，严格执行国家三包政策，享受全国联保服务。<br>
				<strong>功能咨询</strong>：咨询商品功能建议您拨打各品牌的官方客服电话，以便获得更准确的信息。 <br> 
			</p>
			<p id="answer3" style="display: none;">
				<strong>发货时间</strong>：现货：下单后一日内即可发货；在途：一般1-2天发货； 预订：一般1-6天可发货；无货：已售完，相应物流中心覆盖地区内的用户不能购买<br>
				<strong>运&nbsp;&nbsp;&nbsp;&nbsp;费</strong>：如需查看快递运输收费标准及<strong style="color:red;font-weight:normal">免运费规则</strong>，<a class="link_1" href="http://www.jd.com/help/kdexpress.aspx" target="blank">请点此查看</a><br>
				<strong>货到付款</strong>：如需查看开通货到付款地区及运费，<a class="link_1" href="http://www.jd.com/help/cod.aspx" target="blank">请点此查看</a><br>
				<strong>上门自提</strong>：上门自提不收取运费，如需查看全部自提点位置、地图、注意事项，<a class="link_1" href="http://www.jd.com/help/ziti.aspx" target="blank">请点此查看</a><br>
				<strong>物流中心</strong>：京东商城拥有北京、上海、广州三个物流中心，各物流中心覆盖不同的城市，<a class="link_1" href="http://www.jd.com/help/kdexpress.aspx" target="blank">请点此查看</a><br>
			</p>
			<p id="answer4" style="display: none;">
				<strong>限&nbsp;&nbsp;&nbsp;&nbsp;额</strong>：如需查看各银行在线支付限额，<a class="link_1" href="http://www.jd.com/help/onlinepay.aspx" target="blank">请点此查看</a><br>
				<strong>大额支付</strong>：快钱支付中的招行、工行、建行、农行、广发支持大额支付，最高单笔一次支付10000元<br>
				<strong>分期付款</strong>：单个商品价格在500元以上，可使用<strong style="color:red;font-weight:normal">中国银行</strong>、<strong style="color:red;font-weight:normal">招商银行</strong>发行的信用卡申请分期付款，<a class="link_1" href="http://www.jd.com/help/dividedpay.aspx" target="blank">查看</a><br>
				<strong>货到付款</strong>：如需查看开通货到付款地区及运费，<a class="link_1" href="http://www.jd.com/help/cod.aspx" target="blank">请点此查看</a><br>
			</p>
			<p id="answer5" style="display: none;">
				<strong>京东承诺</strong>：商品均为原装正品行货，自带机打发票，严格执行国家三包政策，享受全国联保服务。<br>
				<strong>发票类型</strong>：京东商城所售商品均自带机打发票，在提供相关企业资料证明后，可申请开取增值税发票。<a class="link_1" href="http://www.jd.com/help/invoice.aspx" target="_blank">查看</a><br>
				<strong>退 换 货</strong>：京东商城为您提供完善的退换货服务，<a class="link_1" href="http://www.jd.com/help/return_policy.aspx" target="_blank">请点此查看</a><br>
			</p>
		</li>
		<li>
			<span><?php echo $entry_content; ?></span>
			
			<textarea id="consultationContent" class="span6" rows="8" name="content" class="area1" ></textarea>
			<span id="error-content" class="error"></span>
		</li>
		<li id="column_refer_result" style="display:none;">
			<div class="column_refer_result">	
			</div>
		</li>
		<li class="buttons">
			<input type="button" id="btn_consulation" class="btn btn-primary" value="<?php echo $button_submit; ?>" />
			&nbsp;<input name="isemail" type="checkbox" checked="checked"><label><?php echo $text_email_note; ?></label>
			<span id="realConsultation" style="display:none">
			    <strong class="text1">没有想要的答案？继续提交咨询</strong>
			    <a href="#none">
				    <img id="submitConsultation" src="http://club.jd.com/Static/img/submit_1.gif">
			    </a>
			</span>
		</li>
	</ul>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#consultationContent').click(function(){
		//检测是否登录
		
	});
	
	$('#btn_consulation').click(function(){
		$.ajax({
			url: '<?php echo $action; ?>',
			type: 'post',
			data: $('#form_consulation').serialize(),
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('span.error').html('');
					
					$.each(json['error'],function(name,msg){
						$('#form_consulation #error-'+ name).html(msg);
					});
				}	 
							
				if (json['success']) {
					//显示成功提示信息
					alert('提交成功');
					
					$('span.error').html('');
					
					$('#form_consulation')[0].reset();
				}	
			}
		});
	});
});
</script>